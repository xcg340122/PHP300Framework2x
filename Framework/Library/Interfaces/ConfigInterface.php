<?php

namespace Framework\Library\Interfaces;

/**
 * 配置处理接口
 * Interface ConfigInterface
 * @package Framework\Library\Interfaces
 */
interface ConfigInterface
{

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
    public function set($key, $val);
}