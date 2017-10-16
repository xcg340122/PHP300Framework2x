<?php

namespace Framework\Library\Process;

use \Framework\Library\Interfaces\VisitInterface as VisitInterfaces;
/**
 * 访问处理器
 * Class Visit
 * @package Framework\library\_class
 */
class Visit implements VisitInterfaces
{

    /**
     * 访问参数
     * @var array
     */
    static public $param;

    /**
     * 初始化构造
     * Visit constructor.
     */
    public function __construct()
    {
        $VisitConfig = \Framework\App::$app->get('Config')->get('frame');

        if(isset($VisitConfig['Visit'])) self::$param = $VisitConfig['Visit'];
    }

    /**
     * 绑定数默认实例
     * @param $param
     */
    public function bind($param)
    {
        if(is_array($param)){
            $count = count($param);
            switch($count){
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
            self::$param['Function'] = str_replace(self::$param['extend'],'',self::$param['Function']);
        }
    }

    /**
     * 合并访问对象
     * @return string
     */
    static public function mergeParam()
    {
        \Framework\App::$app->get('Router');

        return self::$param['namespace'] . '\\' . ucwords(self::$param['Project']) . '\\' . ucwords
        (self::$param['Controller']);
    }

    /**
     * 获取对象方法
     * @return mixed
     */
    static public function getfunction()
    {
        if(empty(self::$param['Function'])){
            $error = [
                'file' => Structure::$endfile,
                'message' => 'Passed the empty method!'
            ];
            \Framework\App::$app->get('LogicExceptions')->readErrorFile($error);
        }
        return self::$param['Function'];
    }
}