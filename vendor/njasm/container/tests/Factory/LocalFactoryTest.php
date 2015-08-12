<?php

namespace Njasm\Container\Tests\Factory;

use Njasm\Container\Definition\Definition;
use Njasm\Container\Definition\DefinitionsMap;
use Njasm\Container\Definition\DefinitionType;
use Njasm\Container\Definition\Service\Request;
use Njasm\Container\Factory\LocalFactory;

class LocalFactoryTest extends \PHPUnit_Framework_TestCase
{
    public $factory;

    public function setUp()
    {
        $this->factory = new LocalFactory();
    }

    public function testException()
    {
        // final classes cannot be mocked so lets use reflection to do the trick.
        // goal is to provide an invalid DefinitionType to the factory.
        $definitionType = new DefinitionType(DefinitionType::PRIMITIVE);
        $reflected = new \ReflectionProperty($definitionType, 'value');
        $reflected->setAccessible(true);
        $reflected->setValue($definitionType, 100); // non existent type.

        $definition = new Definition("TestException", null, $definitionType);
        $definitionsMap = new DefinitionsMap(array());
        $providers = array();

        $definitionsMap->add($definition);
        $container = $this->getMock('Njasm\Container\ServicesProviderInterface');
        $request = new Request("TestException", $definitionsMap, $providers, $container);

        $this->setExpectedException('OutOfBoundsException');
        $this->factory->build($request);
    }
}
