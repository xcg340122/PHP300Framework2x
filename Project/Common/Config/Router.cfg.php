<?php

/**
 * 自定义路由配置
 */
return [

    /**
     * 演示路由
     * 这里的实例名称和控制器名称要严格按照大小写
     */
    '/Home/Index/test' => function () {

        //这里是自定义操作
        return '欢迎访问用户test数据';
    },

];