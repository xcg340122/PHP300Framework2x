<?php

namespace Framework\Library\Interfaces;

/**
 * 访问处理接口
 * Interface VisitInterface
 * @package Framework\Library\Interfaces
 */

interface  VisitInterface
{
    /**
     * 绑定数默认实例
     * @param array $param 配置数组
     * @return mixed
     */
    public function bind($param);
}
