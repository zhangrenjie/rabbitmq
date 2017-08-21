<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-3
 * Time: 下午5:43
 */

$config = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'login' => 'test',
    'password' => 'test',
    'vhost' => '/',
];

//Create connection with the AMQP broker.
$conn = new AMQPConnection($config);

//Establish a persistent connection(持久连接) with the AMQP broker.
//This method will initiate a connection with the AMQP broker初始化一个连接
if (!$conn->connect()) {
    die('Can\'t connect to the broker');
}

// Creates an AMQPChannel instance representing a channel on the given connection.
$channel = new AMQPChannel($conn);

//Create an instance of AMQPExchange
$exchange = new AMQPExchange($channel);
$exchangeName = 'test_exchange';

$exchange->setName($exchangeName);//Set the name of the exchange
$exchange->setType(AMQP_EX_TYPE_DIRECT);//Set the type of the exchange
$exchange->setFlags(AMQP_DURABLE);//Set the flags on an exchange持久化

echo 'Exchange Status:' . $exchange->declare();//Declare a new exchange on the broker

for ($i = 1; $i < 100; $i++) {
    $message = 'hello world' . $i . '  ' . date('Y-m-d H:i:s');
    $routeKey = 'key' . $i;
    $exchange->publish($message, $routeKey);
}


