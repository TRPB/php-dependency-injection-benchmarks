<?php
/**
 * The Orno Component Library
 *
 * @author  Phil Bennett @philipobenito
 * @license MIT (see the LICENSE file)
 */
namespace Orno\Di\Definition;

/**
 * DefinitionInterface
 */
interface DefinitionInterface
{
    /**
     * Handle instansiation and manipulation of value and return
     *
     * @param  array $args
     * @return mixed
     */
    public function __invoke(array $args = []);

    /**
     * Add an argument to be injected
     *
     * @param  mixed $arg
     * @return \Orno\Di\Definition\DefinitionInterface
     */
    public function withArgument($arg);

    /**
     * Add multiple arguments to be injected
     *
     * @param  array $args
     * @return \Orno\Di\Definition\DefinitionInterface
     */
    public function withArguments(array $args);

    /**
     * Add a method to be invokes
     *
     * @param  string $method
     * @param  array  $args
     * @return \Orno\Di\Definition\DefinitionInterface
     */
    public function withMethodCall($method, array $args = []);

    /**
     * Add multiple methods to be invoked
     *
     * @param  array $methods
     * @return \Orno\Di\Definition\DefinitionInterface
     */
    public function withMethodCalls(array $methods = []);
}
