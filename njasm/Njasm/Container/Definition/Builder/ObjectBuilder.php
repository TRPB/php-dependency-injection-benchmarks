<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class ObjectBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $key    = $request->getKey();
        $definitionsMap = $request->getDefinitionsMap();
        $definition = $definitionsMap[$key];
        
        return $definition->getConcrete();
    }
}
