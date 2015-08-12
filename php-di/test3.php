<?php 
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config-test3.php');
$builder->setDefinitionCache(new \Doctrine\Common\Cache\ArrayCache());

$container = $builder->build();

//trigger autoloader
$j = $container->get('J');
unset ($j);

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
