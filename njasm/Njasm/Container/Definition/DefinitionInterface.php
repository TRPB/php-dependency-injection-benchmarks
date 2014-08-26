<?php

namespace Njasm\Container\Definition;

use Njasm\Container\Definition\DefinitionType;

interface DefinitionInterface
{
    public function __construct($key, $concrete, DefinitionType $type);
    public function getKey();
    public function getConcrete();
    public function getType(); 
}

