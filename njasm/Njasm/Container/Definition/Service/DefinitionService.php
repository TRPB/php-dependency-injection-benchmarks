<?php

namespace Njasm\Container\Definition\Service;

use Njasm\Container\Definition\Definition,
    Njasm\Container\Definition\DefinitionType,
    Njasm\Container\Definition\Finder\LocalFinder,
    Njasm\Container\Definition\Finder\ProvidersFinder,
    Njasm\Container\Definition\Service\Request,
    Njasm\Container\Factory\LocalFactory,
    Njasm\Container\Factory\ProviderFactory;
use Njasm\Container\Exception\ContainerException;

class DefinitionService
{
    /**
     * @var array Service keys being built.
     */
    protected $buildingKeys = array();
    
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
     * Assembles a Definition object based on the concrete value supplied.
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
     * Assembles a Definition object of type Alias.
     * 
     * @param   string      $key
     * @param   string      $concrete
     * @return  \Njasm\Container\Definition\Definition
     */
    public function assembleAliasDefinition($key, $concrete)
    {
      return new Definition($key, $concrete, new DefinitionType(DefinitionType::ALIAS));  
    }
    
    /**
     * Build the requested service.
     * 
     * @param   Request     $request
     * @return  mixed
     */
    public function build(Request $request)
    {
        $key = (string) $request->getKey();
        
        // circular dependency guard
        if (array_key_exists($key, $this->buildingKeys)) {
            throw new ContainerException("Circular Dependency detected for {$key}");
        }
        
        $this->buildingKeys[$key] = true;
        
        // check local
        if ($this->localHas($request)) {
            $factory = new LocalFactory();
            $returnValue = $factory->build($request);
            
            unset($this->buildingKeys[$key]);
            
            return $returnValue;
        }
        
        // check in nested providers
        if ($this->providersHas($request)) {
            $factory = new ProviderFactory();
            $returnValue = $factory->build($request);
            
            unset($this->buildingKeys[$key]);
            
            return $returnValue;
        }
        
        // try to bail-out client call with reflection.
        // if we're able to resolve all dependencies, we'll assemble a new 
        // definition with the returned value for future use.
        $factory = new LocalFactory();

        // temporary definition
        $def = new Definition((string) $key, null, new DefinitionType(DefinitionType::REFLECTION));
        $request->getDefinitionsMap()->add($def);

        $returnValue = $factory->build($request);
        unset($this->buildingKeys[$key]);
        
        $request->getContainer()->singleton($key, $returnValue);
        
        return $returnValue;  
    }
}
