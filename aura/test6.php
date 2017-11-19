<?php 

use Aura\Di\ContainerBuilder;
$builder = new ContainerBuilder();
$di = $builder->newInstance();


$di->params['J'] = ['i' => $di->lazyNew('I')];
$di->params['I'] = ['h' => $di->lazyNew('H')];
$di->params['H'] = ['g' => $di->lazyNew('G')];
$di->params['G'] = ['f' => $di->lazyNew('F')];
$di->params['F'] = ['e' => $di->lazyNew('E')];
$di->params['E'] = ['d' => $di->lazyNew('D')];
$di->params['D'] = ['c' => $di->lazyNew('C')];
$di->params['C'] = ['b' => $di->lazyNew('B')];
$di->params['B'] = ['a' => $di->lazyNew('A')];

for ($i = 0; $i < $argv[1]; $i++) {
	$j = $di->newinstance('J');
}


$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);
