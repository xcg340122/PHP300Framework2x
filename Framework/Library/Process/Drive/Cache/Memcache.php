<?php

namespace Framework\Library\Process\Drive\Cache;

use \Framework\Library\Interfaces\CacheInterface as CacheInterfaces;

/**
 * Class Memcache
 * @package Framework\Library\Process\Drive\Cache
 */
class Memcache implements CacheInterfaces
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
        $this->obj = new \Memcache();
    }

    /**
     * 连接缓存服务器
     * @param string $ip 服务器IP
     * @param int|string $port 服务器端口
     * @param array $auth 授权信息
     * @return mixed|void
     */
    public function connect($ip, $port, $auth = [])
    {
        $this->obj->connect($ip, $port);
    }

    /**
     * 获取一个缓存标识
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if (!empty($key)) {
            return $this->obj->get($key);
        }
        return false;
    }

    /**
     * 设定一个缓存标识
     * @param string $key 缓存key
     * @param string|array $value 缓存value
     * @param bool $iszip 是否压缩
     * @param int $expire 缓存周期
     * @return bool|mixed
     */
    public function set($key, $value, $iszip = false, $expire = 3600)
    {
        return $this->obj->add($key, $value, $iszip, $expire);
    }

    /**
     * 删除一个标识
     * @param string $key 缓存key
     * @param int $timeout
     * @return bool|mixed
     */
    public function delete($key, $timeout = 0)
    {
        if (!empty($key)) {
            return $this->obj->delete($key, $timeout);
        }
        return false;
    }

    /**
     * 替换标识
     * @param $key
     * @param $value
     * @param bool $iszip
     * @param int $expire
     * @return bool|mixed
     */
    public function replace($key, $value, $iszip = false, $expire = 3600)
    {
        return $this->obj->replace($key, $value, $iszip, $expire);
    }

    /**
     * 检测key是否存在
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        $data = $this->get($key);
        return $data !== false;
    }

    /**
     * 重置所有标识
     */
    public function flush()
    {
        return $this->obj->flush();
    }

    /**
     * 减少标识的值
     * @param $key
     * @param int $number
     * @return mixed
     */
    public function decrement($key, $number = 1)
    {
        return $this->obj->decrement($key, $number);
    }

    /**
     * 增加标识的值
     * @param $key
     * @param int $number
     * @return mixed
     */
    public function increment($key, $number = 1)
    {
        return $this->obj->increment($key, $number);
    }

    /**
     * 获得版本号
     * @return mixed
     */
    public function getVersion()
    {
        return $this->obj->getVersion();
    }

    /**
     * 获取服务器统计信息
     * @return mixed
     */
    public function getStats()
    {
        return $this->obj->getStats();
    }

    /**
     * 关闭与缓存服务器的连接
     */
    public function close()
    {
        $this->obj->close();
    }
}
