<?php 



$file = './container_test6.php';

if (file_exists($file)) {
	require_once $file;
	$container = new ProjectServiceContainer();
} else {
$container = new Symfony\Component\DependencyInjection\ContainerBuilder;

	$classes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
	for ($i = 0; $i < count($classes); $i++) {
		if (isset($classes[$i-1])) {
			$ref = [new Symfony\Component\DependencyInjection\Reference($classes[$i-1])];
		}
		else $ref = [];
		
		$definition = new Symfony\Component\DependencyInjection\Definition($classes[$i], $ref );
		$definition->setShared(false);
		$container->setDefinition($classes[$i], $definition);
	}
	
	$container->compile();

	$dumper = new Symfony\Component\DependencyInjection\Dumper\PhpDumper($container);
	file_put_contents($file, $dumper->dump());
}

for ($i = 0; $i < $argv[1]; $i++) {
	$j = $container->get('J');
}




$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);