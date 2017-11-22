<?php

namespace Framework\Library\Process\Drive\Cache;

use \Framework\Library\Interfaces\CacheInterface as CacheInterfaces;
/**
 * Class Redis
 * @package Framework\Library\Process\Drive\Cache
 */
class Redis implements CacheInterfaces
{

    /**
     * 建立连接
     * @var null
     */
    protected $link = null;

    /**
     * 实例对象
     * @var
     */
    private $obj;


    /**
     * 构造方法
     * Memcache constructor.
     */
    public function __construct()
    {
        $this->obj = new \Redis();
    }

    /**
     * 连接缓存服务器
     * @param $ip
     * @param $port
     */
    public function connect($ip,$port,$auth=[])
    {
        $this->obj->connect($ip, $port);
        if(!empty($auth['password'])){
            $this->obj->auth($auth['password']);
        }
    }

    /**
     * 获取一个缓存标识
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if(!empty($key)){
            return $this->obj->get($key);
        }
        return false;
    }

    /**
     * 设定一个缓存标识
     * @param $key
     * @param $value
     * @param bool $iszip
     * @param int $expire
     */
    public function set($key,$value,$iszip=false,$expire = 3600)
    {
        return $this->obj->set($key, $value,$expire);
    }

    /**
     * 删除一个标识
     * @param $key
     */
    public function delete($key,$timeout = 0)
    {
        if(!empty($key)){
            return $this->obj->delete($key);
        }
        return false;
    }

    /**
     * 替换标识
     * @param $key
     * @param $value
     * @param bool $iszip
     * @param int $expire
     */
    public function replace($key,$value,$iszip=false,$expire = 3600)
    {
        return $this->obj->getSet($key,$value);
    }

    /**
     * 检测key是否存在
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return $this->obj->exists($key);
    }

    /**
     * 重置所有标识
     */
    public function flush()
    {
        return $this->obj->flushAll();
    }

    /**
     * 减少标识的值
     * @param $key
     * @param int $number
     * @return mixed
     */
    public function decrement($key,$number=1)
    {
        return $this->obj->decrBy($key,$number);
    }

    /**
     * 增加标识的值
     * @param $key
     * @param int $number
     * @return mixed
     */
    public function increment($key,$number=1)
    {
        return $this->obj->incrBy($key,$number);
    }

    /**
     * 获得版本号
     * @return mixed
     */
    public function getVersion()
    {
        $info = $this->getStats();
        return $info['redis_version'];
    }

    /**
     * 获取服务器统计信息
     * @return mixed
     */
    public function getStats()
    {
        return $this->obj->info();
    }

    /**
     * 关闭与缓存服务器的连接
     */
    public function close()
    {
        $this->obj->close();
    }

}
