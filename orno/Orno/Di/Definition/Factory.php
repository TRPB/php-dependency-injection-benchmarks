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
 * Factory
 */
class Factory
{
    /**
     * Return a definition based on type of concrete
     *
     * @param  string                       $alias
     * @param  mixed                        $concrete
     * @param  \Orno\Di\ContainerInterface  $container
     * @return mixed
     */
    public function __invoke($alias, $concrete, ContainerInterface $container)
    {
        if ($concrete instanceof \Closure) {
            return new ClosureDefinition($alias, $concrete, $container);
        }

        if (is_string($concrete) && class_exists($concrete)) {
            return new ClassDefinition($alias, $concrete, $container);
        }

        // if the item is not defineable we just return the value to be stored
        // in the container as an arbitrary value/instance
        return $concrete;
    }
}
