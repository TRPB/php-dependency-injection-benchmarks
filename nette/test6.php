<?php


$configurator = new \Nette\Configurator();
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->defaultExtensions = array();
$configurator->addConfig(__DIR__ . '/config/services.neon');
$container = $configurator->createContainer(); // compile
	

for ($i = 0; $i < $argv[1]; $i++) {
	$j = $container->createServiceJ();
}

$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);