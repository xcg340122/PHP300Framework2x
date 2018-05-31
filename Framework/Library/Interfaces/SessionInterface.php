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
     * @param string $name key名称
     * @return mixed
     */
    public function get($name='');

    /**
     * 设置session
     * @param string $name key名称
     * @param string $value value值
     * @return mixed
     */
    public function set($name='php300',$value='');

    /**
     * 删除session
     * @param string $name key名称
     * @return mixed
     */
    public function del($name='');
}