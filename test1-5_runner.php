<?php
//You shouldn't have max_exectution_time set high enough to run these benchmarks 
ini_set('max_execution_time', 90000);
opcache_reset();

//Number of times to run each test before taking an average 
$runs = 10;

//Containers to be tested (dir names)
$containers =  ['zend-servicemanager', 'dice'];


//Default ini file to use for tests
$defaultIni = getcwd() . DIRECTORY_SEPARATOR . 'php.ini';
$inis = array_fill_keys($containers, $defaultIni);

//Phalcon needs its own ini file to load the phalcon.so extension
$inis['phalcon'] = getcwd() . DIRECTORY_SEPARATOR . 'php-phalcon.ini';

$cwd = getcwd();

//The number of tests
$numTests = 5;


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



//Some very basic styling
echo '<style>td,th {padding: 5px; border: 1px solid #aaa; text-align: right;}</style>';

$testdescriptions = [1 => 'Create single object (incl autoload time)',
					 2 => 'Create single object (excl autoload time)',
					 3 => 'Create deep object graph',
					 4 => 'Fetch the same instance from the container repeatedly',
					 5 => 'Inject a shared instance into a new object repeatedly'
	];

for ($test = 1; $test <= $numTests; $test++) {
	echo '<h2>Test ' . $test . ' - ' . $testdescriptions[$test]  . '</h2>';
	echo '<table>';
	
	echo '<thead><tr><th>Container</th><th>Time</th><th>Memory</th><th>Files</th></thead>';
	
	foreach ($containers as $container) {
		//chdir($cwd);
		//chdir('./' . $container);
		$memory = [];
		$time = [];
		$files = [];
		$output = [];
		
		for ($i = 0; $i < $runs; $i++) {
			$output = runScript('./' . $container . '/test' . $test . '.php', $inis[$container]);
			$result = json_decode($output[0]);
			if (!is_object($result)) echo $container . $test . '<br />';
			$time[] = $result->time;
			$memory[] = $result->memory;
			$files[] = $result->files;
		}
		
		//average memory and file count is pointless here, but it's included for completenesss
		echo '<tr>';
		echo '<td>' . $container .'</td>';
		echo '<td>' . average($time) . '</td>';
		echo '<td>' . average($memory) . '</td>';
		echo '<td>' . (average($files)-2) . '</td>';
		echo '</tr>';
		
		flush();
	}
	
	
	echo '</table>';
	flush();
}


die;
echo '<h2>Test 6</h2>';

//Calculate the average overhead of running $test6Iterations php scripts with a specified ini file
$blankScript = 'blank.php';

$overheads = [];
chdir($cwd);
for ($i = 0; $i < $runs; $i++) {
	$t1 = microtime(true);
	
	for ($i = 0; $i < $test6Iterations; $i++) {
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
		for ($i = 0; $i < $test6Iterations; $i++) {
			$output = runScript('./test6a.php', $inis[$container], [1]);			
		}
		$t2 = microtime(true);
		
		$result = json_decode($output[0]);
		$results[] = $t2 - $t1;
	}
	
	$result = average($results);
	
	echo '<tr>';
	echo '<td>' . $container .'</td>';
	echo '<td>' . ($result - $overhead) . '</td>';
	echo '<td>' . average([$result['memory']]) . '</td>';
	echo '<td>' . average([$result['files']])  . '</td>';
	echo '</tr>';
	
}
