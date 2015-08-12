<?php 
$container = new \Njasm\Container\Container();

// register a factory to instantiate the object everytime
$container->set('B', function() use($container) {
	return new B($container->get('A'));
});


//trigger autoloader
$b = $container->get('B');
unset($b);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$b = $container->get('B');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);