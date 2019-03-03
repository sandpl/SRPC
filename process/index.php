<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 19-3-3
 * Time: 下午6:23
 */




$urls = [
    'http://www.baidu.com',
    'http://www.google.com',
];

$workers = [];
for ($i = 0; $i < 2; $i++) {
    $process = new swoole_process(function (swoole_process $pro) use ($urls, $i){
        //echo 111;
        //$pro->exec('/usr/bin/php', ['./script.php']);
        curlData($urls[$i]);

    },true);

    $pid = $process->start();
    $workers[$pid] = $process;
}

foreach ($workers as $process) {
    echo $process->read();
}

function curlData($url) {
    echo $url . PHP_EOL;
    sleep(1);
}





