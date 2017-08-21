<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-18
 * Time: 下午7:16
 */

$config = [
    'host' => '127.0.0.1',
    'vhosts' => '/',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
];

$conn = new \AMQPConnection($config);
$conn->connect();

$channel = new \AMQPChannel($conn);
$channel->qos(0, 0);

$queueName = "queue.activity";
$exchangeName = "exchange.activity";
$routingKey = "route.activity";

$exchange = new \AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_DIRECT);//direct
$exchange->setFlags(AMQP_DURABLE);//chijiuhua
$exchange->declare();

$queue = new \AMQPQueue($channel);
$queue->setName($queueName);
$queue->setFlags(AMQP_DURABLE);//chijiuhua duilie
$queue->declare();//chongxin shengming
$queue->bind($exchangeName, $routingKey);




for($i=1;$i<=400;$i++){
    $result=$exchange->publish('hello world '.$i,$routingKey);
}





