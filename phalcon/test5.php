<?php 

$di = new Phalcon\DI();
$di->set('A', function() {
	return new A();
});

$di->set('B', function() use ($di) {
	return new B($di->getShared('A'));
});	
	

//trigger autoload
$b = $di->get('B');
unset($b);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$b = $di->get('B');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);