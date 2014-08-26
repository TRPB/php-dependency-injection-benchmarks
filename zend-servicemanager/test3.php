<?php 
opcache_reset();
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
		
		
		$serviceManager->setFactory('B', function($serviceManager) {
			return new B($serviceManager->get('A'));
		});
		$serviceManager->setShared('B', false);
	
		$serviceManager->setFactory('C', function($serviceManager) {
			return new C($serviceManager->get('B'));
		});
			
		$serviceManager->setShared('C', false);
			
		$serviceManager->setFactory('D', function($serviceManager) {
			return new D($serviceManager->get('C'));
		});
	
		$serviceManager->setShared('D', false);
		
		$serviceManager->setFactory('E', function($serviceManager) {
			return new E($serviceManager->get('D'));
		});
		
		$serviceManager->setShared('E', false);
	
		$serviceManager->setFactory('F', function($serviceManager) {
			return new F($serviceManager->get('E'));
		});
		
		$serviceManager->setShared('F', false);
		
		$serviceManager->setFactory('G', function($serviceManager) {
			return new G($serviceManager->get('F'));
		});
		
		$serviceManager->setShared('G', false);
		
		$serviceManager->setFactory('H', function($serviceManager) {
			return new H($serviceManager->get('G'));
		});
		
		$serviceManager->setShared('H', false);
		
		$serviceManager->setFactory('I', function($serviceManager) {
			return new I($serviceManager->get('H'));
		});
		
		$serviceManager->setShared('I', false);
		
		$serviceManager->setFactory('J', function($serviceManager) {
			return new J($serviceManager->get('I'));
		});
		
		$serviceManager->setShared('J', false);
				
			
	}
}


$config = new ServiceConfiguration();
$serviceManager = new \Zend\ServiceManager\ServiceManager($config);


//trigger autoloaders
$j = $serviceManager->get('J');
unset($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$j = $serviceManager->get('J');
}

$t2 = microtime(true);

$results = [
	'time' => $t2 - $t1,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);