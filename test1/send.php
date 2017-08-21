<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-18
 * Time: 上午10:58
 */

$config = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhosts' => '/',
];

//create an instance of AMQPConnection
$conn = new \AMQPConnection($config);

//establish a transient(短暂) connection with the AMQP broker
//this method will initate a connection with the AMQP broker

try {
    $conn->connect();
} catch (\AMQPConnectionException $e) {
    die($e->getMessage());
}


$channel = new \AMQPChannel($conn);


$queueName = $routingKey = 'hello_queue';
$queue = new \AMQPQueue($channel);
$queue->setName($queueName);

$message = 'hello world ' . date('Y-m-d H:i:s');
$exchange = new \AMQPExchange($channel);
$send = $exchange->publish($message, $routingKey);
var_dump('Result : ' . $send);