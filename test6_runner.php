<?php
//You shouldn't have max_exectution_time set high enough to run these benchmarks 
ini_set('max_execution_time', 90000);
opcache_reset();


//Increasing these numbers will inprove accuracy but increase the time this script takes to run.

//Number of times to run each test before taking an average, the higher the better
$runs = 6;

//Number of iterations (HTTP requests to mimic) in each test. This will make each test longer
//use 100 or more for a more accurate result
$iterations = 500;


//Containers to be tested (dir names)
//$containers =  ['dice','pimple', 'symfonydi'];
//$containers = ['pimple', 'dice', 'symfonydi'];
$containers = ['pimple', 'dice', 'symfonydi'];
//$containers =  ['aura', 'dice', 'nette', 'orno', 'phalcon', 'php-di', 'pimple', 'symfonydi'];


//Default ini file to use for tests
$defaultIni = getcwd() . DIRECTORY_SEPARATOR . 'php.ini';
$inis = array_fill_keys($containers, $defaultIni);

//Phalcon needs its own ini file to load the phalcon.so extension
$inis['phalcon'] = getcwd() . DIRECTORY_SEPARATOR . 'php-phalcon.ini';

$cwd = getcwd();



function average($array, $dp = 4) {
	sort($array, SORT_NUMERIC);

	$smallest = $array[0];
	$num = 0;
	$total = 0;

	//Discard any values that were over 20% slower than the smallest as something likely happened to cause a blip in speed. A single
	//slow result would skew the results using a standard mean.
	foreach ($array as $val) {
		if ($val <= $smallest * 1.2) {
			$num++;
			$total += $val;
		}
	}

	return round($total / $num, $dp);
}

//Run a PHP script via exec, using the specified php.ini
function runScript($file, $iniFile, $args = []) {
	exec('php -c ' . $iniFile . ' ' . $file . ' ' . implode(' ', $args), $output, $exitCode);
	return $output;
}


echo '<h2>Test 6 - Scalability</h2>';

echo '<p>This test measures the entire script execution time for the PHP process to launch, construct/configure the container and then
have the container construct a specified number of objects. Fast containers with a slow startup time will score worse with fewer objects but improve in the rankings 
as the number of objects is increased. Slower containers with fast startup times will rank highly with fewer objects but will lose out to faster containers once the number of objects gets high enough</p>
';
//Calculate the average overhead of running $iterations php scripts with a specified ini file
$blankScript = 'blank.php';

$overheads = [];

for ($i = 0; $i < $runs; $i++) {
	$t1 = microtime(true);
	
	for ($i = 0; $i < $iterations; $i++) {
		runScript($blankScript, $defaultIni);
	}
	$t2 = microtime(true);
	$overheads[] = $t2 - $t1;
}


$overhead = average($overheads);

echo 'Overhead time: ' . $overhead;

echo '<table>';

echo '<thead><tr><th>Container</th>';


//The number of J objects on each iteration for the tests. Each J object consists of 10 objects in total
$objects = [1, 5, 10, 20, 50, 100, 150];


foreach ($objects as $object) {
	echo '<th>' . $object *10 . ' objects</th>';
}

echo '</thead>';

foreach ($containers as $container) {	

	echo '<tr>';
	echo '<td>' . $container .'</td>';

	foreach ($objects as $objectcount) {
		$results = [];
		for ($i = 0; $i < $runs; $i++) {
			$t1 = microtime(true);
			for ($i = 0; $i < $iterations; $i++) {
				$output = runScript($container . '/test6a.php', $inis[$container], [$objectcount]);
			}
			$t2 = microtime(true);
			
			$test = json_decode($output[0]);
			if (!is_object($test)) print_r($output);
			$results[] = $t2 - $t1;
		}	
		$result = average($results);

		echo '<td>' . ($result - $overhead) . '</td>';
	}

	echo '</tr>';
	
}
