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
     * @param $fileName
     * @return mixed
     */
    public function set($fileName);

    /**
     * 获取渲染内容
     * @return mixed
     */
    public function get();

    /**
     * 获取视图原型
     * @return mixed
     */
    public function getView();
}