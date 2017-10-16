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
     * 调试模式
     * @var bool
     */
    public $Debug = true;

    /**
     * 监视运行参数
     * @var array
     */
    public $param = [];

    /**
     * 路径信息
     * @var string
     */
    static public $framworkPath;


    public function __construct()
    {
        self::$framworkPath = str_replace('Framework/','',\Framework\App::$app->corePath);
    }

    /**
     * 开始记录信息
     */
    public function startRecord()
    {
        $this->param['startTime'] = microtime(true);
        $this->param['startRam'] = (function_exists('memory_get_usage'))?(memory_get_usage()):(0);
    }

    /**
     * 停止记录信息
     */
    public function endRecord()
    {
        $this->param['endTime'] = microtime(true);
        $this->param['endRam'] = (function_exists('memory_get_usage'))?(memory_get_usage()):(0);
        $this->param['consumeRam'] = $this->consumeRam(($this->param['endRam'] - $this->param['startRam']));
    }

    /**
     * 计算消耗的内存
     */
    private function consumeRam($size)
    {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
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