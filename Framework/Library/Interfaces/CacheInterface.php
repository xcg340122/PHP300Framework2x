<?php

namespace Framework\Library\Interfaces;

/**
 * 缓存接口
 * Interface CacheInterface
 * @package Framework\Library\Interfaces
 */
interface CacheInterface
{

    /**
     * 连接缓存服务器
     * @param string $ip 服务器IP
     * @param string|int $port 服务器端口
     * @param array $auth 授权信息
     * @return mixed
     */
    public function connect($ip, $port, $auth = []);

    /**
     * 获取一个缓存标识
     * @param string $key 获取的键名称
     * @return mixed|string|array
     */
    public function get($key);

    /**
     * 设定一个缓存标识
     * @param string $key 键名称
     * @param string|array $value 设置的值内容
     * @param bool $isZip 是否启用压缩
     * @param int $expire 缓存的周期
     * @return mixed|bool
     */
    public function set($key, $value, $isZip = false, $expire = 3600);

    /**
     * 删除一个标识
     * @param string $key 删除的键名称
     * @param int $timeout 延迟删除(默认为0立即删除)
     * @return mixed|bool
     */
    public function delete($key, $timeout = 0);

    /**
     * 替换标识
     * @param string $key 替换的键名称
     * @param string|array $value 替换的值内容
     * @param bool $isZip 是否启用压缩
     * @param int $expire 缓存的周期
     * @return mixed|bool
     */
    public function replace($key, $value, $isZip = false, $expire = 3600);

    /**
     * 检查值是否存在
     * @param string $key 检查的键名称
     * @return mixed|bool
     */
    public function exists($key);

    /**
     * 重置所有标识
     * @return mixed
     */
    public function flush();

    /**
     * 减少标识的值
     * @param string $key 减少的键名称
     * @param int $number
     * @return mixed
     */
    public function decrement($key, $number = 1);

    /**
     * 增加标识的值
     * @param string $key 增加的键名称
     * @param int $number
     * @return mixed
     */
    public function increment($key, $number = 1);

    /**
     * 获得版本号
     * @return mixed|string
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
