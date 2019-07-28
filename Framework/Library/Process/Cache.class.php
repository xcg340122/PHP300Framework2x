<?php
namespace Framework\Library\Process;

use Framework\App;

/**
 * 缓存器
 * Class Cache
 * @package Framework\Library\Process
 */
class Cache
{

    /**
     * @var object 操作对象
     */
    private $object;

    /**
     * @var array 数据库驱动映射
     */
    private $CacheType = [

        'memcache' => 'Drive\Cache\Memcache',

        'redis' => 'Drive\Cache\Redis',

        'file' => 'Drive\Cache\File'
    ];

    /**
     * 初始化缓存配置
     */
    public function init()
    {
        $CacheConfig = Config::$AppConfig['cache'];
        if (is_array($CacheConfig) && isset($CacheConfig['ip'])) {
            $this->object = App::$app->get($this->CacheType[strtolower($CacheConfig['cacheType'])]);
            if(strtolower($CacheConfig['cacheType']) != 'file'){
                $this->object->connect($CacheConfig['ip'], $CacheConfig['port']);
            }
        }
        return $this;
    }

    /**
     * 获取操作对象实例
     * @return object
     */
    public function getObj()
    {
        return $this->object;
    }
}