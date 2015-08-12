<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class PrimitiveBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        return $request->getConcrete();
    }
}
