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
$exchangeName = 'test_exchange';
$queueName = 'test_queue';
$routeKey = 'key1';


//创建连接和channel
$conn = new AMQPConnection($config);
if (!$conn->connect()) {
    exit('connect to amqp broker fail');
}
$channel = new AMQPChannel($conn);

//创建交换机
$exchange = new AMQPExchange($channel);
$exchange->setName($exchangeName);
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->setFlags(AMQP_DURABLE);
echo "Exchange Status:" . $exchange->declare() . "\n";


//创建队列
$queue = new AMQPQueue($channel);

print_r($queue);
$queue->setName($queueName);
$queue->setFlags(AMQP_DURABLE);
//$queue->declare();

//绑定交换机与队列，并指定路由键
echo 'Queue Bind: ' . $queue->bind($exchangeName, $routeKey) . "\n";

//阻塞模式接收消息
$queue->consume('processMessage', AMQP_AUTOACK);

$conn->disconnect();


/**
 * 消费回调函数
 * 处理消息
 */

function processMessage($envelope, $queue)
{
    var_dump($envelope->getRoutingKey);
    $msg = $envelope->getBody();
    echo $msg . "\n"; //处理消息
}


