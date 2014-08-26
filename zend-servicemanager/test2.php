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



class ServiceConfiguration extends \Zend\ServiceManager\Config {
	public function configureServiceManager(\Zend\ServiceManager\ServiceManager $serviceManager)	{
		$serviceManager->setFactory('A', function() {
				return new A;
		});
		$serviceManager->setShared('A', false);
	}
}


$config = new ServiceConfiguration();
$serviceManager = new \Zend\ServiceManager\ServiceManager($config);


//trigger autoloaders
$a = $serviceManager->get('A');
unset($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$a = $serviceManager->get('A');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);