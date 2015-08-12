<?php


$configurator = new \Nette\Configurator();
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->defaultExtensions = array();
$configurator->addConfig(__DIR__ . '/config/services.neon');
$container = $configurator->createContainer(); // compile


//trigger autoloader
$a = $container->getService('b');
unset($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$a = $container->getService('b');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);