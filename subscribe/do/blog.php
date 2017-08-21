<?php
/**
 * Created by PhpStorm.
 * User: zrj
 * Date: 17-8-21
 * Time: 下午3:53
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
$channel->qos(0,1);

$queue = new \AMQPQueue($channel);
$queue->setName("blog.process"); //其他的业务队列可以更改为weibo、boke
$queue->setFlags(AMQP_DURABLE);
$queue->declare();
$queue->bind('exchange.register');
$queue->consume('createBlog',AMQP_AUTOACK);

function createBlog($envelope, $queue) {
    $message=$envelope->getBody();
    $userInfo=json_decode($message,true);
    $userName=$userInfo['user_name'];
    echo "{$userName}'s blog was created" . "\n";
}