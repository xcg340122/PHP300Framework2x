<?php

/**
 * 数据库配置
 */
return [

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

];