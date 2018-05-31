<?php

/**
 * 自定义路由配置
 */
return [

    /**
     * 演示路由
     */
    '/Home/Index/test' => function () {

        //这里是自定义操作
        return '欢迎访问用户test数据';
    },

];