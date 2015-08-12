<?php 

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

for ($i = 0; $i < $argv[1]; $i++) {
	$j = $serviceManager->get('J');
}


$results = [
	'time' => 0,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);