<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/21
 * Time: 3:57
 */

namespace Framework\Library\Process;

use \Framework\Library\Interfaces\LogInterface as LogInterfaces;
/**
 * 日志处理器
 * Class Log
 * @package Framework\Library\Process
 */
class Log implements LogInterfaces
{

    /**
     * 日志文件后缀
     * @var string
     */
    public $extend = '.log';

    /**
     * 写出动作
     * @param $fileName
     * @param $Content
     */
    private function Write($fileName,$Content)
    {
        file_put_contents($fileName . $this->extend,$Content,FILE_APPEND);
    }

    /**
     * 记录动作
     * @param $LogPath
     * @param $fileName
     * @param $Log
     */
    public function Record($LogPath,$fileName,$Log)
    {
        if(strpos($LogPath,'/')===false) $LogPath = Running::$framworkPath .'/Project/Runtime/'.$LogPath.'/Log';
        if(!empty($Log)){
            if(!file_exists($LogPath)){
                Structure::createDir($LogPath);
            }
            $Log = "[".date('Y-m-d H:i:s')."]\r\n$Log\r\n--------------------\r\n\r\n ";
            $this->Write($LogPath.'/'.$fileName,$Log);
        }
    }

}