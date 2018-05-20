<?php

/**
 * 自定义dump
 * @param string $vars 打印的变量
 * @param string $label 追加标签
 * @param bool $return 是否直接返回
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
    if ($return) {
        return $content;
    }
    echo $content;
    return null;
}

/**
 * 数据模型操作
 * @param null $config 操作数据的配置名称
 * @return mixed
 */
function Db($config = null)
{
    $Db = \Framework\App::$app->get('Db')->getlink();
    if (is_array($Db) && count($Db) > 0) {
        if (is_null($config)) {
            $link = current($Db);
            return $link['obj']->setlink($link['link']);
        }
        if (!empty($Db[$config])) {
            return $Db[$config]['obj']->setlink($Db[$config]['link']);
        }
    }
}

/**
 * 读取配置信息
 * @param string $configName 配置名称
 * @return bool
 */
function Config($configName)
{
    if (empty($configName)) return false;
    return \Framework\App::$app->get('Config')->get($configName);
}

/**
 * 缓存模型操作
 * @return mixed
 */
function Cache()
{
    $Cache = \Framework\App::$app->get('Cache');
    return $Cache->init()->getObj();
}

/**
 * 渲染视图信息
 * @param string $fileName 模板文件名
 * @param string $dir 其他模板文件
 * @return mixed
 */
function View($fileName = '', $dir = '')
{
    $Object = \Framework\App::$app->get('View')->init();
    if (empty($fileName) && empty($dir)) return $Object;
    if (empty($dir) && !empty($fileName)) {
        $ViewPath = \Framework\Library\Process\Running::$framworkPath . 'Project/View';
        $fileName = $ViewPath . '/' . $fileName . '.html';
    } else {
        $fileName = $dir;
    }
    if (!file_exists($fileName)) {
        $fileName = str_replace('\\', '/', $fileName);
        $error = [
            'file' => __FILE__,
            'message' => "[$fileName] 请检查您的模板是否存在!",
        ];
        \Framework\App::$app->get('LogicExceptions')->readErrorFile($error);
    }
    $Object->set($fileName);
    return $Object;
}

/**
 * 获取GET值
 * @param string $value GET的键名称
 * @return string 为空时返回
 */
function get($value, $null = '')
{
    return \Framework\Library\Process\Auxiliary::Receive('get.' . $value, $null);
}

/**
 * 获取POST值
 * @param string $value POST的键名称
 * @return string 为空时返回
 */
function post($value, $null = '')
{
    return \Framework\Library\Process\Auxiliary::Receive('post.' . $value, $null);
}

/**
 * 动态载入扩展
 * @param string $name 扩展文件名称(可传递数组)
 * @param int $type 是否为包(0=扩展文件,1=扩展包)
 * @return bool
 */
function extend($name, $type = 0)
{
    $Extend = \Framework\App::$app->get('Extend');
    if (!empty($name) && is_array($name)) {
        foreach ($name as $value) {
            if ($type == 1) {
                $Extend->addPackage($value);
            } else {
                $Extend->addClass($value);
            }
        }
        return true;
    }
    if ($type == 1) {
        return $Extend->addPackage($name);
    }
    return $Extend->addClass($name);
}