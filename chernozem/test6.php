<?php 

$chernozem = new Chernozem\Container();
$chernozem['A'] = $chernozem->factory(function($chernozem) {
	return new A();
});
$chernozem['B'] = $chernozem->factory(function($chernozem) {
	return new B($chernozem['A']);
});
$chernozem['C'] = $chernozem->factory(function($chernozem) {
	return new C($chernozem['B']);
});
$chernozem['D'] = $chernozem->factory(function($chernozem) {
	return new D($chernozem['C']);
});
$chernozem['E'] = $chernozem->factory(function($chernozem) {
	return new E($chernozem['D']);
});
$chernozem['F'] = $chernozem->factory(function($chernozem) {
	return new F($chernozem['E']);
});
$chernozem['G'] = $chernozem->factory(function($chernozem) {
	return new G($chernozem['F']);
});
$chernozem['H'] = $chernozem->factory(function($chernozem) {
	return new H($chernozem['G']);
});
$chernozem['I'] = $chernozem->factory(function($chernozem) {
	return new I($chernozem['H']);
});
$chernozem['J'] = $chernozem->factory(function($chernozem) {
	return new J($chernozem['I']);
});

for ($i = 0; $i < 10000; $i++) {
	$a = $chernozem['J'];
}

$results = [
'time' => 0,
'files' => count(get_included_files()),
'memory' => memory_get_peak_usage()/1024/1024
];

echo json_encode($results);