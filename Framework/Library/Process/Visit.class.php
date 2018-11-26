<?php

namespace Framework\Library\Process;

use Framework\Library\Interfaces\VisitInterface as VisitInterfaces;

/**
 * 访问处理器
 * Class Visit
 * @package Framework\library\_class
 */
class Visit implements VisitInterfaces
{

    /**
     * 访问配置参数
     * @var array
     */
    static public $param;

    /**
     * 默认请求参数
     * @var array
     */
    static public $request;

    /**
     * 初始化构造
     * Visit constructor.
     */
    public function __construct()
    {
        $VisitConfig = \Framework\App::$app->get('Config')->get('frame');
        if (isset($VisitConfig['Visit'])){ self::$param = $VisitConfig['Visit']; }
        if (isset($VisitConfig['Parameter'])){ self::$request = $VisitConfig['Parameter']; }
    }

    /**
     * 合并访问对象
     * @return string
     */
    static public function mergeParam()
    {
        \Framework\App::$app->get('Router');
        return self::$param['namespace'] . '\\' . ucwords(self::$param['Project']) . '\\' . ucwords(self::$param['Controller']);
    }

    /**
     * 获取对象方法
     * @return mixed
     */
    static public function getfunction()
    {
        if (empty(self::$param['Function'])) {
            \Framework\App::$app->get('LogicExceptions')->readErrorFile([
                'file' => Structure::$endfile,
                'message' => '无法执行空方法!'
            ]);
        }
        return self::$param['Function'];
    }

    /**
     * 设定CLI模式参数
     * @return bool
     */
    static function setCliParam()
    {
        if(isset($_SERVER['argv'])){
            $param = $_SERVER['argv'];
            if (count($param) > 3) {
                foreach ($param as $key => $value) {
                    if ($key > 0) {
                        $param[($key - 1)] = $value;
                    }
                }
                unset($param[3]);
                \Framework\App::$app->get('Visit')->bind($param);
            } else {
                if (count($param) === 1) {
                    return true;
                }
                die('PHP300::Inadequacy of parameters!');
            }
        }else{
            die('PHP300:server.argv not found');
        }
        return false;
    }

    /**
     * 绑定数默认实例
     * @param $param
     */
    public function bind($param)
    {
        if (is_array($param)) {
            $count = count($param);
            switch ($count) {
                case 1:
                    self::$param['Controller'] = $param[0];
                    break;
                case 2:
                    self::$param['Controller'] = $param[0];
                    self::$param['Function'] = $param[1];
                    break;
                case 3:
                    self::$param['Project'] = $param[0];
                    self::$param['Controller'] = $param[1];
                    self::$param['Function'] = $param[2];
                    break;
            }
            if (isset(self::$param['Function'])) {
                self::$param['Function'] = str_replace(self::$param['extend'], '', self::$param['Function']);
            }
        }
    }
}