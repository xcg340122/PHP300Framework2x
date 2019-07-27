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
        if(is_object($Obj) || is_array($Obj)){
            header('content-type:application/json;charset=utf-8');
            echo json_encode($Obj);
        }else{
            echo $Obj;
        }
    }

}