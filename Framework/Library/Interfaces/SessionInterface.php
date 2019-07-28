<?php

namespace Framework\Library\Interfaces;

/**
 * Session接口
 * Interface SessionInterface
 * @package Framework\Library\Interfaces
 */
interface SessionInterface
{
    /**
     * 启动session
     * @return mixed
     */
    public function start();

    /**
     * 获取session
     * @param string $name 获取的键名称
     * @return mixed|string|array
     */
    public function get($name='');

    /**
     * 设置session
     * @param string $name 设置的键名称
     * @param string|array $value 设置的值
     * @return mixed
     */
    public function set($name='php300',$value='');

    /**
     * 删除session
     * @param string $name 删除的键名称
     * @return mixed
     */
    public function del($name='');
}