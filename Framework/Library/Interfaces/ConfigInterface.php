<?php

namespace Framework\Library\Interfaces;

/**
 * 数据基础模型接口
 * Class Db
 */
interface ConfigInterface{

    /**
     * 读取配置
     * @param $keys
     * @return mixed
     */
    public function get($keys);

    /**
     * 设置数据
     * @param $key
     * @param $val
     * @return mixed
     */
    public function set($key,$val);
}