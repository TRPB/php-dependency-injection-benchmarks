<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

interface BuilderInterface
{
    public function execute(Request $request);
}
