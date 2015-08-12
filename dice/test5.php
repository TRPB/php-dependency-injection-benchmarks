<?php 

$dice = new \Dice\Dice;

$dice->addRule('A', ['shared' => true]);

//Trigger all autoloaders
$b = $dice->create('B');
unset($b);


$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$b = $dice->create('B');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);