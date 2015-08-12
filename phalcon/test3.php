<?php 

$di = new Phalcon\DI();

$di->set('A', function() {
	return new A();
});

$di->set('B', function() use ($di) {
	return new B($di->get('A'));
});	
	

$di->set('C', function() use ($di) {
	return new C($di->get('B'));
});
	

$di->set('D', function() use ($di) {
	return new D($di->get('C'));
});
		
		

$di->set('E', function() use ($di) {
	return new E($di->get('D'));
});
			

$di->set('F', function() use ($di) {
	return new F($di->get('E'));
});
				

$di->set('G', function() use ($di) {
	return new G($di->get('F'));
});
					

$di->set('H', function() use ($di) {
	return new H($di->get('G'));
});
						
						

$di->set('I', function() use ($di) {
	return new I($di->get('H'));
});


$di->set('J', function() use ($di) {
	return new J($di->get('I'));
});

//trigger all autoloaders
$j = $di->get('J');
unset($j);
$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$j = $di->get('J');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);