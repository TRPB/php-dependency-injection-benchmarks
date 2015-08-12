<?php 

$container = new \Pimple\Container();


$container['a'] = $container->factory(function ($c) {
	return new A();
});

//trigger autoloaders
$a = $container['a'];
unset($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$j = $container['a'];
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);