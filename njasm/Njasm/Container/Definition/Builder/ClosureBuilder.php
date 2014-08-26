<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class ClosureBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $key            = $request->getKey();
        $definitionsMap = $request->getDefinitionsMap();
        $definition     = $definitionsMap[$key];
        $concrete       = $definition->getConcrete();
        
        return $concrete();
    }
}
