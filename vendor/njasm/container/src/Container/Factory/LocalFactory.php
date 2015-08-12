<?php

namespace Njasm\Container\Factory;

use Njasm\Container\Definition\Service\Request;

class LocalFactory implements FactoryInterface
{
    protected $buildersNamespace;
    protected $buildersSuffix;

    const BUILDERS_INTERFACE = 'Njasm\Container\Definition\Builder\BuilderInterface';

    public function __construct(
        $buildersNamespace = 'Njasm\Container\Definition\Builder\\',
        $buildersSuffix = 'Builder'
    ) {
        $this->buildersNamespace = $buildersNamespace;
        $this->buildersSuffix = $buildersSuffix;
    }

    public function build(Request $request)
    {
        $key                = $request->getKey();
        $definitionsMap     = $request->getDefinitionsMap();
        $definition         = $definitionsMap->get($key);
        $builder            = $this->getBuilder($definition->getType());

        return $builder->execute($request);
    }

    protected function getBuilder($builderType)
    {
        $builder = $this->buildersNamespace . ucfirst($builderType) . $this->buildersSuffix;

        if (
            !class_exists($builder)
            || !in_array(self::BUILDERS_INTERFACE, class_implements($builder), true)
        ) {
            $message = "No available factory to build the requested service";
            $message .= " or factory does not implement " . self::BUILDERS_INTERFACE;

            throw new \OutOfBoundsException($message);
        }

        return new $builder;
    }
}
