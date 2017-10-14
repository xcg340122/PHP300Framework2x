<?php

namespace Framework\Library\Interfaces;

/**
 * 日志接口
 */

interface  LogInterface
{
    /**
     * 写出日志
     * @param $LogPath
     * @param $fileName
     * @param $Log
     * @return mixed
     */
    public function Record($LogPath,$fileName,$Log);
}
