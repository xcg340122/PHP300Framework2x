<?php

namespace Framework\Library\Process;

/**
 * 返回值处理器
 * Class ReturnHandle
 * @package Framework\Library\Process
 */
class ReturnHandle
{

    /**
     * 处理返回值输出
     * @param string $Obj
     */
    public function Output($Obj = '')
    {
        echo is_object($Obj) || is_array($Obj) ? json_encode($Obj) : $Obj;
    }

}