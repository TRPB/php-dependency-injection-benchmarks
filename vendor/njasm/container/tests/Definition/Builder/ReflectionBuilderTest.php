<?php

namespace Njasm\Container\Tests\Definition\Builder;

use Njasm\Container\Container;

class ReflectionBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testReflection()
    {
        $returnValue = $this->container->get('\Njasm\Container\Tests\Definition\Builder\NoConstructArgs');

        $this->assertInstanceOf('\Njasm\Container\Tests\Definition\Builder\NoConstructArgs', $returnValue);
    }

    public function testArgsNull()
    {
        $returnValue = $this->container->get('\Njasm\Container\Tests\Definition\Builder\ConstructArgsNull');

        $this->assertInstanceOf('\Njasm\Container\Tests\Definition\Builder\ConstructArgsNull', $returnValue);
    }

    public function testArgsString()
    {
        $returnValue = $this->container->get('\Njasm\Container\Tests\Definition\Builder\ConstructArgsString');

        $this->assertInstanceOf('\Njasm\Container\Tests\Definition\Builder\ConstructArgsString', $returnValue);
        $this->assertEquals("test", $returnValue->attribute);
    }

    public function testArgsObject()
    {
        $returnValue = $this->container->get('\Njasm\Container\Tests\Definition\Builder\ConstructArgsObject');

        $this->assertInstanceOf('\Njasm\Container\Tests\Definition\Builder\ConstructArgsObject', $returnValue);
        $this->assertInstanceOf('\SplObjectStorage', $returnValue->attribute);
    }

    public function testUnresolvable()
    {
        $this->setExpectedException('\Exception');
        $returnValue = $this->container->get('\Njasm\Container\Tests\Definition\Builder\ConstructUnableResolve');
    }

    public function testUnreachableObject()
    {
        $this->setExpectedException('\Interop\Container\Exception\ContainerException');
        $returnValue = $this->container->get('Njasm\Container\Non\Existent');
    }

    public function testComplex()
    {
        $this->container->set(
            'Njasm\Container\Tests\Definition\Builder\TestInterface',
            function () {
                return new \Njasm\Container\Tests\Definition\Builder\ImplementsInterface();
            }
        );

        $this->container->set('NoConstructArgs', new NoConstructArgs());
        $returnValue = $this->container->get('Njasm\Container\Tests\Definition\Builder\ComplexDependency');

        $this->assertInstanceOf('Njasm\Container\Tests\Definition\Builder\ComplexDependency', $returnValue);
        $this->assertInstanceOf(
            'Njasm\Container\Tests\Definition\Builder\ConstructArgsString',
            $returnValue->resolvable
        );
        $this->assertInstanceOf(
            'Njasm\Container\Tests\Definition\Builder\NoConstructArgs',
            $returnValue->containerRegistered
        );
        $this->assertInstanceOf(
            'Njasm\Container\Tests\Definition\Builder\TestInterface',
            $returnValue->interface
        );
        $this->assertInstanceOf(
            'Njasm\Container\Tests\Definition\Builder\ImplementsInterface',
            $returnValue->interface
        );
        $this->assertEquals('Default-Value', $returnValue->defaultValue);

        $returnValue2 = $this->container->get('Njasm\Container\Tests\Definition\Builder\ComplexDependency');

        $this->assertEquals($returnValue2, $returnValue);
    }

    public function testComplexUnresolvableInterface()
    {
        // missing interface binding, will throw exception
        $this->setExpectedException('\Interop\Container\Exception\ContainerException');

        $this->container->set('Njasm\Container\Tests\Definition\Builder\NoConstructArgs', new NoConstructArgs());
        $this->container->get('Njasm\Container\Tests\Definition\Builder\ComplexDependency');
    }

    public function testVariableNoDefaultValue()
    {
        // and also test Container-Interop Exception
        $this->setExpectedException('\Interop\Container\Exception\ContainerException');
        $returnValue = $this->container->get('\Njasm\Container\Tests\Definition\Builder\VariableNoDefaultValue');
    }
}

// HELPER CLASSES
class NoConstructArgs
{
    public $attribute;

    public function __construct()
    {
        $this->attribute = "NoConstructArgs";
    }
}

class ConstructArgsNull
{
    public $attribute;

    public function __construct($value = null)
    {
        $this->attribute = $value;
    }
}

class ConstructArgsString
{
    public $attribute;

    public function __construct($value = "test")
    {
        $this->attribute = $value;
    }
}

class ConstructArgsObject
{
    public $attribute;

    public function __construct(\SplObjectStorage $value)
    {
        $this->attribute = $value;
    }
}

class ConstructUnableResolve
{
    public $attribute;

    public function __construct(NonExistent $value)
    {
        $this->attribute = $value;
    }
}

class VariableNoDefaultValue
{
    public $attribute;

    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }
}

// COMPLEX

interface TestInterface
{
    public function get();
}

class ImplementsInterface implements TestInterface
{
    public function get()
    {
        return "test";
    }
}

class ComplexDependency
{
    public $defaultValue;
    public $resolvable;
    public $containerRegistered;
    public $interface;

    public function __construct(
        ConstructArgsString $resolvable,
        NoConstructArgs $containerRegistered,
        TestInterface $interface,
        $defaultValue = "Default-Value"
    ) {
        $this->defaultValue = $defaultValue;
        $this->resolvable = $resolvable;
        $this->containerRegistered = $containerRegistered;
        $this->interface = $interface;
    }
}
