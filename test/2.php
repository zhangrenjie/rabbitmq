<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-17
 * Time: 下午4:18
 */
//comsumer demo

$config = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhost' => '/',
];

//connection
$conn = new \AMQPConnection($config);
if (!$conn->connect()) {
    die('connect error');
}

//channel
$channel = new \AMQPChannel($conn);

//exchange
$exchangeName = 'test_exchange';
$exchange = new \AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);
$exchange->declare();

//queue
$queue = new \AMQPQueue($channel);
$queue->setName('demo_queue');
$queue->setFlags(AMQP_DURABLE);//持久化

//绑定交换机与队列，并指定路由键
$routingKey = "demo_route";
$queue->bind($exchangeName, $routingKey);

//Consume messages from a queue
$queue->consume('consumeMessage', AMQP_AUTOACK);//自动ACK应答

/*
 * comsume()阻塞
 * 程序(回收)会进入持续侦听状态，每收到一个消息就会调用callback指定的函数一次，直到某个callback函数返回FALSE才结束。
 *
 *get()一次性
 *不管取到取不到消息都会立即返回，一般情况下使用轮询处理消息队列就要用这种方式；
 *
 * */


//消费回调函数
function comsumeMessage($envelope, $queue)
{
    var_dump($envelope->getRoutingKey);
    $msg = $envelope->getBody();
    echo $msg . "\n"; //处理消息
}








