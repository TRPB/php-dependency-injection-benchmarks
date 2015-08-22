<?php 

$container = new \Pimple\Container();


$container['a'] = $container->factory(function ($container) {
	return new A();
});


$container['b'] = $container->factory(function ($container) {
	return new B($container['a']);
});
	
	
$container['c'] = $container->factory(function ($container) {
	return new C($container['b']);
});


$container['d'] = $container->factory(function ($container) {
	return new D($container['c']);
});
	

$container['e'] = $container->factory(function ($container) {
	return new E($container['d']);
});

$container['f'] = $container->factory(function ($container) {
	return new F($container['e']);
});

$container['g'] = $container->factory(function ($container) {
	return new G($container['f']);
});
		
$container['h'] = $container->factory(function ($container) {
	return new H($container['g']);
});
			
$container['i'] = $container->factory(function ($container) {
	return new I($container['h']);
});
				
$container['j'] = $container->factory(function ($container) {
	return new J($container['i']);
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