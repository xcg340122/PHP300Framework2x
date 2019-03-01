<?php

/**
 * 应用配置
 */
return [

    /**
     * 数据库配置
     */
    'db' => [
        /**
         * 默认连接
         */
        'default' => [

            /** 目标IP/域名 */
            'host' => '127.0.0.1',

            /** 目标端口 */
            'port' => 3306,

            /** 数据库用户名 */
            'username' => 'root',

            /** 数据库密码 */
            'password' => 'root',

            /** 数据库名称 */
            'database' => 'test',

            /** 数据表前缀 */
            'tabprefix' => '',

            /** 数据库编码 */
            'char' => 'utf8',

            /**
             * 数据库驱动类型
             * mysqli
             * pdo
             */
            'dbType' => 'mysqli',

            /** 是否连接数据库 */
            'connect' => false
        ]
    ],

    /**
     * 缓存配置
     */
    'cache' => [
        /**
         * 缓存服务类型
         * memcache(默认端口：11211)
         * redis(默认端口：6379)
         * file
         */
        'cacheType' => 'file',

        /** 缓存服务器IP/域名（类型为file可忽略） */
        'ip' => '',

        /** 缓存服务器端口（类型为file可忽略） */
        'port' => '',
    ],

    /**
     * 路由配置
     */
    'router' => [

        /**
         * 演示路由
         * 这里的实例名称和控制器名称全部小写
         */
        '/home/index/test' => function () {

            //这里是自定义操作
            return '欢迎访问用户test数据';
        },
    ],

    /**
     * 安全配置
     */
    'safe' => [

        /**
         * 域名白名单，可用于异步请求跨域响应(默认只允许当前域名=)
         */
        'ajax_domain' => [
            'http://www.baidu.com', /* 一行一个域名 */
        ],
    ]

];