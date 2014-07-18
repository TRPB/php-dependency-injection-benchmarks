<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see the LICENSE file)
 */
namespace Orno\Di\Definition;

use Orno\Di\ContainerInterface;
use Orno\Di\Exception;

/**
 * ClosureDefinition
 */
class ClosureDefinition
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
    protected $closure;

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
     * @param \Closure                    $closure
     * @param \Orno\Di\ContainerInterface $container
     */
    public function __construct($alias, \Closure $closure, ContainerInterface $container)
    {
        $this->alias     = $alias;
        $this->closure   = $closure;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $args = [])
    {
        return call_user_func_array($this->closure, $this->resolveArguments($args));
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
        throw new Exception\UnbindableMethodCallException(
            sprintf('Cannot bind a method call to a Closure aliased as [%s]', $this->alias)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function withMethodCalls(array $methods = [])
    {
        throw new Exception\UnbindableMethodCallException(
            sprintf('Cannot bind method calls to a Closure aliased as [%s]', $this->alias)
        );
    }

    /**
     * Resolves all of the arguments.  If you do not send an array of arguments
     * it will use the Definition Arguments.
     *
     * @param  array $args The arguments to us instead of $this->arguments
     * @return array The resolved arguments.
     */
    protected function resolveArguments($args = [])
    {
        $args = (empty($args)) ? $this->arguments : $args;

        $resolvedArguments = [];

        foreach ($args as $arg) {
            if (is_string($arg) && ($this->container->isRegistered($arg) || class_exists($arg))) {
                $resolvedArguments[] = $this->container->get($arg);
            } else {
                $resolvedArguments[] = $arg;
            }
        }

        return $resolvedArguments;
    }
}
