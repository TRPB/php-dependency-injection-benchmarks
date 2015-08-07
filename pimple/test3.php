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


$container = new Pimple\Container;

$classes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
for ($i = 0; $i < count($classes); $i++) {
	if (isset($classes[$i-1])) {
		$ref = $classes[$i-1];
	}
	else $ref = null;

    $class = $classes[$i];
	
    $container[$class] = $container->factory(function ($app) use ($ref, $class) {
        return new $class($app[$ref]);
    });
}


//Trigger autoloader
$a = $container['J'];
unset($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$a = $container['J'];
}

$t2 = microtime(true);

echo '<br />' . ($t2 - $t1);

echo '<br /># Files: ' . count(get_included_files());
echo '<br />Memory usage:' . (memory_get_peak_usage()/1024/1024) . 'mb';
