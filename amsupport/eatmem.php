<?php
    require_once('_check_ip.php');
    require_once('_check_auth.php');
?>
<pre>
<?php

ini_set('display_errors', 'on');

$mem_limit = ini_get('memory_limit');
$mem_limit = str_replace('M', '', $mem_limit) * 1024 * 1024;
$mem_usage = memory_get_usage();
$mem_usage_at_start = $mem_usage;

echo 'Memory limit is ' . $mem_limit . ' bytes' . PHP_EOL;
echo 'Memory usage is ' . $mem_usage . ' bytes' . PHP_EOL;

$i = 0;
$data = array();

while ($mem_usage < $mem_limit - 8 * 1024 * 1024) {
	$i++;
	$data[$i] = range(1, 100000);
	$mem_usage = memory_get_usage();
	echo "Iteration: $i, memory usage: $mem_usage" . PHP_EOL;
}

echo 'Allocated ' . ($mem_usage - $mem_usage_at_start) . ' bytes' . PHP_EOL;
