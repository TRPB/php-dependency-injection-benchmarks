<?php 

$chernozem = new Chernozem\Container();
$chernozem['A'] =  new A();
$chernozem['B'] = $chernozem->factory(function($chernozem) {
	return new B($chernozem['A']);
});

$t1 = microtime(true);
for ($i = 0; $i < 10000; $i++) {
	$a = $chernozem['B'];
}
$t2 = microtime(true);

$results = [
'time' => $t2 - $t1,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);