<?php

namespace App\Home;

/**
 * 默认首页控制器
 * Class Index
 * @package App\Home
 */
class Index
{

    public function index()
    {
        $View = View('Home/index');

        return $View->data([
            'show' => 'PHP300Framework - 想象无极限',
            'describe' => '梦还是要有的万一实现了呢',
            'version' => '2.2.2'
        ])->get();
    }
}