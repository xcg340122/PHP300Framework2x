<?php

namespace Framework\Library\Process;

/**
 * 缓存器
 * Class Cache
 * @package Framework\Library\Process
 */
class Cache
{

    /**
     * 操作对象
     * @var object
     */
    private $object;

    /**
     * 数据库驱动映射
     * @var array
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
        $CacheConfig = \Framework\App::$app->get('Config')->get('Cache');
        if (is_array($CacheConfig) && isset($CacheConfig['ip'])) {
            $this->object = \Framework\App::$app->get($this->CacheType[strtolower($CacheConfig['cacheType'])]);
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