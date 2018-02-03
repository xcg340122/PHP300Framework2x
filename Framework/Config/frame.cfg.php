<?php

/**
 * 系统基础配置
 */
return [

    /**
     * 日志基础配置
     */
    'log' => [

        /**
         * 是否开启日志记录
         */
        'error_switch' => true,

        /**
         * 记录错误级别
         */
        'error_level' => 'E_ALL'
    ],

    /**
     * 异常处理配置
     */
    'Exception' => [

        /**
         * 是否开启异常显示
         */
        'display_switch' => true,

        /**
         * 显示错误的级别
         */
        'display_level' => 'E_ALL'
    ],

    /**
     * 访问信息配置
     */
    'Visit' => [

        /**
         * 默认加载实例的命名空间前缀(不建议修改)
         */
        'namespace' => 'App',

        /**
         * 默认实例名称
         */
        'Project' => 'Home',

        /**
         * 默认控制器名称
         */
        'Controller' => 'Index',

        /**
         * 默认方法名称
         */
        'Function' => 'index',

        /**
         * 静态扩展名
         */
        'extend' => '.html'
    ],

    'View' => [

        /**
         * 模板左标记
         */
        'left_delimiter' => '{',

        /**
         * 模板右标记
         */
        'right_delimiter' => '}',

        /**
         * 是否启用缓存
         */
        'is_cache' => true,

        /**
         * 缓存周期
         */
        'cache_lifetime' => '0',
    ]

];