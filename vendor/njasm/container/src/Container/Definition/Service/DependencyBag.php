<?php

namespace Njasm\Container\Definition\Service;

class DependencyBag
{
    /** @var array */
    protected $constructor;

    /** @var array */
    protected $properties;

    /** @var array */
    protected $methods;

    public function __construct(array $construct = array(), array $properties = array(), array $methods = array())
    {
        $this->constructor = $construct;
        $this->properties = $properties;
        $this->methods = $methods;
    }

    public function getConstructorArguments()
    {
        return $this->constructor;
    }

    public function getConstructorArgument($index)
    {
        if (!isset($this->constructor[(int) $index])) {
            throw new \Exception();
        }

        return $this->constructor[(int) $index];
    }

    /**
     * @param   $argumentIndex
     * @param   $value
     * @return  $this
     */
    public function setConstructorArgument($argumentIndex, $value)
    {
        $this->constructor[(int) $argumentIndex] = $value;
        ksort($this->constructor);

        return $this;
    }

    /**
     * @todo    validate array.
     * @param   $arguments
     * @return  $this
     */
    public function setConstructorArguments($arguments)
    {
        $this->constructor = $arguments;
        ksort($this->constructor);

        return $this;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getProperty($propertyName)
    {
        if (!isset($this->properties[$propertyName])) {
            throw new \Exception();
        }

        return $this->properties[$propertyName];
    }

    public function setProperty($propertyName, $value)
    {
        $this->properties[(string) $propertyName] = $value;

        return $this;
    }

    /**
     * @todo    validate array, key as propertyName, value as property value.
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    public function getCallMethods()
    {
        return $this->methods;
    }

    public function getCallMethod($methodName)
    {
        if (!isset($this->methods[(string) $methodName])) {
            throw new \Exception();
        }

        return $this->methods[(string) $methodName];
    }

    public function callMethod($methodName, array $arguments = array())
    {
        $this->methods[$methodName] = $arguments;

        return $this;
    }

    /**
     * @todo    validate array with key as methodName and value as method arguments
     * @param   array $methods
     */
    public function callMethods(array $methods = array())
    {
        $this->methods = $methods;
    }
}
