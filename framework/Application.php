<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 19-2-26
 * Time: 下午10:54
 */

namespace zi;


class Application
{

    public function __construct()
    {

    }

    public function bootstrap()
    {
        //mode 模式 多进程模式， 基本模式
        $serv = new \swoole_server('0.0.0.0', 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);

        $serv->set([
            'worker_num' => 8,  //cpu
            'max_request' => 10000,
        ]);
        $serv->on('connect', function ($serv, $fd, $reactor_id) {
            echo "Client:{$reactor_id} - {$fd}-Connect.\n";
        });

        $serv->on('receive', function ($serv, $fd, $reactor_id, $data) {
            $serv->send($fd, "Server: {$reactor_id} - {$fd} " . $data);
        });

        $serv->on('close', function ($serv, $fd) {
            echo "Client: Close.\n";
        });
        $serv->start();
    }

    public function run()
    {

    }

    private $httpSetting = [];




    public function Request()
    {

    }


    /**
     * 显示服务状态
     */
    private function statusUI()
    {

    }

    /**
     * 现实使用方法UI
     */
    private function usageUI()
    {

    }




}