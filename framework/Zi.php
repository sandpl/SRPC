<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 19-2-26
 * Time: 下午10:49
 */

namespace zi;


use zi\di\Container;

define('DS', DIRECTORY_SEPARATOR);
define("BASE_PATH", dirname(getcwd()) . DS);
define("APP_PATH", BASE_PATH . 'app' . DS);
define("ZI_PATH", __DIR__ . DS);



class Zi
{
    public static $app;
    public static $container;

    public static $classMap = [
        'zi\Application' => ZI_PATH . 'Application.php',
        'zi\di\Container' => ZI_PATH . 'Di' . DS . 'Container.php',
        'zi\library\Packet' => ZI_PATH . 'Library' . DS . 'Packet.php',
        'zi\library\Helper' => ZI_PATH . 'Library' . DS . 'Helper.php',
    ];

    public function __construct()
    {

    }

    public static function init()
    {

    }


    //
    public static function autoload($className)
    {

        if (isset(self::$classMap[$className])) {
            $classFile = self::$classMap[$className];
            //echo $classFile;die;
        } else {
            return;
        }
        include $classFile;


    }



}

spl_autoload_register(["zi\Zi", 'autoload'], true, true);

Zi::$container = new Container();



