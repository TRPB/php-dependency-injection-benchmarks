<?php 

use Aura\Di\ContainerBuilder;
$builder = new ContainerBuilder();
$di = $builder->newInstance();


$di->set('A', $di->lazyNew('A'));
$di->params['B'] = ['a' => $di->lazyNew('A')];
$di->set('B', $di->lazyNew('B'));


//Trigger the autoloader before measuring execution time
$a = $di->newinstance('B');
unset ($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$b = $di->newinstance('B');
}

$t2 = microtime(true);

$results = [
'time' => $t2 - $t1,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);