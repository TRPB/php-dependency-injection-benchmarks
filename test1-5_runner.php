<?php
//You shouldn't have max_exectution_time set high enough to run these benchmarks 
ini_set('max_execution_time', 90000);
opcache_reset();
$isCli = php_sapi_name() == 'cli';
function cliPrint($text, $newLine = true) {
	$isCli = php_sapi_name() == 'cli';	
	if ($isCli) {
		echo $text;
		if ($newLine) echo "\n";
	}
}

cliPrint('Starting benchmarks');

$html = '';

//Number of times to run each test before taking an average 

$runs = 10;
cliPrint('Running each test ' . $runs . ' times');

//Containers to be tested (dir names)
$containers =  ['aura', 'auryn', 'chernozem', 'di52', 'dice', 'DiMaria', 'joomla-di', 'laravel', 'league', 'nette', 'njasm', 'phalcon', 'php-di', 'pimple', 'slince-di', 'symfonydi', 'unbox', 'yii2-di', 'zend-di', 'zend-servicemanager'];


//Default ini file to use for tests
$defaultIni = getcwd() . DIRECTORY_SEPARATOR . 'php.ini';
$inis = array_fill_keys($containers, $defaultIni);

//Phalcon needs its own ini file to load the phalcon.so extension
$inis['phalcon'] = getcwd() . DIRECTORY_SEPARATOR . 'php-phalcon.ini';

$cwd = getcwd();

//The number of tests
$numTests = 5;
cliPrint('Running tests 1 - ' . $numTests);

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
$html .= '<style>td,th {padding: 5px; border: 1px solid #aaa; text-align: right;}</style>';

$testdescriptions = [1 => 'Create single object (incl autoload time)',
					 2 => 'Create single object (excl autoload time)',
					 3 => 'Create deep object graph',
					 4 => 'Fetch the same instance (service) from the container repeatedly',
					 5 => 'Inject a service into a new object repeatedly'
	];

for ($test = 1; $test <= $numTests; $test++) {
	$html .= '<h2>Test ' . $test . ' - ' . $testdescriptions[$test]  . '</h2>';
	$html .= '<table>';
	cliPrint('Starting test:' . $test);

	$containerInfo = [];

	$html .= '<thead><tr><th>Container</th><th>Time</th><th>Memory</th><th>Files</th></thead>';
	
	foreach ($containers as $container) {
		cliPrint('');
		cliPrint('Benchmarking container:' . $container);
		$memory = [];
		$time = [];
		$files = [];
		$output = [];
		
		for ($i = 0; $i < $runs; $i++) {
			cliPrint($container . ' test' . $test . ' : ' . ($i+1) . '/' . $runs);
			$output = runScript('./' . $container . '/test' . $test . '.php', $inis[$container]);
			$result = json_decode($output[0]);
			if (!is_object($result)) echo $container . $test . '<br />';
			$time[] = $result->time;
			$memory[] = $result->memory;
			$files[] = $result->files;
		}
		
		
		$containerInfo[] = ['name' => $container, 'time' => average($time), 'memory' => average($memory), 'files' => average($files)];
	}
	
	//Sort the results by time
	usort($containerInfo, function($a, $b) {
		if ($a['time'] == $b['time']) return ($a['memory'] < $b['memory']) ? -1 : 1; 

		return ($a['time'] < $b['time']) ? -1 : 1; 
	});

	foreach ($containerInfo as $containerDetail) {
		$html .= '<tr>';
		$html .= '<td>' . $containerDetail['name'] .'</td>';
		$html .= '<td>' . $containerDetail['time'] . '</td>';
		$html .= '<td>' . $containerDetail['memory'] . '</td>';
		$html .= '<td>' . $containerDetail['files'] . '</td>';
		$html .= '</tr>';
		
	}


	$html .= '</table>';

}

if (!$isCli) echo $html;
else file_put_contents('test1-5_results.html', $html);