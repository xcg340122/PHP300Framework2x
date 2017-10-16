<?php

/**
 * 自定义dump
 * @param $vars
 * @param string $label
 * @param bool $return
 * @return null|string
 */
function dump($vars, $label = '', $return = false)
{
    if (ini_get('html_errors')) {
        $content = "<pre>\n";
        if (!empty($label)) {
            $content .= "<strong>{$label} :</strong>\n";
        }
        $content .= htmlspecialchars(print_r($vars, true));
        $content .= "\n</pre>\n";
    } else {
        $content = $label . " :\n" . print_r($vars, true);
    }
    if ($return) { return $content; }
    echo $content;
    return null;
}

/**
 * 数据模型操作
 * @param null $config
 * @return mixed
 */
function Db($config = null)
{
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

/**
 * 缓存模型操作
 */
function Cache()
{
    $Cache = \Framework\App::$app->get('Cache');
    return $Cache->init()->getObj();
}

/**
 * 获取GET值
 * @param $value
 * @return string
 */
function get($value,$null='')
{
    return \Framework\Library\Process\Auxiliary::Receive('get.'.$value,$null);
}

/**
 * 获取POST值
 * @param $value
 * @return string
 */
function post($value,$null='')
{
    return \Framework\Library\Process\Auxiliary::Receive('post.'.$value,$null);
}

/**
 * 动态载入扩展
 * @param $name
 * @param int $type
 * @return bool
 */
function extend($name,$type=0)
{
    $Extend = \Framework\App::$app->get('Extend');
    if($type==1){
        $Extend->addPackage($name);
        return true;
    }
    $Extend->addClass($name);
}