<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once '../testclasses.php';


$configurator = new \Nette\Configurator();
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->defaultExtensions = array();
$configurator->addConfig(__DIR__ . '/config/services.neon');
$container = $configurator->createContainer(); // compile


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
