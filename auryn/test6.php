<?php 

$injector = new Auryn\Injector;
for ($i = 0; $i < 10000; $i++) {
	$a = $injector->make('J');
}

$results = [
	'time' => 0,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);