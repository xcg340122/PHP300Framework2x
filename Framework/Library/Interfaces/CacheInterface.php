<?php

namespace Framework\Library\Interfaces;

/**
 * 缓存接口
 * Interface CacheInterface
 * @package Framework\Library\Interfaces
 */
interface CacheInterface{

    /**
     * 连接缓存服务器
     * @param $ip
     * @param $port
     * @param array $auth
     * @return mixed
     */
    public function connect($ip,$port,$auth=[]);

    /**
     * 获取一个缓存标识
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * 设定一个缓存标识
     * @param $key
     * @param $value
     * @param bool $iszip
     * @param int $expire
     * @return mixed
     */
    public function set($key,$value,$iszip=false,$expire = 3600);

    /**
     * 删除一个标识
     * @param $key
     * @param int $timeout
     * @return mixed
     */
    public function delete($key,$timeout = 0);

    /**
     * 替换标识
     * @param $key
     * @param $value
     * @param bool $iszip
     * @param int $expire
     * @return mixed
     */
    public function replace($key,$value,$iszip=false,$expire = 3600);
    
    /**
     * 检查值是否存在
     * @param $key
     * @return mixed
     */
    public function exists($key);

    /**
     * 重置所有标识
     * @return mixed
     */
    public function flush();

    /**
     * 减少标识的值
     * @param $key
     * @param int $number
     * @return mixed
     */
    public function decrement($key,$number=1);

    /**
     * 增加标识的值
     * @param $key
     * @param int $number
     * @return mixed
     */
    public function increment($key,$number=1);

    /**
     * 获得版本号
     * @return mixed
     */
    public function getVersion();

    /**
     * 获取服务器统计信息
     * @return mixed
     */
    public function getStats();

    /**
     * 关闭与缓存服务器的连接
     * @return mixed
     */
    public function close();
}
