<?php

namespace Njasm\Container\Tests\Definition;

use Njasm\Container\Container;
use Njasm\Container\Definition\Definition;
use Njasm\Container\Definition\DefinitionType;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGetType()
    {
        $d = new Definition("primitive", array("a"), new DefinitionType(DefinitionType::PRIMITIVE));
        $this->assertTrue($d->getType() === DefinitionType::PRIMITIVE);
    }

    public function testGetKey()
    {
        $key = "primitive";
        $d = new Definition($key, array("a"),  new DefinitionType(DefinitionType::PRIMITIVE));
        $this->assertTrue($d->getKey() === $key);
    }

    public function testGetDefinition()
    {
        $d = new Definition("primitive", array("a"),  new DefinitionType(DefinitionType::PRIMITIVE));
        $this->assertEquals(array("a"), $d->getConcrete());
    }

    public function testInvalidKey()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $d = new Definition(null, "A",  new DefinitionType(DefinitionType::PRIMITIVE));
    }

    public function testGetConstructorArguments()
    {
        $key = "primitive";
        $d = new Definition($key, array("a"),  new DefinitionType(DefinitionType::PRIMITIVE));
        $this->assertEmpty($d->getConstructorArguments());
        $this->assertEmpty($d->getProperties());
        $this->assertEmpty($d->getCallMethods());

        // setter constructor
        $d->setConstructorArgument(1, 2);
        $this->assertEquals(2, $d->getConstructorArgument(1));

        // setter constructor
        $d->setConstructorArguments(array(3, 4));
        $this->assertEquals(3, $d->getConstructorArgument(0));

        //exception
        $this->setExpectedException('\Exception');
        $d->getCallMethod('Non-Existent');
    }

    public function testProperties()
    {
        $key = "primitive";
        $d = new Definition($key, array("a"),  new DefinitionType(DefinitionType::PRIMITIVE));

        // setter
        $d->setProperties(array('name' => 2));
        $this->assertEquals(2, $d->getProperty('name'));

        // setter
        $d->setProperty('name', 3);
        $this->assertEquals(3, $d->getProperty('name'));

        // exception
        $this->setExpectedException('\Exception');
        $d->getProperty('Non-Existent');
    }

    public function testCallMethods()
    {
        $key = "primitive";
        $d = new Definition($key, array("a"),  new DefinitionType(DefinitionType::PRIMITIVE));

        // setter
        $d->callMethods(array('setName' => array(1, 2)));
        $this->assertEquals(array(1, 2), $d->getCallMethod('setName'));

        // setter
        $d->callMethod('setName', array(3, 4));
        $this->assertEquals(array(3, 4), $d->getCallMethod('setName'));

        // exception
        $this->setExpectedException('\Exception');
        $d->getCallMethod('Non-Existent');
    }
}
