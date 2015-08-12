<?php 

$container = new \Pimple\Container();


$container['a'] = function ($c) {
	return new A();
};


$container['b'] = $container->factory(function ($c) {
	return new B($c['a']);
});
	


//trigger autoloader
$b = $container['b'];
unset($b);
	
	
$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$j = $container['b'];
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);