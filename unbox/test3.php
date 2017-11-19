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
$a = $di->get('J');
unset ($a);

$t1 = microtime(true);
for ($i = 0; $i < 10000; $i++) {
	$j = $di->create('J');
}
$t2 = microtime(true);

$results = [
'time' => $t2 - $t1,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);