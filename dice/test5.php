<?php 

require_once '../testclasses.php';
require_once 'dice.php';

$dice = new \Dice\Dice;
$rule = new \Dice\Rule;
$rule->shared = true;

$dice->addRule('A', $rule);

//Trigger all autoloaders
$b = $dice->create('B');
unset($b);


$t1 = microtime(true);

for ($i = 0; $i < 10000; $i++) {
	$b = $dice->create('B');
}

$t2 = microtime(true);

echo $t2 - $t1;

echo '<br /># Files: ' . count(get_included_files());
echo '<br />Memory usage:' . (memory_get_peak_usage()/1024/1024) . 'mb';