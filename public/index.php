<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 19-2-26
 * Time: 下午10:23
 */

//if (is_file('../autoload/')) {
//    require_once
//}     //这里将composer加载进来




if ('cli' !== php_sapi_name()) {
    exit('服务只能在cli模式下运行');
}

if (!extension_loaded('swoole')) {
    exit("请安装swoole扩展");
}


$http = new swoole_websocket_server('127.0.0.1', 8811);
$http->set([
    'enable_static_handler' => true,
    'document_root' => '/home/zj/gitdir/SRPC/public/view',
    'task_worker_num' => 2
]);

$http->on('request', function ($request, $response) {
    //print_r($request->get);
    //$response->cookie();
    //请求过滤
    if ($request->server['request_uri'] == '/favicon.ico') {
        $response->status(404);
        $response->end();
    }
    $response->end("<h1>HTTPServer</h1>" . json_encode($request->get));
});

$http->on('open', function ($server, $request) {
    echo "server: handshake success with fd{$request->fd} . \n";
    if ($request->fd == 'fd3') {
        swoole_timer_tick(2000, function($timer_id) {
            echo "2s";
        });
    }
});


$http->on('task', function ($serv, $taskId, $workerId, $data) {
    print_r($data);
    sleep(10);
    return "task finish";

});
$http->on('finish', function ($serv, $taskId, $data) {  //告诉worker进程
    //sleep(10);
    echo "taskId: {$taskId} \n";
    echo "finish-data-success:{$data} . \n";

});



$http->on('message', function ($server, $frame) {
    echo "receive from {$frame->fd}: {$frame->data}, opcode:{$frame->opcode}, fin->{$frame->finish} \n ";
//    $server->task([
//        'task' => 1,
//        'fd' => $frame->fd,
//    ]);

    swoole_timer_after(2000, function () use ($server, $frame){
        echo "5s-after\n";
        $server->push($frame->fd, '5s-after' . PHP_EOL);
    });

    $server->push($frame->fd, "singwa-push-seccess");
});

//加载框架文件到worker进程
$http->on('workerStart', function () {
    require_once dirname(__DIR__) . '/framework/Zi.php';
    //require_once '../framework/Zi.php';
});

$http->on("request", function () {
    (new \zi\Application())->run();

});




//注意 swoole是常驻内存的。


$serv = $http->addlistener('127.0.0.1', 9501, SWOOLE_SOCK_TCP);

//$serv = new Swoole\Server('127.0.0.1', 9501);
$serv->set([
    'worker_num' => 8,  //进程数，建议CPU核数的1-4倍
    'max_request' => 10000,

]);
$serv->on('connect', function ($serv, $from_id, $reactor_id){
    echo "Client: {$reactor_id} - {$from_id} - Connect \n";
});

$serv->on('receive', function ($serv, $from_id, $reactor_id, $data){
    $serv->send($from_id, "server: {$reactor_id} - {$from_id}" . $data . PHP_EOL);
    //echo "Client: {$from_id} - Connect \n";
});
$serv->on('close', function ($serv, $fd) {
    echo "Client: close. \n";
});

$http->start();











