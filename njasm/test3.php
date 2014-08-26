<?php 
require_once '../testclasses.php';
function __autoload($className)
{
	$className = ltrim($className, '\\');
	$fileName  = '';
	$namespace = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	require $fileName;
}


$container = new \Njasm\Container\Container();

// register a factory to instantiate the object everytime
$container->set('A', function() {
	return new A();
});

$container->set('B', function() use ($container) {
	return new B($container->get('A'));
});


$container->set('C', function() use ($container) {
	return new C($container->get('B'));
});



$container->set('D', function() use ($container) {
	return new D($container->get('C'));
});



$container->set('E', function() use ($container) {
	return new E($container->get('D'));
});



$container->set('F', function() use ($container) {
	return new F($container->get('E'));
});
		


$container->set('G', function() use ($container) {
	return new G($container->get('F'));
});


$container->set('H', function() use ($container) {
	return new H($container->get('G'));
});
		

$container->set('I', function() use ($container) {
	return new I($container->get('H'));
});


$container->set('J', function() use ($container) {
	return new J($container->get('I'));
});
							

//trigger autoloader
$j = $container->get('J');
unset($j);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$j = $container->get('J');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);