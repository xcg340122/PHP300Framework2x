<?php

namespace Framework\Library\Interfaces;

/**
 * 视图接口
 * Interface ViewInterface
 * @package Framework\Library\Interfaces
 */
interface ViewInterface
{
    /**
     * 初始化视图
     * @return mixed
     */
    public function init();

    /**
     * 设定操作文件
     * @param string $fileName 文件名称
     * @return mixed
     */
    public function set($fileName);

    /**
     * 获取渲染内容
     * @return mixed|string
     */
    public function get();

    /**
     * 获取视图原型
     * @return mixed|object
     */
    public function getView();

    /**
     * 赋值变量
     * @param null|array $data 设置的变量数组
     * @return mixed|self
     */
    public function data($data = null);
}