<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 19-3-3
 * Time: 下午7:17
 */




$http = new Swoole\Http\Server('0.0.0.0', 8001);
$http->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    $redis =  new \Swoole\Coroutine\Redis();
    $redis->connect('127.0.0.1', 6379);
    $value = $redis->get($request->get['a']);

    //mysql

    //time = max(redis, mysql)
    $response->header("Content-Type", "text/plain");
    $response->end($value);

});

$http->start();
