<?php

namespace Njasm\Container\Definition\Service;

use Njasm\Container\Definition\DefinitionsMap;
use Njasm\Container\ServicesProviderInterface;

class Request
{
    /** @var string */
    protected $key;

    /** @var ServicesProviderInterface */
    protected $container;

    /** @var DefinitionsMap */
    protected $definitionsMap;

    /** @var array */
    protected $providers;

    /** @var DependencyBag */
    protected $dependencyBag;

    public function __construct(
        $key,
        DefinitionsMap $definitionsMap,
        array $providers,
        ServicesProviderInterface $container,
        DependencyBag $dependencyBag = null
    ) {
        $key = trim($key);

        if (empty($key)) {
            throw new \InvalidArgumentException("Key cannot be empty.");
        }

        $this->key              = $key;
        $this->definitionsMap   = $definitionsMap;
        $this->providers        = $providers;
        $this->container        = $container;
        $this->dependencyBag    = $dependencyBag ?: new DependencyBag();
    }

    /**
     * Returns this Request Key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Returns the Container.
     *
     * @return ServicesProviderInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Returns the Definitions Map.
     *
     * @return DefinitionsMap
     */
    public function getDefinitionsMap()
    {
        return $this->definitionsMap;
    }

    /**
     * Returns all Sub-Containers registered.
     *
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * Returns this Key's concrete value.
     *
     * @return mixed
     */
    public function getConcrete()
    {
        return $this->getDefinition()->getConcrete();
    }

    /**
     * Check if Definition exists for this key.
     *
     * @return bool
     */
    protected function definitionExists()
    {
        return $this->definitionsMap->has($this->key);
    }

    /**
     * Return Definition for this key if definition exists, null otherwise.
     *
     * @return \Njasm\container\Definition\Definition|null
     */
    protected function getDefinition()
    {
        return $this->definitionsMap->get($this->key);
    }

    /**
     * Constructor arguments to be injected.
     *
     * @return array
     */
    public function getConstructorArguments()
    {
        return $this->dependencyBag->getConstructorArguments();
    }

    /**
     * Properties to be injected, when service was requested to the container.
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->dependencyBag->getProperties();
    }

    /**
     * Methods to call, when service was requested to the container.
     *
     * @return array
     */
    public function getMethodCalls()
    {
        return $this->dependencyBag->getCallMethods();
    }

    /**
     * Defualt Constructor arguments to be injected.
     *
     * @return array
     */
    public function getDefaultConstructorArguments()
    {
        return $this->getDefinition()->getConstructorArguments();
    }

    /**
     * Default Properties and values to set when this service was registered.
     *
     * @return array
     */
    public function getDefaultProperties()
    {
        if (!$this->definitionExists()) {
            return array();
        }

        return $this->getDefinition()->getProperties();
    }

    /**
     * Default Methods to call when this service was registered.
     *
     * @return array
     */
    public function getDefaultMethodCalls()
    {
        if (!$this->definitionExists()) {
            return array();
        }

        return $this->getDefinition()->getCallMethods();
    }
}
