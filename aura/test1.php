<?php 

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once '../testclasses.php';
require_once 'autoload.php';


$t1 = microtime(true);
use Aura\Di\Container;
use Aura\Di\Factory;
$di = new Container(new Factory());
$di->set('A', $di->lazyNew('A'));

for ($i = 0; $i < 10000; $i++) {
	$a = $di->newinstance('A');
}

$t2 = microtime(true);

echo ($t2 - $t1);

echo '<br /># Files: ' . count(get_included_files());
echo '<br />Memory usage:' . (memory_get_peak_usage()/1024/1024) . 'mb';