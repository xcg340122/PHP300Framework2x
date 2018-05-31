<?php

/**
 * 默认缓存服务器配置
 */
return [

    /** 缓存服务器IP/域名 */
    'ip' => '127.0.0.1',

    /** 缓存服务器端口 */
    'port' => 11211,

    /**
     * 缓存服务类型
     * memcache
     * redis
     */
    'cacheType' => 'memcache'
];