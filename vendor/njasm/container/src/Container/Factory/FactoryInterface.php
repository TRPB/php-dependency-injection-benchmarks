<?php

namespace Njasm\Container\Factory;

use Njasm\Container\Definition\Service\Request;

interface FactoryInterface
{
    public function build(Request $request);
}
