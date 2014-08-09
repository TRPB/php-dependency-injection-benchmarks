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

$container = new \Njasm\Container\Container();


$container->set('A', new A());
$container->set('B', new B($container->get('A')));
$container->set('C', new C($container->get('B')));
$container->set('D', new D($container->get('C')));
$container->set('E', new E($container->get('D')));
$container->set('F', new F($container->get('E')));
$container->set('G', new G($container->get('F')));
$container->set('H', new H($container->get('G')));
$container->set('I', new I($container->get('H')));
$container->set('J', function() use (&$container) {
    return new J($container->get('I'));
});

//trigger autoloader
$a = $container->get('J');
unset ($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$a = $container->get('J');
	
}

$t2 = microtime(true);

echo '<br />' . ($t2 - $t1);

echo '<br /># Files: ' . count(get_included_files());
echo '<br />Memory usage:' . (memory_get_peak_usage()/1024/1024) . 'mb';