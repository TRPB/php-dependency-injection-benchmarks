<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class AliasBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $value = $request->getConcrete();

        return $request->getContainer()->get($value);
    }
}
