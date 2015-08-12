<?php

namespace Njasm\Container\Adapter;

use Njasm\Container\ServicesProviderInterface;
use Symfony\Component\DependencyInjection\Container as SymfonyContainer;

class SymfonyAdapter implements ServicesProviderInterface
{
    protected $container;

    public function __construct(SymfonyContainer $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        try {
            return $this->container->get($id);
        } catch (\Exception $e) {
            // try a parameter
            try {
                return $this->container->getParameter($id);
            } catch (\Exception $ex) {
                // throw original exception
                throw $e;
            }
        }
    }

    public function has($id)
    {
        $hasService = $this->container->has($id);

        if (!$hasService) {
            return $this->container->hasParameter($id);
        }

        return $hasService;
    }
}
