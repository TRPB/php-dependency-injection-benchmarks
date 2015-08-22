<?php 

$t1 = microtime(true);
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