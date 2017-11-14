<?php 

$container = new League\Container\Container;
// register the reflection container as a delegate to enable auto wiring
$container->delegate(
    new League\Container\ReflectionContainer
);

for ($i = 0; $i < $argv[1]; $i++) {
	$j = $container->get('J');
}

$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);