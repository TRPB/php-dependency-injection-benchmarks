<?php 

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once '../testclasses.php';
require_once 'autoload.php';


use Aura\Di\Container;
use Aura\Di\Factory;
$di = new Container(new Factory());

$di->set('A', $di->lazyNew('A'));

//Trigger the autoloader
$a = $di->get('A');
unset ($a);

$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$j = $di->get('A');
}

$t2 = microtime(true);

echo '<br />' . ($t2 - $t1);

echo '<br /># Files: ' . count(get_included_files());
echo '<br />Memory usage:' . (memory_get_peak_usage()/1024/1024) . 'mb';