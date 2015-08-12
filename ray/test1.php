<?php 
require_once '../testclasses.php';
opcache_reset();
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


//Either use the injector directly, which works for the test but uses 19mb of RAM on this test and 203mb on Test 3

			$injector = Ray\Di\Injector::create([new Module]);


//or use a compiled injector, which cannot be tested because it always returns the same instance.
$cache = new \Doctrine\Common\Cache\MemcachedCache();
$m = new Memcached();
$m->addServer('localhost', 11211);
$cache->setMemcached($m);

$injector = \Ray\Di\DiCompiler::create($injector, $cache, 'ray', './tmp');

$a = $injector->getInstance('A');
$a1 = $injector->getInstance('A');

if ($a === $a1) throw new Exception('Container returned the same instance');

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