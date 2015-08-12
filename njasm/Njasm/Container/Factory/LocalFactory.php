<?php

namespace Njasm\Container\Factory;

use Njasm\Container\Definition\Service\Request;
use \Njasm\Container\Definition\DefinitionType;
use Njasm\Container\Definition\Builder\ClosureBuilder;
use Njasm\Container\Definition\Builder\ObjectBuilder;
use Njasm\Container\Definition\Builder\PrimitiveBuilder;
use Njasm\Container\Definition\Builder\ReflectionBuilder;
use Njasm\Container\Definition\Builder\AliasBuilder;

class LocalFactory implements FactoryInterface
{
    public function build(Request $request)
    {
        $key                = $request->getKey();
        $definitionsMap     = $request->getDefinitionsMap();
        $definition         = $definitionsMap->get($key);
        $builder            = $this->getBuilder($definition->getType());
        
        return $builder->execute($request);       
    }
    
    protected function getBuilder($builderType)
    {
        switch ($builderType) {
            case DefinitionType::PRIMITIVE:
                
                return new PrimitiveBuilder();
                
            case DefinitionType::OBJECT:
                
                return new ObjectBuilder();
                
            case DefinitionType::CLOSURE:
                
                return new ClosureBuilder();
                
            case DefinitionType::REFLECTION:
                
                return new ReflectionBuilder();
                
            case DefinitionType::ALIAS:
                
                return new AliasBuilder();
                
            default:
                
                throw new \OutOfBoundsException("No available factory to build the requested service.");
        }         
    }
}

