<?php 

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


	
for ($i = 0; $i < $argv[1]; $i++) {
	$j = $container['j'];
}

$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);