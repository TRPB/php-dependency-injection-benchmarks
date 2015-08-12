<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class ClosureBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $concrete = $request->getConcrete();
        $arguments = $request->getConstructorArguments();
        $closure = new \ReflectionFunction($concrete);

        return $closure->invokeArgs($arguments);
    }
}
