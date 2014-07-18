<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see the LICENSE file)
 */
namespace Orno\Di;

use Orno\Cache\Cache;
use Orno\Di\Definition\Factory;
use Orno\Di\Definition\ClosureDefinition;
use Orno\Di\Definition\ClassDefinition;

/**
 * Container
 */
class Container implements ContainerInterface, \ArrayAccess
{
    /**
     * @var \Orno\Di\Definition\Factory
     */
    protected $factory;

    /**
     * @var \Orno\Cache\Cache
     */
    protected $cache;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $singletons = [];

    /**
     * @var boolean
     */
    protected $caching = true;

    /**
     * Constructor
     *
     * @param \Orno\Cache\Cache           $cache
     * @param array|ArrayAccess           $config
     * @param \Orno\Di\Definition\Factory $factory
     */
    public function __construct(
        Cache   $cache   = null,
        $config          = [],
        Factory $factory = null
    ) {
        $this->factory = (is_null($factory)) ? new Factory : $factory;
        $this->cache   = $cache;

        $this->addItemsFromConfig($config);

        $this->add('Orno\Di\ContainerInterface', $this);
        $this->add('Orno\Di\Container', $this);
    }

    /**
     * {@inheritdoc}
     */
    public function add($alias, $concrete = null, $singleton = false)
    {
        if (is_null($concrete)) {
            $concrete = $alias;
        }

        // if the concrete is an already instantiated object, we just store it
        // as a singleton
        if (is_object($concrete) && ! $concrete instanceof \Closure) {
            $this->singletons[$alias] = $concrete;
            return $this;
        }

        // get a definition of the item
        $this->items[$alias]['singleton'] = (boolean) $singleton;

        $factory = $this->getDefinitionFactory();
        $definition = $factory($alias, $concrete, $this);
        $this->items[$alias]['definition'] = $definition;

        return $definition;
    }

    /**
     * {@inheritdoc}
     */
    public function singleton($alias, $concrete = null)
    {
        return $this->add($alias, $concrete, true);
    }

    /**
     * {@inheritdoc}
     */
    public function get($alias, array $args = [])
    {
        // if we have a singleton just return it
        if (array_key_exists($alias, $this->singletons)) {
            return $this->singletons[$alias];
        }

        // invoke the correct definition
        if (array_key_exists($alias, $this->items)) {
            $definition = $this->items[$alias]['definition'];

            if ($definition instanceof ClosureDefinition || $definition instanceof ClassDefinition) {
                $return = $definition($args);
            } else {
                $return = $definition;
            }

            // store as a singleton if needed
            if (isset($this->items[$alias]['singleton']) && $this->items[$alias]['singleton'] === true) {
                $this->singletons[$alias] = $return;
            }

            return $return;
        }

        // check for and invoke a definition that was reflected on then cached
        if ($this->isCaching()) {
            if ($cached = $this->cache->get('orno::container::' . $alias)) {
                $definition = unserialize($cached);
                return $definition();
            }
        }

        // if we've got this far, we can assume we need to reflect on a class
        // and automatically resolve it's dependencies, we also cache the
        // result if a caching adapter is available
        $definition = $this->reflect($alias);

        if ($this->isCaching()) {
            $this->cache->set('orno::container::' . $alias, serialize($definition));
        }

        $this->items[$alias]['definition'] = $definition;

        return $definition();
    }

    /**
     * {@inheritdoc}
     */
    public function isRegistered($alias)
    {
        return array_key_exists($alias, $this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function isSingleton($alias)
    {
        return (
            array_key_exists($alias, $this->singletons) ||
            (array_key_exists($alias, $this->items) && $this->items[$alias]['singleton'] === true)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function enableCaching()
    {
        $this->caching = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function disableCaching()
    {
        $this->caching = false;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isCaching()
    {
        return (! is_null($this->cache) && $this->caching === true);
    }

    /**
     * Encapsulate the definition factory to allow for invokation
     *
     * @return \Orno\Di\Definition\Factory
     */
    protected function getDefinitionFactory()
    {
        return $this->factory;
    }

    /**
     * Populate the container with items from config
     *
     * @param $config array|ArrayAccess
     * @return void
     */
    protected function addItemsFromConfig($config)
    {
        if (! is_array($config) && ! $config instanceof \ArrayAccess) {
            throw new \InvalidArgumentException('You can only load definitions from and array or an object that implements ArrayAccess.');
        }

        if (empty($config)) {
            return;
        }

        if (! isset($config['di']) || ! is_array($config['di'])) {
            throw new \RuntimeException('Key "di" is missing from the definition config, or is not an array.');
        }

        foreach ($config['di'] as $alias => $options) {
            if (is_string($options) || $options instanceof \Closure) {
                $options = [
                    'class' => $options,
                ];
            }

            if (is_array($options) && array_key_exists('definition', $options)) {
                $options['class'] = $options['definition'];
            }

            $singleton = (array_key_exists('singleton', $options)) ? (boolean) $options['singleton'] : false;
            $concrete  = (array_key_exists('class', $options)) ? $options['class'] : null;

            // if the concrete doesn't have a class associated with it then it
            // must be either a Closure or arbitrary type so we just bind that
            $concrete = (is_null($concrete)) ? $options : $concrete;

            $definition = $this->add($alias, $concrete, $singleton);

            // set constructor argument injections
            if (array_key_exists('arguments', $options)) {
                $definition->withArguments((array) $options['arguments']);
            }

            // set method calls
            if (array_key_exists('methods', $options)) {
                $definition->withMethodCalls((array) $options['methods']);
            }
        }

        return $this;
    }

    /**
     * Reflect on a class, establish it's dependencies and build a definition
     * from that information
     *
     * @param  string $class
     * @return \Orno\Di\Definition\ClassDefinition
     */
    protected function reflect($class)
    {
        // try to reflect on the class so we can build a definition
        try {
            $reflection  = new \ReflectionClass($class);
            $constructor = $reflection->getConstructor();
        } catch (\ReflectionException $e) {
            throw new Exception\ReflectionException(
                sprintf('Unable to reflect on the class [%s], does the class exist and is it properly autoloaded?', $class)
            );
        }

        $factory = $this->getDefinitionFactory();
        $definition = $factory($class, $class, $this);

        if (is_null($constructor)) {
            return $definition;
        }

        // loop through dependencies and get aliases/values
        foreach ($constructor->getParameters() as $param) {
            $dependency = $param->getClass();

            // if the dependency is not a class we attempt to get a dafult value
            if (is_null($dependency)) {
                if ($param->isDefaultValueAvailable()) {
                    $definition->withArgument($param->getDefaultValue());
                    continue;
                }

                throw new Exception\UnresolvableDependencyException(
                    sprintf('Unable to resolve a non-class dependency of [%s] for [%s]', $param, $class)
                );
            }

            // if the dependency is a class, just register it's name as an
            // argument with the definition
            $definition->withArgument($dependency->getName());
        }

        return $definition;
    }

    /**
     * Array Access get
     *
     * @param  string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Array Access set
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->singleton($key, $value);
    }

    /**
     * Array Access unset
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
        unset($this->singletons[$key]);
    }

    /**
     * Array Access isset
     *
     * @param  string $key
     * @return boolean
     */
    public function offsetExists($key)
    {
        return $this->isRegistered($key);
    }
}
