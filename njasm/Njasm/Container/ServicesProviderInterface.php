<?php

namespace Njasm\Container;

use Interop\Container\ContainerInterface;

interface ServicesProviderInterface extends ContainerInterface
{
    /**
     * Check if service is registered
     * 
     * @param   string  $key the service to check
     * @return  boolean
     */
    public function has($key);
    
    /**
     * Return the requested service instanciated
     * 
     * @param   string      $key The Servive to return
     * @return  mixed
     * 
     * @throws  Exception   If service is not registered
     */
    public function get($key);
}
