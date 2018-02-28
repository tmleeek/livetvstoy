<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('TEST_KEY', 'hello_tystoybox_web1_6381');
$redis = new Redis();
try {
        $redis->connect('10.0.0.27', 6381);
        $redis->set(TEST_KEY, 'yes');
        $glueStatus = $redis->get(TEST_KEY);
        if ($glueStatus) {
                $testKey = TEST_KEY;
                echo "Glued with the Redis key value store:" . PHP_EOL;
                echo "1. Got value '{$glueStatus}' for key '{$testKey}'." . PHP_EOL;
                /*
                if ($redis->delete(TEST_KEY)) {
                        echo "2. And already removed the key/value pair again." . PHP_EOL;
                } */
        } else {
                echo "Not glued with the Redis key value store." . PHP_EOL;
        }
} catch (RedisException $e) {
$exceptionMessage = $e->getMessage();
echo "{$exceptionMessage}. Not glued with the Redis key value store." . PHP_EOL;
}
