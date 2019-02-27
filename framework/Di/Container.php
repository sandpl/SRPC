<?php
/**
 * Created by PhpStorm.
 * User: zj
 * Date: 19-2-26
 * Time: 下午10:52
 */
namespace zi\di;

class Container
{
    protected $definition = [];
    protected static $instance;
    static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new static();
        return self::$instance;
    }

    public function __construct()
    {
        //echo '我被创建了';
    }

    public function set($id, $obj, array $params = [], $singleton = true)
    {

        $this->definition[$id] = [
            'obj' => $obj,
            'params' => $params,
            'singleton' => $singleton,
        ];
        return $this;
    }

    public function remove($id)
    {
        unset($this->definition[$id]);
    }

    public function clear()
    {
        $this->definition = [];
    }


    public function get($id)
    {
        if (isset($this->definition[$id])) {
            $definition = $this->definition[$id];
            if (is_object($definition['obj'])) {
                return $definition;
            } elseif (is_callable($definition['obj'])) {   //设置为单例
                $called = call_user_func_array($definition['obj'], $definition['params']);
                if ($definition['singleton']){
                    $this->set($id, $called);
                }
                return $called;
            }elseif (is_string($definition['obj']) && class_exists($definition['obj'])) {
                $reflection = new \ReflectionClass($definition['obj']);
                $ins = $reflection->newInstanceArgs($definition['params']);
                if ($definition['singleton']) {
                    $this->set($id, $ins);
                }
                return $ins;
            } else {
                return $definition['obj'];
            }
        } else {
           return null;
        }
    }

}