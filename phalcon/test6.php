<?php

//Work out overhead of launching 1000 PHP scripts via exec()

$t1 = microtime(true);

for ($i = 0; $i < 1000; $i++) {
	exec('php ../blank.php', $output, $exitCode);
}

$t2 = microtime(true);

$overhead = $t2 - $t1;


echo 'Overhead time: ' . $overhead . '<br />';

$ini = getcwd() . DIRECTORY_SEPARATOR . 'php-phalcon.ini';

$t1 = microtime(true);

for ($i = 0; $i < 1000; $i++) {
	exec('php -c ' . $ini. ' test6a.php', $output, $exitCode);
}


$t2 = microtime(true);

$test  = $t2 - $t1;
echo 'Test time: ' . $test . '<br />';

echo 'Benchmark time (after removing the overhead): ' . ($test - $overhead);

echo '<br /># Files: ' . count(get_included_files());
echo '<br />Memory usage:' . (memory_get_peak_usage()/1024/1024) . 'mb';