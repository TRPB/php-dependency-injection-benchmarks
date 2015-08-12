<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class PrimitiveBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $key    = $request->getKey();
        $definitionsMap = $request->getDefinitionsMap();
        $definition = $definitionsMap[$key];
        
        return $definition->getConcrete();
    }
}
