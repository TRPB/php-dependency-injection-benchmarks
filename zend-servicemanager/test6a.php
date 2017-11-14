<?php 

use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
$serviceManager = new \Zend\ServiceManager\ServiceManager([
    'factories' => [
        'A' => ReflectionBasedAbstractFactory::class,
        'B' => ReflectionBasedAbstractFactory::class,
        'C' => ReflectionBasedAbstractFactory::class,
        'D' => ReflectionBasedAbstractFactory::class,
        'E' => ReflectionBasedAbstractFactory::class,
        'F' => ReflectionBasedAbstractFactory::class,
        'G' => ReflectionBasedAbstractFactory::class,
        'H' => ReflectionBasedAbstractFactory::class,
        'I' => ReflectionBasedAbstractFactory::class,
        'J' => ReflectionBasedAbstractFactory::class,
    ],
]);
$serviceManager->setShared('A', false);
$serviceManager->setShared('B', false);
$serviceManager->setShared('C', false);
$serviceManager->setShared('D', false);
$serviceManager->setShared('E', false);
$serviceManager->setShared('F', false);
$serviceManager->setShared('G', false);
$serviceManager->setShared('H', false);
$serviceManager->setShared('I', false);
$serviceManager->setShared('J', false);

for ($i = 0; $i < $argv[1]; $i++) {
	$j = $serviceManager->get('J');
}


$results = [
	'time' => 0,
	'files' => count(get_included_files()),
	'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);