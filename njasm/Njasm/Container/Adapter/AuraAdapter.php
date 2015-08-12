<?php

namespace Njasm\Container\Adapter;

use Njasm\Container\ServicesProviderInterface;
use Aura\Di\Container as AuraContainer;

class AuraAdapter implements ServicesProviderInterface
{
    protected $container;
    
    public function __construct(AuraContainer $container)
    {
        $this->container = $container;
    }
    
    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        return $this->container->has($id);
    }
}
