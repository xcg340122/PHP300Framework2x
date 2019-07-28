<?php

namespace Framework\Library\Interfaces;

/**
 * 日志接口
 * Interface LogInterface
 * @package Framework\Library\Interfaces
 */
interface  LogInterface
{
    /**
     * 写出日志
     * @param string $LogPath 日志文件路径
     * @param string $fileName 日志文件名
     * @param string $Log 日志内容
     * @return mixed
     */
    public function Record($LogPath, $fileName, $Log);
}
