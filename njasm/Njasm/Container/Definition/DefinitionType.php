<?php

namespace Njasm\Container\Definition;

final class DefinitionType 
{
    const OBJECT        = 1;
    const CLOSURE       = 2;
    const PRIMITIVE     = 3;
    const REFLECTION    = 4;
    const ALIAS         = 5;

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
        if (!in_array($value, self::$validTypes)) {
            throw new \InvalidArgumentException("DefinitionType of type: {$value} not allowed.");
        }
    }
    
    public function __toString()
    {
        return $this->value;
    }
}
