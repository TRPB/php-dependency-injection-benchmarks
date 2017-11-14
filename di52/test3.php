<?php 

$di52 = new tad_DI52_Container();

//Trigger all autoloaders
$a = $di52->make('J');
unset($a);


$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$a = $di52->make('J');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);