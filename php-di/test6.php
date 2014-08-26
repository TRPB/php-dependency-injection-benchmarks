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

require_once 'DI/functions.php';

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions('config-test3.php');
$cache = new \Doctrine\Common\Cache\MemcachedCache();
$m = new Memcached();
$m->addServer('localhost', 11211);

$cache->setMemcached($m);
$builder->setDefinitionCache($cache);

$container = $builder->build();


for ($i = 0; $i < $argv[1]; $i++) {
	$j = $container->get('J');
}


$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);




