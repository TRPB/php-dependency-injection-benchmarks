<?php

namespace Njasm\Container\Definition;

interface DefinitionInterface
{
    public function getKey();
    public function getConcrete();
    public function getType();

    public function setConstructorArgument($argumentIndex, $value);
    public function setConstructorArguments(array $arguments);
    public function getConstructorArguments();
    public function getConstructorArgument($index);

    public function setProperty($propertyName, $value);
    public function setProperties(array $properties);
    public function getProperty($propertyName);
    public function getProperties();

    public function callMethod($methodName, array $methodArguments = array());
    public function callMethods(array $methods);
    public function getCallMethod($methodName);
    public function getCallMethods();
}
