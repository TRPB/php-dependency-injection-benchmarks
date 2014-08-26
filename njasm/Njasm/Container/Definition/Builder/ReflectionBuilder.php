<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class ReflectionBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $key = $request->getKey();          
        $reflected = new \ReflectionClass($key);

        // abstract class or interface
        if (!$reflected->isInstantiable()) {     
            $message = "Unable to resolve [{$reflected->name}]";
            $this->raiseException($message);
        }
        
        $constructor = $reflected->getConstructor();  
        $parameters = array();
        
        if (!is_null($constructor)) {         
            $parameters = $this->getDependencies($constructor, $request->getContainer());
        }
        
        return !empty($parameters) ? $reflected->newInstanceArgs($parameters) : $reflected->newInstanceArgs();
    }
    
    protected function getDependencies(\ReflectionMethod $constructor, $container)
    {
        $parameters = array();
        foreach($constructor->getParameters() as $param) {
            
            if(!$param->isDefaultValueAvailable()) {
                $parameters[] = $this->getDependency($param, $container);
                continue;
            }

            $parameters[] = $param->getDefaultValue();
        } 
        
        return $parameters;
    }
    
    protected function getDependency(\ReflectionParameter $param, $container)
    {
        $dependency = $param->getClass();

        if (is_null($dependency)) {
            $message = "Unable to resolve [{$param->name}] in {$param->getDeclaringClass()->getName()}";
            $this->raiseException($message);
        }

        return $container->get($dependency->name);        
    }
    
    protected function raiseException($message = null)
    {
        throw new \Exception($message);
    }
}
