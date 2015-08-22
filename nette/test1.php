<?php

$t1 = microtime(true);
$loader = new Nette\DI\ContainerLoader(__DIR__ . '/temp', TRUE);
$class = $loader->load('', function($compiler) {
    $compiler->loadConfig(__DIR__ . '/config/services.neon');
});
$container = new $class;





for ($i = 0; $i < 10000; $i++) {
	$a = $container->createServiceA();
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);