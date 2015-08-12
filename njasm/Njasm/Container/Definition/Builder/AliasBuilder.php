<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class AliasBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $key = $request->getKey();
        $definitionsMap = $request->getDefinitionsMap();
        $definition = $definitionsMap[$key];
        $value = $definition->getConcrete();
        
        return $request->getContainer()->get($value);
    }
}

