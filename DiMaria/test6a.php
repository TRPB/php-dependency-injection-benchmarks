<?php 

$di = new DD\DiMaria;
for ($i = 0; $i < $argv[1]; $i++) {
	$j = $di->create('J');
}

$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);
