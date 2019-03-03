<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 19-3-3
 * Time: 上午11:55
 */

$client = new Swoole\Client(SWOOLE_SOCK_TCP);

if (!$client->connect('127.0.0.1', 9501)) {
    echo '连接失败';
    exit;
}

fwrite(STDOUT, "请输入内容");

$msg = trim(fgets(STDIN));

$client->send($msg);


$result = $client->recv();
echo $result;