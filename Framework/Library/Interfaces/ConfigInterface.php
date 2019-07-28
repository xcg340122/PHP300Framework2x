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
     * @param string $keys 获取的键名称
     * @return mixed|string|array
     */
    public function get($keys);

    /**
     * 设置数据
     * @param string $key 设置的键名称
     * @param string|array $val 设置的值内容
     * @return mixed|string|array
     */
    public function set($key, $val);
}