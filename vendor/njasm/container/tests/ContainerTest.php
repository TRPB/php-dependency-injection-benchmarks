<?php

namespace Njasm\Container\Tests;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = new \Njasm\Container\Container();
    }

    public function testHas()
    {
        $this->container->set("primitive", "primitive-def");

        $this->assertTrue($this->container->has("primitive"));
        $this->assertFalse($this->container->has("Non-Existent-Service"));
    }

    public function testAlias()
    {
        $key = 'really\long\FQCN\Class';
        $alias = 'short';
        $value = 'value';

        $this->container->set($key, $value);
        $this->container->alias($alias, $key);

        $returnValue = $this->container->get($alias);
        $this->assertEquals($value, $returnValue);
    }

    public function testAliasCircularDependency()
    {
        $key = 'really\long\FQCN\Class';
        $alias = 'short';
        $value = 'value';

        $this->container->alias($alias, $key);
        $this->container->alias($key, $alias);

        $this->setExpectedException('Interop\Container\Exception\ContainerException');
        $this->container->get($alias);
    }

    public function testGetException()
    {
        // ReflectionException
        $this->setExpectedException('\Exception');
        $this->container->get("Non-existent-service");
    }

    public function testContainerInterop()
    {
        $this->assertInstanceOf('Interop\Container\ContainerInterface', $this->container);
    }

    public function testSetAndGet()
    {
        $this->container->set("SingleClass", new SingleClass());

        $object = $this->container->get("SingleClass");
        $this->assertInstanceOf("Njasm\\Container\\Tests\\SingleClass", $object);
    }

    public function testSingletonAndGet()
    {
        $this->container->singleton("SingleClass", new SingleClass());

        $obj1 = $this->container->get("SingleClass");
        $obj2 = $this->container->get("SingleClass");

        $this->assertInstanceOf("Njasm\\Container\\Tests\\SingleClass", $obj1);
        $this->assertInstanceOf("Njasm\\Container\\Tests\\SingleClass", $obj2);

        // exactly the same object
        $obj1->concrete = "SingleTone-Test";
        $this->assertEquals($obj1->concrete, $obj2->concrete);
    }

    public function testServiceFromProvider()
    {
        $provider = $this->getServiceProvider("SingleClassOnServiceProvider");
        $this->container->provider($provider);

        //test has from provider
        $this->assertTrue($this->container->has("SingleClassOnServiceProvider"));
        // test get from provider
        $obj = $this->container->get("SingleClassOnServiceProvider");
        $this->assertInstanceOf("Njasm\\Container\\Tests\\SingleClassOnServiceProvider", $obj);
        $this->assertEquals("Object-from-Service-Provider", $obj->value);
    }

    public function testNestedDependency()
    {
        $container = &$this->container;

        $this->container->set(
            "DependentClass",
            function () use (&$container) {
                return new DependentClass($container->get("SingleClass"));
            }
        );

        $this->container->set("SingleClass", new SingleClass());

        $dependent = $this->container->get("DependentClass");
        $this->assertInstanceOf("Njasm\\Container\\Tests\\DependentClass", $dependent);
        $this->assertInstanceOf("Njasm\\Container\\Tests\\SingleClass", $dependent->getInjectedClass());
    }

    public function testCircularDependencyWithFactories()
    {
        $key1 = 'key1';
        $key2 = 'key2';

        $container = &$this->container;

        $this->container->set(
            $key1,
            function () use (&$container, $key2) {
                return $container->get($key2);
            }
        );

        $this->container->set(
            $key2,
            function () use (&$container, $key1) {
                return $container->get($key1);
            }
        );

        $this->setExpectedException('Interop\Container\Exception\ContainerException');
        $this->container->get($key1);
    }

    public function testNestedDependencyWithSingleton()
    {
        $container = &$this->container;

        $this->container->singleton(
            "DependentClass",
            function () use (&$container) {
                return new DependentClass($container->get("SingleClass"));
            }
        );

        $this->container->set("SingleClass", new SingleClass());

        //get sigleton first
        $singleton = $this->container->get("SingleClass");
        // now get a dependent go get the singleton and compare if it's the same object and not a new instance
        $dependent = $this->container->get("DependentClass");
        $this->assertInstanceOf("Njasm\\Container\\Tests\\DependentClass", $dependent);
        $this->assertInstanceOf("Njasm\\Container\\Tests\\SingleClass", $dependent->getInjectedClass());

        $this->assertTrue($singleton === $dependent->getInjectedClass());
    }

    public function testSetPrimitiveDataTypeAsService()
    {
        $this->container->set("string", "VariableString");
        $this->container->set("bool", true);
        $this->container->set("int", 123);
        $this->container->set("float", 45.678);

        $this->assertTrue("VariableString" === $this->container->get("string"));
        $this->assertTrue(true === $this->container->get("bool"));
        $this->assertTrue(123 === $this->container->get("int"));
        $this->assertTrue(45.678 === $this->container->get("float"));
    }

    public function testInstanciatedObject()
    {
        $this->container->set("SingleClass", new SingleClass());

        $objResult1 = $this->container->get("SingleClass");
        $objResult2 = $this->container->get("SingleClass");

        $this->assertTrue($objResult1 === $objResult2);
    }

    public function testRemove()
    {
        $obj = new \stdClass();
        $this->container->set("obj1", $obj);
        $this->container->set("obj2", $obj);


        $remove1 = $this->container->remove("obj1");
        $remove2 = $this->container->remove("obj2");

        $this->assertTrue($remove1 === true);
        $this->assertTrue($remove2 === true);
    }

    public function testRemoveNotRegistered()
    {
        $result = $this->container->remove("No-Service-Registered");
        $this->assertFalse($result);
    }

    public function testRemoveSingleton()
    {
        $this->container->singleton("SingleClass", new SingleClass());

        $obj = $this->container->get("SingleClass");

        $this->assertInstanceOf("\\Njasm\Container\\Tests\\SingleClass", $obj);

        $result = $this->container->remove("SingleClass");
        $this->assertTrue($result);
    }

    public function testReset()
    {
        $this->container->set("SingleClass", new SingleClass());

        $this->assertTrue($this->container->has("SingleClass"));
        $this->container->reset();

        $this->setExpectedException('\Exception');
        $this->container->get("SingleClass");
    }

    public function testResetSingleton()
    {
        $this->container->singleton("SingleClass", new singleClass());

        $this->assertTrue($this->container->has("SingleClass"));
        $this->container->reset();

        $this->setExpectedException('\Exception');
        $this->container->get("SingleClass");
    }

    public function testBind()
    {
        $this->container->bind("SingleClass", '\Njasm\Container\Tests\SingleClass');
        $obj = $this->container->get("SingleClass");
        $obj2 = $this->container->get("SingleClass");

        $this->assertFalse($obj === $obj2);
        $this->assertInstanceOf('\Njasm\Container\Tests\SingleClass', $obj);
    }

    public function testBindSingleton()
    {
        $this->container->bindSingleton("SingleClass", '\Njasm\Container\Tests\SingleClass');
        $obj = $this->container->get("SingleClass");
        $obj2 = $this->container->get("SingleClass");

        $this->assertInstanceOf('\Njasm\Container\Tests\SingleClass', $obj);
        $this->assertInstanceOf('\Njasm\Container\Tests\SingleClass', $obj2);
        $this->assertTrue($obj === $obj2);
    }

    public function testPropertyInjection()
    {
        $key = 'PropertyInjections';
        $name = 'John Doe';
        $email = 'john@localhost';

        $name2 = 'Jane Doe';
        $email2 = 'jane@localhost';

        $this->container->set(
            $key,
            new PropertyInjections(),
            array(),
            array(
                'name' => $name,
                'email' => $email
            )
        );

        $obj = $this->container->get($key);

        $this->assertEquals($email, $obj->email);
        $this->assertEquals($name, $obj->name);

        // call override
        $obj = $this->container->get($key, array(), array('name' => $name2, 'email' => $email2));

        $this->assertEquals($email2, $obj->email);
        $this->assertEquals($name2, $obj->name);
    }

    public function testPropertyInjectionThroughDefinition()
    {
        $key = 'PropertyInjections';
        $name = 'John Doe';
        $email = 'john@localhost';

        $definition = $this->container->set($key, new PropertyInjections());

        $definition->setProperty('name', $name);
        $definition->setProperty('email', $email);

        $obj = $this->container->get($key);

        $this->assertEquals($email, $obj->email);
        $this->assertEquals($name, $obj->name);
    }

    public function testMethodCalls()
    {
        $key = 'PropertyInjections';
        $name = 'John Doe';
        $email = 'john@localhost';
        $age = 20;
        $month = 01;

        $this->container->set(
            $key,
            new MethodCalls(),
            array(),
            array(),
            array(
                'setName' => array($name),
                'setEmail' => array($email),
                'setAgeAndBirthMonth' => array($age, $month)
            )
        );

        $obj = $this->container->get($key);

        $this->assertEquals($email, $obj->getEmail());
        $this->assertEquals($name, $obj->getName());
        $this->assertEquals($age, $obj->getAge());
        $this->assertEquals($month, $obj->getMonth());
    }

    public function testMethodCallsThoughDefinition()
    {
        $key = 'PropertyInjections';
        $name = 'John Doe';
        $email = 'john@localhost';
        $age = 20;
        $month = 01;

        $name2 = 'Jane Doe';
        $email2 = 'jane@localhost';
        $age2 = 40;
        $month2 = 12;

        //$definition = $this->container->bind($key, new MethodCalls());
        $definition = $this->container->bind($key, '\Njasm\Container\Tests\MethodCalls');
        $definition->callMethod('setName', array($name));
        $definition->callMethod('setEmail', array($email));
        $definition->callMethod('setAgeAndBirthMonth', array($age, $month));

        $obj = $this->container->get($key);

        $this->assertEquals($email, $obj->getEmail());
        $this->assertEquals($name, $obj->getName());
        $this->assertEquals($age, $obj->getAge());
        $this->assertEquals($month, $obj->getMonth());

        // call override
        $obj = $this->container->get(
            $key, array(), array(),
            array('setName' => array($name2), 'setEmail' => array($email2), 'setAgeAndBirthMonth' => array($age2, $month2)));

        $this->assertEquals($email2, $obj->getEmail());
        $this->assertEquals($name2, $obj->getName());
        $this->assertEquals($age2, $obj->getAge());
        $this->assertEquals($month2, $obj->getMonth());
    }

    public function testConstructorInjection()
    {
        $key = 'ConstructorInjection';
        $name = 'John Doe';
        $email = 'john@localhost';

        $name2 = 'Jane Doe';
        $email2 = 'jane@localhost';

        $this->container->bind($key, '\Njasm\Container\Tests\ConstructorInjection', array($name, $email));

        $object = $this->container->get($key);
        $this->assertEquals($name, $object->getName());
        $this->assertEquals($email, $object->getEmail());

        //override call
        $object2 = $this->container->get($key, array($name2, $email2));
        $this->assertEquals($name2, $object2->getName());
        $this->assertEquals($email2, $object2->getEmail());
    }

    /** HELPER METHODS **/

    protected function getServiceProvider($key = "SingleClassOnServiceProvider")
    {
        $provider = new \Njasm\Container\Container();

        $provider->set(
            $key,
            function () {
                return new SingleClassOnServiceProvider();
            }
        );

        return $provider;
    }
}

/** HELPER TEST CLASSES **/
/** Set Properties and method calls helpers */
class PropertyInjections
{
    public $name;
    public $email;
}

class MethodCalls
{
    protected $name;
    protected $email;
    protected $age;
    protected $birthMonth;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setAgeAndBirthMonth($age = 0, $month = 02)
    {
        $this->age = $age;
        $this->birthMonth = $month;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getMonth()
    {
        return $this->birthMonth;
    }
}

class ConstructorInjection
{
    protected $name;
    protected $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }
}

/** Dependency resolving helpers */
class SingleClass
{
    public $value;
}

class SingleClassOnServiceProvider
{
    public $value = "Object-from-Service-Provider";
}

class DependentClass
{
    public $single;

    public function __construct(SingleClass $single)
    {
        $this->single = $single;
    }

    public function getInjectedClass()
    {
        return $this->single;
    }
}
