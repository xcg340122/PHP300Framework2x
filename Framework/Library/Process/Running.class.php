<?php

namespace Framework\Library\Process;

/**
 * 运行监视器
 * Class Running
 * @package Framework\Library\Process
 */
class Running
{

    /**
     * 是否系统异常
     * @var bool
     */
    static public $iserror = false;

    /**
     * 运行模式
     * @var string
     */
    static public $runMode = 'cgi';

    /**
     * 开发模式(true=>调试模式,false=>线上模式)
     * @var bool
     */
    static public $Debug;
    /**
     * 路径信息
     * @var string
     */
    static public $framworkPath;
    /**
     * 监视运行参数
     * @var array
     */
    public $param = [];

    /**
     * 运行构造
     * Running constructor.
     */
    public function __construct()
    {
        self::$framworkPath = str_replace('Framework/', '', \Framework\App::$app->corePath);
    }

    /**
     * 预定义常量信息
     */
    static public function setconstant()
    {
        $define = [
            'RES' => Auxiliary::getPublic(),
            '_P' => Visit::$param['Project'],
            '_C' => Visit::$param['Controller'],
            '_F' => Visit::$param['Function'],
            '_T' => time()
        ];
        foreach ($define as $key => $value) {
            define($key, $value);
        }
    }

    /**
     * 设定开发模式
     * @param bool $status
     */
    public function isDev($status = true)
    {
        self::$Debug = $status;
    }

    /**
     * 开始记录信息
     */
    public function startRecord()
    {
        $this->param['startTime'] = microtime(true);
        $this->param['startRam'] = (function_exists('memory_get_usage')) ? (memory_get_usage()) : (0);
    }

    /**
     * 停止记录信息
     */
    public function endRecord()
    {
        $this->param['endTime'] = microtime(true);
        $this->param['endRam'] = (function_exists('memory_get_usage')) ? (memory_get_usage()) : (0);
        $this->param['consumeRam'] = $this->consumeRam(($this->param['endRam'] - $this->param['startRam']));
    }

    /**
     * 计算消耗的内存
     */
    private function consumeRam($size)
    {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * 程序汇总处理
     * @return array
     */
    public function TotalInfo()
    {
        return $this->param;
    }
}