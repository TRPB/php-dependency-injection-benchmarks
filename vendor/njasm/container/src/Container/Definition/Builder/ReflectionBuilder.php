<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;
use Njasm\Container\Exception\ContainerException;

class ReflectionBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $concrete   = $request->getConcrete();
        $reflected  = $this->getReflected($concrete);

        $this->guardAgainstNonInstantiable($reflected);

        $constructor = $reflected->getConstructor();

        if (is_null($constructor)) {
            return $reflected->newInstanceArgs();
        }

        $parameters = $this->getConstructorArguments($constructor, $request);

        return $reflected->newInstanceArgs($parameters);
    }

    protected function getReflected($key)
    {
        try {
            $reflected = new \ReflectionClass($key);
        } catch (\ReflectionException $e) {
            $this->raiseException($e->getMessage());
        }

        return $reflected;
    }

    protected function guardAgainstNonInstantiable(\ReflectionClass $reflected)
    {
        // abstract class or interface
        if (!$reflected->isInstantiable()) {
            $message = "Non-instantiable class [{$reflected->name}]";
            $this->raiseException($message);
        }
    }

    protected function getConstructorArguments(\ReflectionMethod $constructor, Request $request)
    {
        $defaultArguments = $request->getDefaultConstructorArguments();
        $arguments = $request->getConstructorArguments();

        if (!empty($arguments)) {
            $parameters = $arguments;
        } elseif (!empty($defaultArguments)) {
            $parameters = $defaultArguments;
        } else {
            $parameters = $this->getDependencies($constructor, $request->getContainer());
        }

        return $parameters;
    }

    protected function getDependencies(\ReflectionMethod $constructor, $container)
    {
        $parameters = array();
        foreach ($constructor->getParameters() as $param) {
            if (!$param->isDefaultValueAvailable()) {
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
        throw new ContainerException($message);
    }
}
