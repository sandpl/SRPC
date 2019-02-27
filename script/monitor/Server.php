<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 19-2-27
 * Time: 下午11:36
 */

const PORT = 8811;

function port() {
    $shell = "netstat -anp 2>/dev/null| grep " . PORT . " | grep LISTEN | wc -l";
    $result = shell_exec($shell);
    if ($result != 1) {
        //TODO 报警
        echo date("Ymd H:i:s") . PHP_EOL;
    }
}

swoole_timer_tick(2000, function () {
    port();
});
