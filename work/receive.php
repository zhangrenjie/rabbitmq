<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-18
 * Time: 下午10:05
 */

$config = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest',
];

$conn = new \AMQPConnection($config);
try {
    $conn->connect();
} catch (\AMQPConnectionException $e) {
    die($e->getMessage());
}

$channel = new \AMQPChannel($conn);

$queueName = "queue.activity";
$exchangeName = "exchange.activity";
$routingKey = "route.activity";

$queue = new \AMQPQueue($channel);
$queue->setName($queueName);
$queue->setFlags(AMQP_PASSIVE);//消极被动
$queue->declare();

$queue->bind($exchangeName, $routingKey);

function processMessage($envelope, $queue)
{
    global $i;
    echo "Message $i: " . $envelope->getBody() . "\n";
    $i++;
}


$queue->consume('processMessage', AMQP_AUTOACK);





