<?php 
require_once '../testclasses.php';

function __autoload($className)
{
	$className = ltrim($className, '\\');
	$fileName  = '';
	$namespace = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	require $fileName;
}



$di = new Zend\Di\Di;
//trigger autoloader
$a = $di->newinstance('J');
unset ($a);


$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	//This is required otherwise ->newinstance() only creates a new instance of the top level of the code
	//If there's a better way to configure this let me know
	$di = new Zend\Di\Di;
	$a = $di->newinstance('J');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);