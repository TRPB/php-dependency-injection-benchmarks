<?php

namespace Njasm\Container\Definition\Finder;

use Njasm\Container\Definition\Service\Request;

abstract class AbstractFinder
{
    protected $successor;
    protected $found;


    final public function has(Request $request)
    {
        return (bool) $this->handle($request);
    }

    abstract protected function handle(Request $request);
}
