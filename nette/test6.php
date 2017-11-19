<?php

$loader = new Nette\DI\ContainerLoader(__DIR__ . '/temp');
$class = $loader->load(function($compiler) {
    $compiler->loadConfig(__DIR__ . '/config/services.neon');
});
$container = new $class;
	

for ($i = 0; $i < $argv[1]; $i++) {
	$j = $container->createService('j');
}

$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);