<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-21
 * Time: 下午3:52
 *
 * FUCTION:
 * publish/subscribe
 */

$config = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'vhost' => '/',
    'login' => 'guest',
    'password' => 'guest'

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
$exchange->setName('exchange.register');//business exchange
$exchange->setType(AMQP_EX_TYPE_FANOUT);//broadcast 广播模式
$exchange->setFlags(AMQP_DURABLE);//持久化
$exchange->declare();//重新声明

for ($i = 1; $i <= 100; $i++) {

    $registerInfo=[
        'user_name'=>'user_'.$i,
    ];
    $result = $exchange->publish(json_encode($registerInfo));
}

