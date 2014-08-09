<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once '../testclasses.php';


class MyContainer extends Nette\DI\Container
{

	public function createServiceA()
	{
		return new A();
	}

}



$container = new MyContainer();


$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$a = $container->getService('a');
}

$t2 = microtime(true);

echo '<br />' . ($t2 - $t1);

echo '<br /># Files: ' . count(get_included_files());
echo '<br />Memory usage:' . (memory_get_peak_usage()/1024/1024) . 'mb';
