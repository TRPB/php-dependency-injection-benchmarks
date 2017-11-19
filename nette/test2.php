<?php
$loader = new Nette\DI\ContainerLoader(__DIR__ . '/temp');
$class = $loader->load(function($compiler) {
    $compiler->loadConfig(__DIR__ . '/config/services.neon');
});
$container = new $class;

//Trigger the autoloader
$a = $container->createService('a');
unset($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$a = $container->createService('a');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);