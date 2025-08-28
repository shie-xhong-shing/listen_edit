<?php
require_once 'vendor/autoload.php';
require_once 'models/question.php';

$redis = new Redis();
try {
    $redis->pconnect('127.0.0.1', 6379);
    $redis->setOption(Redis::OPT_READ_TIMEOUT, 5); // Read timeout to prevent blocking
} catch (RedisException $e) {
    echo "Redis connection error: " . $e->getMessage() . "\n";
    exit(1);
}

$channel = 'channel'; // Replace with your channel name

while (true) {
    try {
        // Subscribe to Redis channel
        $redis->subscribe([$channel], function ($redis, $channel, $message) {
            // Log before processing
            file_put_contents('log.txt', "Received message: $message\n", FILE_APPEND);

            // Write the message to a file
            file_put_contents('current_id.txt', $message);

            // Log after processing
            file_put_contents('log.txt', "Processed message: $message\n", FILE_APPEND);
        });
    } catch (RedisException $e) {
        // Log the error and attempt to reconnect
        file_put_contents('log.txt', "Redis error: " . $e->getMessage() . "\n", FILE_APPEND);
        sleep(5);  // Wait before trying to reconnect
        $redis->connect('127.0.0.1', 6379);  // Reconnect to Redis
    }
}

