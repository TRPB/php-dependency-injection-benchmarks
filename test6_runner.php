<?php
//You shouldn't have max_exectution_time set high enough to run these benchmarks 
ini_set('max_execution_time', 90000);
opcache_reset();


//Increasing these numbers will inprove accuracy but increase the time this script takes to run.

//Number of times to run each test before taking an average, the higher the better
$runs = 10;

//Number of iterations (HTTP requests to mimic) in each test. This will make each test longer
//use 100 or more
$iterations = 500;

//The number of J objects to create on each iteration (each J object consits of 10 other objects)
$objects = 1;

//Containers to be tested (dir names)
//$containers =  ['dice','pimple', 'symfonydi'];
//$containers = ['pimple', 'dice', 'symfonydi'];
$containers = ['aura', 'php-di'];
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


echo '<h2>Test 6</h2>';

//Calculate the average overhead of running $iterations php scripts with a specified ini file
$blankScript = 'blank.php';

$overheads = [];
chdir($cwd);
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

echo '<thead><tr><th>Container</th><th>Time</th><th>Memory</th><th>Files</th></thead>';


foreach ($containers as $container) {	
	chdir($cwd);
	chdir('./' . $container);
	
	$results = [];
	for ($i = 0; $i < $runs; $i++) {
		$t1 = microtime(true);
		for ($i = 0; $i < $iterations; $i++) {
			$output = runScript('./test6.php', $inis[$container], [$objects]);			
		}
		$t2 = microtime(true);
		
		$test = json_decode($output[0]);
		$results[] = $t2 - $t1;
	}
	
	$result = average($results);
	
	echo '<tr>';
	echo '<td>' . $container .'</td>';
	echo '<td>' . ($result - $overhead) . '</td>';
	echo '<td>' . average([$test->memory]) . '</td>';
	echo '<td>' . average([$test->files])  . '</td>';
	echo '</tr>';
	
}
