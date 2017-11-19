<?php 

use mindplay\unbox\ContainerFactory;

$factory = new ContainerFactory();
$factory->register("A");
$factory->register("B");
$factory->register("C");
$factory->register("D");
$factory->register("E");
$factory->register("F");
$factory->register("G");
$factory->register("H");
$factory->register("I");
$factory->register("J");
$di = $factory->createContainer();

for ($i = 0; $i < $argv[1]; $i++) {
	$j = $di->create('J');
}

$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);
