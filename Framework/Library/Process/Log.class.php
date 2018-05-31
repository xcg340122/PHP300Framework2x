<?php

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
     * 写出动作(划分日期和大小)
     * @param $fileName
     * @param $Content
     */
    private function Write($fileName, $Content, $count = 0)
    {
        $fileLine = $fileName . '_' . date('Y-m-d', time()) . '(' . $count . ')';
        $fileNames = $fileLine . $this->extend;
        if (file_exists($fileNames)) {
            $size = number_format(filesize($fileNames) / 1024 / 1024, 3);
            if ($size > 5) {
                $number = intval(substr($fileLine, -2, 1)) + 1;
                $this->Write($fileName, $Content, $number);
            } else {
                file_put_contents($fileNames, $Content, FILE_APPEND);
            }
        } else {
            if($count == 0) $fileNames = str_replace('(0)','',$fileNames);
            file_put_contents($fileNames, $Content, FILE_APPEND);
        }
    }

    /**
     * 记录动作
     * @param $LogPath
     * @param $fileName
     * @param $Log
     */
    public function Record($LogPath, $fileName, $Log)
    {
        if (strpos($LogPath, '/') === false) $LogPath = Running::$framworkPath . '/Project/Runtime/' . $LogPath . '/Log';
        if (!empty($Log)) {
            if (!file_exists($LogPath)) {
                Structure::createDir($LogPath);
            }
            $Log = "[" . date('Y-m-d H:i:s') . "]\r\n$Log\r\n------------------\r\n\r\n ";
            $this->Write($LogPath . '/' . $fileName, $Log);
        }
    }

}