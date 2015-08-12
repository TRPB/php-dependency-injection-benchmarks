<?php

namespace Njasm\Container\Adapter;

use Nette\DI\Container as NetteContainer;
use Njasm\Container\ServicesProviderInterface;

class NetteAdapter implements ServicesProviderInterface
{
    protected $container;

    public function __construct(NetteContainer $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->getService($id);
    }

    public function has($id)
    {
        return $this->container->hasService($id);
    }
}
