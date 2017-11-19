<?php 

$di52 = new tad_DI52_Container();

$di52->singleton('A', 'A');

//Trigger all autoloaders
$b = $di52->make('B');
unset($b);


$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$b = $di52->make('B');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);