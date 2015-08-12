<?php

namespace Njasm\Container\Definition;

use Njasm\Container\Definition\DefinitionType;

class Definition implements DefinitionInterface
{
    protected $key;
    protected $concrete;
    protected $type;
    
    public function __construct($key, $concrete, DefinitionType $type)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException("key cannot be empty.");
        }
        $this->key = $key;
        $this->concrete = $concrete;
        $this->type = $type;
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
}

