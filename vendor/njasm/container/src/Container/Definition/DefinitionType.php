<?php

namespace Njasm\Container\Definition;

final class DefinitionType
{
    const OBJECT        = 'Object';
    const CLOSURE       = 'Closure';
    const PRIMITIVE     = 'Primitive';
    const REFLECTION    = 'Reflection';
    const ALIAS         = 'Alias';

    private $value;
    private static $validTypes = array();

    public function __construct($value)
    {
        if (empty(self::$validTypes)) {
            $class = new \ReflectionClass($this);
            self::$validTypes = $class->getConstants();
        }

        $this->validateType($value);

        $this->value = $value;
    }

    protected function validateType($value)
    {
        if (!in_array($value, self::$validTypes, true)) {
            throw new \InvalidArgumentException("DefinitionType of type: {$value} not allowed.");
        }
    }

    public function __toString()
    {
        return $this->value;
    }
}
