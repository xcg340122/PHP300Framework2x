<?php

/**
* 函数助手
*/

function dump($value){
    var_dump($value);
}

/**
 * 数据模型操作
 * @param null $config
 * @return mixed
 */
function Db($config = null){

    $Db = \Framework\App::$app->get('Db')->getlink();

    if(is_array($Db) && count($Db) > 0){
        if(is_null($config)){
            $link = current($Db);
            return $link['obj']->setlink($link['link']);
        }
        if(!empty($Db[$config])){
            return $Db[$config]['obj']->setlink($Db[$config]['link']);
        }
    }
}