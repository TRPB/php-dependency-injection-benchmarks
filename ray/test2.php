<?php 
require_once '../testclasses.php';

spl_autoload_register(function($className) {
	$className = ltrim($className, '\\');
	$fileName  = '';
	$namespace = '';
	if ($lastNsPos = strrpos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

	require  $fileName;
});



use \Ray\Di\Config;	

class Module extends \Ray\Di\AbstractModule
{
	public function configure()
	{
		
	}
}


$injector = Ray\Di\Injector::create([new Module]);


//trigger autoloaders
$a = $injector->getInstance('A');
unset($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$a = $injector->getInstance('A');
}

$t2 = microtime(true);

$results = [
'time' => $t2 - $t1,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);