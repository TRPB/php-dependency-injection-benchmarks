<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class ObjectBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $concrete = $request->getConcrete();

        return $concrete;
    }
}
