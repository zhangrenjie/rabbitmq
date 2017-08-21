<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-17
 * Time: 下午3:42
 */

$config = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhost' => '/',
];

//创建连接
$conn = new \AMQPConnection($config);
if (!$conn->connect()) {
    die('Can\'t connect to the broker');
}

//创建信道
$channel = new \AMQPChannel($conn);

//创建交换机
$exchange = new \AMQPExchange($channel);
$exchange->setName('test_exchange');
$exchange->setType(AMQP_EX_TYPE_DIRECT);// Set the type of the exchange
$exchange->setFlags(AMQP_DURABLE);//持久化
//Declare a new exchange on the broker.
$exchange->declare();//上文指定持久化交换机，此处要重新声明交换机才能生效

$message = "hello world {date('Y-m-d H:i:s')}";
$routingkey = 'test_route_key';
$exchange->publish($message, $routingkey);//Publish a message to an exchange

//由以上代码可以看到，发送消息时，只要有“交换机”就够了。
//至于交换机后面有没有对应的处理队列，发送方是不用管的。
//routingkey可以是空的字符串。


/*
 * 持久化
 *
 * 指定了持久化的交换机，在重新启动时才能重建，否则需要客户端重新声明生成才行。
 *
 * 需要特别明确的概念：交换机的持久化，并不等于消息的持久化。
 * 只有在持久化队列中的消息，才能持久化；
 * 如果没有队列，消息是没有地方存储的；
 * 消息本身在投递时也有一个持久化标志的，PHP中默认投递到持久化交换机就是持久的消息，不用特别指定。
 */



