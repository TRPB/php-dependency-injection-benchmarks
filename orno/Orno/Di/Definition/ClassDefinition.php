<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see the LICENSE file)
 */
namespace Orno\Di\Definition;

use Orno\Di\ContainerInterface;

/**
 * ClassDefinition
 */
class ClassDefinition implements DefinitionInterface
{
    /**
     * @var \Orno\Di\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * Constructor
     *
     * @param string                      $alias
     * @param string                      $concrete
     * @param \Orno\Di\ContainerInterface $container
     */
    public function __construct($alias, $concrete, ContainerInterface $container)
    {
        $this->alias     = $alias;
        $this->class     = $concrete;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $args = [])
    {
        $object = $this->constructorInjection($args);

        return $this->invokeMethods($object);
    }

    /**
     * {@inheritdoc}
     */
    public function withArgument($arg)
    {
        $this->arguments[] = $arg;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withArguments(array $args)
    {
        foreach ($args as $arg) {
            $this->withArgument($arg);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withMethodCall($method, array $args = [])
    {
        $this->methods[] = [
            'method'    => $method,
            'arguments' => $args
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withMethodCalls(array $methods = [])
    {
        foreach ($methods as $method => $args) {
            $this->withMethodCall($method, $args);
        }

        return $this;
    }

    /**
     * Instantiate object with dependencies
     *
     * @param  array  $args - allow runtime override of stored values with literal values
     * @return object
     */
    protected function constructorInjection(array $args = [])
    {
        $reflection = new \ReflectionClass($this->class);

        $args = (empty($args)) ? $this->arguments : $args;

        $resolvedArguments = [];

        foreach ($args as $arg) {
            if (is_string($arg) && ($this->container->isRegistered($arg) || class_exists($arg))) {
                $resolvedArguments[] = $this->container->get($arg);
            } else {
                $resolvedArguments[] = $arg;
            }
        }

        return $reflection->newInstanceArgs($resolvedArguments);
    }

    /**
     * Invoke methods on resolved object
     *
     * @param  object $object
     * @return object
     */
    protected function invokeMethods($object)
    {
        foreach ($this->methods as $method) {
            $reflection = new \ReflectionMethod($object, $method['method']);

            $args = [];

            foreach ($method['arguments'] as $arg) {
                $args[] = ($this->container->isRegistered($arg)) ? $this->container->get($arg) : $arg;
            }

            $reflection->invokeArgs($object, $args);
        }

        return $object;
    }
}
