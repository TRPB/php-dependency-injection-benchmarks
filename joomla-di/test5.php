<?php 

$di = new Joomla\DI\Container();
$di->buildObject('A', true);
$di->buildObject('B', false);

$t1 = microtime(true);
for ($i = 0; $i < 10000; $i++) {
	$b = $di->get('B', true);
}
$t2 = microtime(true);

$results = [
'time' => $t2 - $t1,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);