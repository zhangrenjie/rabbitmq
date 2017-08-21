<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-18
 * Time: 上午11:12
 */

$config = [
    'host' => '127.0.0.1',
    'port' => 5672,
    'login' => 'guest',
    'password' => 'guest',
    'vhosts' => '/',
];

$conn = new \AMQPConnection($config);

try {
    $conn->connect();
} catch (\AMQPConnectionException $e) {
    die($e->getMessage());
}


$channel = new \AMQPChannel($conn);
$channel->qos(0, 0);

$queueName = 'hello_queue';
$queue = new \AMQPQueue($channel);
$queue->setName($queueName);
$queue->declare();

////Retrieve the next message from the queue
//$result = $queue->get();
//
//if (!empty($result)) {
//    echo $result->getBody();
//}

//消费回调函数
function comsumeMessage($envelope, $queue)
{
    $msg = $envelope->getBody();
    echo $msg . "\n"; //处理消息

    echo $queue->getName()."\n";
}


$queue->consume('comsumeMessage', AMQP_AUTOACK);//自动ACK应答


