<?php

/**
 * 默认缓存服务器配置
 */
return [

    /**
     * 缓存服务类型
     * memcache
     * redis
     * file
     */
    'cacheType' => 'file',

    /** 缓存服务器IP/域名,类型为file可忽略 */
    'ip' => '',

    /** 缓存服务器端口,类型为file可忽略 */
    'port' => '',
];