<?php

namespace Njasm\Container\Definition;

use Njasm\Container\Definition\Service\DependencyBag;

class Definition implements DefinitionInterface
{
    /** @var string */
    protected $key;

    /** @var mixed */
    protected $concrete;

    /** @var DefinitionType */
    protected $type;

    /** @var DependencyBag */
    protected $dependencyBag;

    public function __construct($key, $concrete, DefinitionType $type, DependencyBag $dependencyBag = null)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException("key cannot be empty.");
        }

        $this->key = $key;
        $this->concrete = $concrete;
        $this->type = $type;
        $this->dependencyBag = $dependencyBag ?: new DependencyBag();
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getConcrete()
    {
        return $this->concrete;
    }

    public function getType()
    {
        return $this->type->__toString();
    }

    public function setConstructorArgument($argumentIndex, $value)
    {
        $this->dependencyBag->setConstructorArgument($argumentIndex, $value);

        return $this;
    }

    public function setConstructorArguments(array $arguments)
    {
        $this->dependencyBag->setConstructorArguments($arguments);

        return $this;
    }

    public function getConstructorArguments()
    {
        return $this->dependencyBag->getConstructorArguments();
    }

    public function getConstructorArgument($index)
    {
        return $this->dependencyBag->getConstructorArgument($index);
    }

    public function getProperties()
    {
        return $this->dependencyBag->getProperties();
    }

    public function setProperties(array $properties)
    {
        $this->dependencyBag->setProperties($properties);

        return $this;
    }

    public function getProperty($propertyName)
    {
        return $this->dependencyBag->getProperty($propertyName);
    }

    public function setProperty($propertyName, $value)
    {
        $this->dependencyBag->setProperty($propertyName, $value);

        return $this;
    }

    public function callMethod($methodName, array $methodArguments = array())
    {
        $this->dependencyBag->callMethod($methodName, $methodArguments);

        return $this;
    }

    public function callMethods(array $methods)
    {
        $this->dependencyBag->callMethods($methods);

        return $this;
    }

    public function getCallMethod($methodName)
    {
        return $this->dependencyBag->getCallMethod($methodName);
    }

    public function getCallMethods()
    {
        return $this->dependencyBag->getCallMethods();
    }
}
