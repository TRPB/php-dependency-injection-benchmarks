<?php
namespace Njasm\Container\Tests\Definition\Service;

use Njasm\Container\Definition\Service\DependencyBag;

class DependencyBagTest extends \PHPUnit_Framework_TestCase
{
    /** @var  DependencyBag */
    public $dependencyBag;

    public $constructor = array('A', 'B', 'C');
    public $properties = array('A' => 1, 'B' => 2);
    public $methods = array('methodOne' => array(), 'methodTwo' => array('A', 1));

    public function setUp()
    {
        $this->dependencyBag = new DependencyBag();
        $this->dependencyBag->setConstructorArguments($this->constructor);
        $this->dependencyBag->setProperties($this->properties);
        $this->dependencyBag->callMethods($this->methods);
    }

    public function testArraySettersGetters()
    {
        $this->assertEquals($this->dependencyBag->getConstructorArguments(), $this->constructor);
        $this->assertEquals($this->dependencyBag->getProperties(), $this->properties);
        $this->assertEquals($this->dependencyBag->getCallMethods(), $this->methods);

        // empty values
        $this->dependencyBag->setConstructorArguments(array());
        $this->dependencyBag->setProperties(array());
        $this->dependencyBag->callMethods(array());

        $this->assertEmpty($this->dependencyBag->getConstructorArguments());
        $this->assertEmpty($this->dependencyBag->getCallMethods());
        $this->assertEmpty($this->dependencyBag->getProperties());
    }

    public function testConstructorByIndex()
    {
        $value = $this->constructor[2];
        $this->assertEquals($value, $this->dependencyBag->getConstructorArgument(2));

        // setter
        $index = 3;
        $value = "Test-Index-Setter";
        $this->dependencyBag->setConstructorArgument($index, $value);
        $this->assertEquals($this->dependencyBag->getConstructorArgument($index), $value);

        // Exception
        $this->setExpectedException('\Exception');
        $this->dependencyBag->getConstructorArgument(10);
    }


    public function testProperties()
    {
        $this->assertEquals(1, $this->dependencyBag->getProperty('A'));

        // setter
        $value = -1;
        $this->dependencyBag->setProperty('A', $value);
        $this->assertEquals($this->dependencyBag->getProperty('A'), $value);

        // Exception
        $this->setExpectedException('\Exception');
        $this->dependencyBag->getProperty('Non-Existent');
    }

    public function testCallMethods()
    {
        $this->assertEmpty($this->dependencyBag->getCallMethod('methodOne'));

        // setter
        $value = array('B' => 0);
        $this->dependencyBag->callMethod('methodOne', $value);
        $this->assertArrayHasKey('B', $this->dependencyBag->getCallMethod('methodOne'));

        // Exception
        $this->setExpectedException('\Exception');
        $this->dependencyBag->getCallMethod('Non-Existent');
    }
}
