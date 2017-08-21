<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-21
 * Time: 下午4:41
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
$channel->qos(0, 0);

$exchange = new \AMQPExchange($channel);
$exchange->setName('exchange.logs');
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);
$exchange->declare();

//$queue = new \AMQPQueue($conn);
//$queue->setName();
//$queue->setFlags();
//$queue->declare();
//$queue->bind();


$logs = [
    'debug' => 'debug message',
    'info' => 'info message',
    'notice' => 'notice message',
    'warning' => 'warning message',
    'error' => 'error message',
    'critical' => 'critical message',
    'alert' => 'alert message',
    'emergency' => 'emergency message',
];


foreach ($logs as $routeKey => $log) {
    $exchange->publish($log, $routeKey);
}
