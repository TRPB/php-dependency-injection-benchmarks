<?php

echo "# Running DI Container benchmarks:\n\n";

foreach (glob(__DIR__ . '/*') as $framework) {
	if (!is_dir($framework)) {
		continue;
	}

	echo "## Running ", basename($framework), "\n\n";

	chdir($framework);

	echo "| num | Time (ms) | Files | Memory usage (mb) |\n";
	echo "| ---:| ---------:| -----:| -----------------:|\n";

	foreach (range(1, 5) as $testNum) {
		exec('php ' . escapeshellarg($framework . '/test' . $testNum . '.php'), $output, $exitCode);
		$formatted = trim(strip_tags(implode($output)));
		unset($output);

		preg_match_all('~(?P<number>\\d+(?:\\.\\d+)?)~', $formatted, $m);
		echo "| $testNum | " . number_format($m['number'][0], 4) . " | " . $m['number'][1] . " | " . number_format($m['number'][2], 4) . " |\n";

	}

	echo "\n\n";

}
