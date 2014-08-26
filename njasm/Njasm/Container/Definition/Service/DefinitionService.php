<?php

namespace Njasm\Container\Definition\Service;

use Njasm\Container\Definition\Definition,
    Njasm\Container\Definition\DefinitionType,
    Njasm\Container\Definition\Finder\AbstractFinder,
    Njasm\Container\Definition\Finder\LocalFinder,
    Njasm\Container\Definition\Finder\ProvidersFinder,
    Njasm\Container\Definition\Service\Request,
    Njasm\Container\Factory\LocalFactory,
    Njasm\Container\Factory\ProviderFactory;

class DefinitionService
{
    /**
     * Finds if a service is defined globally. Globally means, in this Container or in a nested Container.
     * 
     * @param \Njasm\Container\Definition\Service\Request $request
     * @return boolean
     */
    public function has(Request $request)
    {
        return $this->localHas($request) || $this->providersHas($request);
    }
    
    /**
     * Finds if a service is defined locally in Container.
     * 
     * @param \Njasm\Container\Definition\Service\Request $request
     * @return boolean
     */
    protected function localHas(Request $request)
    {
        $finder = new LocalFinder();
        return $finder->has($request);
    }
    
    /**
     * Finds if a service is defined in a nested Container.
     * 
     * @param \Njasm\Container\Definition\Service\Request $request
     * @return boolean
     */    
    protected function providersHas(Request $request)
    {
        $finder = new ProvidersFinder();
        return $finder->has($request);
    }
    
    /**
     * Assembles a Definition Object based on the concrete value supplied.
     * 
     * @param   string      $key
     * @param   \Closure    $concrete
     * @return  \Njasm\Container\Definition\Definition
     * @throws  \OutOfBoundsException
     */
    public function assemble($key, $concrete)
    {
        $definitionType = null;
        
        if ($concrete instanceof \Closure) {
            $definitionType = new DefinitionType(DefinitionType::CLOSURE);
        }elseif (is_object($concrete)) {
            $definitionType = new DefinitionType(DefinitionType::OBJECT);
        }elseif (is_scalar($concrete)) {
            $definitionType = new DefinitionType(DefinitionType::PRIMITIVE);
        }
        
        if (!$definitionType instanceof DefinitionType) {
            throw new \OutOfBoundsException("Unknown definition type.");
        }
        
        return new Definition($key, $concrete, $definitionType);
    }
    
    /**
     * Build the requested service.
     * 
     * @param   Request     $request
     * @return  mixed
     */
    public function build(Request $request)
    {
        // check local
        if ($this->localHas($request)) {
            $factory = new LocalFactory();
            return $factory->build($request);  
        }
        
        // check in nested providers
        if ($this->providersHas($request)) {
            $factory = new ProviderFactory();
            return $factory->build($request);  
        }
        
        // try to bail-out client call with reflection.
        // if we're able to resolve all dependencies, we'll assemble a new 
        // definition with the returned value for future use.
        $factory = new LocalFactory();
        $key = $request->getKey();

        // temporary definition
        $def = new Definition((string) $key, null, new DefinitionType(DefinitionType::REFLECTION));
        $request->getDefinitionsMap()->add($def);

        $returnValue = $factory->build($request);
        $request->getContainer()->singleton($key, $returnValue);
        
        return $returnValue;  
    }
}
