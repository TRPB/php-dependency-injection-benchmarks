<?php

$container = new League\Container\Container;
// register the reflection container as a delegate to enable auto wiring
$container->delegate(
    new League\Container\ReflectionContainer
);
$container->share('A');
//trigger all autoloaders
$b = $container->get('B');
unset($b);

$t1 = microtime(true);
for ($i = 0; $i < 10000; $i++) {
	$a = $container->get('B');
}
$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);