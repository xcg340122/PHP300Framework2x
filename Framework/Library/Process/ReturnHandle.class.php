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
     * @param $Obj
     */
    public function Output($Obj)
    {
       if(!empty($Obj)){
           $ObjType = '';
           if(is_object($Obj) && isset($Obj->_methon)){
               $_methon = $Obj->_methon;
               $ObjType = 1;
               unset($Obj->_methon);
           }
           elseif(is_array($Obj) && isset($Obj['_methon'])){
               $_methon = $Obj['_methon'];
               $ObjType = 0;
               unset($Obj['_methon']);
           }
           if(isset($_methon)){
               switch($_methon){
                   case 'json':
                       echo json_encode($Obj);
                       break;
                   case 'join':
                       if($ObjType === 1){
                           echo implode(',',$Obj);
                       }else{
                          echo $this->joinObj(',',$Obj);
                       }
                        break;
                   case 'dump':
                       var_dump($Obj);
                       break;
                   case 'print':
                       print_r($Obj);
                       break;
               }
           }
       }
    }

    /**
     * 合并对象
     * @param string $glue
     * @param $Obj
     * @return string
     */
    private function joinObj($glue = ',',$Obj)
    {
        $str = '';
        foreach($Obj as $value){
            $str .= $value.$glue;
        }
        $str = rtrim($str,'.'.$glue);
        return $str;
    }

}