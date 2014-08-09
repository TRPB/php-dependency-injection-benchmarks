<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once '../testclasses.php';



class MyContainer extends Nette\DI\Container
{

	public function createServiceA()
	{
		return new A();
	}

	public function createServiceB()
	{
		return new B($this->getService('a'));
	}

	public function createServiceC()
	{
		return new C($this->getService('b'));
	}

	public function createServiceD()
	{
		return new D($this->getService('c'));
	}

	public function createServiceE()
	{
		return new E($this->getService('d'));
	}

	public function createServiceF()
	{
		return new F($this->getService('e'));
	}

	public function createServiceG()
	{
		return new G($this->getService('f'));
	}

	public function createServiceH()
	{
		return new H($this->getService('g'));
	}

	public function createServiceI()
	{
		return new I($this->getService('h'));
	}

	public function createServiceJ()
	{
		return new J($this->getService('i'));
	}

}


$container = new MyContainer();


//Trigger autoloader
$j = $container->getService('j');
unset($j);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$j = $container->getService('j');
}

$t2 = microtime(true);

echo '<br />' . ($t2 - $t1);

echo '<br /># Files: ' . count(get_included_files());
echo '<br />Memory usage:' . (memory_get_peak_usage()/1024/1024) . 'mb';
