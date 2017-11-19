<?php 

use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
$serviceManager = new \Zend\ServiceManager\ServiceManager([
    'factories' => [
        'A' => ReflectionBasedAbstractFactory::class,
    ],
]);
$serviceManager->setShared('A', true);

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