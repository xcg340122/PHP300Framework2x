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
         * 记录错误级别(E_ERROR|E_WARNING|E_PARSE)
         * 全部错误(E_ALL)
         * 致命错误(E_ERROR)
         * 运行警告(E_WARNING)
         * 语法错误(E_PARSE)
         * 其他通知(E_NOTICE)
         * 更多错误级别请参照(http://php.net/manual/zh/errorfunc.constants.php)
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
         * 显示错误的级别(E_ERROR|E_WARNING|E_PARSE)
         * 全部错误(E_ALL)
         * 致命错误(E_ERROR)
         * 运行警告(E_WARNING)
         * 语法错误(E_PARSE)
         * 其他通知(E_NOTICE)
         * 更多错误级别请参照(http://php.net/manual/zh/errorfunc.constants.php)
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
        'left_delimiter' => '_{',

        /**
         * 模板右标记
         */
        'right_delimiter' => '}_',

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