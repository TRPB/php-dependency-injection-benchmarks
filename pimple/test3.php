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


$container = new \Pimple\Container();


$container['a'] = $container->factory(function ($c) {
	return new A();
});


$container['b'] = $container->factory(function ($c) {
	return new B($c['a']);
});
	
	
$container['c'] = $container->factory(function ($c) {
	return new C($c['b']);
});


$container['d'] = $container->factory(function ($c) {
	return new D($c['c']);
});
	

$container['e'] = $container->factory(function ($c) {
	return new E($c['d']);
});

$container['f'] = $container->factory(function ($c) {
	return new F($c['e']);
});

$container['g'] = $container->factory(function ($c) {
	return new G($c['f']);
});
		
$container['h'] = $container->factory(function ($c) {
	return new H($c['g']);
});
			
$container['i'] = $container->factory(function ($c) {
	return new I($c['h']);
});
				
$container['j'] = $container->factory(function ($c) {
	return new J($c['i']);
});



//trigger autoloader
$j = $container['j'];
unset($j);
	
	
$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$j = $container['j'];
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);