<?php

namespace Njasm\Container\Definition;

final class DefinitionType 
{
    const OBJECT        = 1;
    const CLOSURE       = 2;
    const PRIMITIVE     = 3;
    const REFLECTION    = 4;
    
    private $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    final public function __toString()
    {
        return $this->value;
    }
}
