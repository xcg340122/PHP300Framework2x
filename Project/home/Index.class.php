<?php

namespace App\home;

use \Framework\Library\Process\Tool;

/**
 * 默认首页控制器
 * Class Index
 * @package App\Home
 */
class Index
{
    /**
     * 默认首页
     * @return mixed
     */
    public function index()
    {
        //演示cli模式下处理
        if (Tool::isCli()) {
            return 'hello this is cli mode,framework version:'._V.'!';
        }

        return View('home/index')->data(['show' => 'PHP300Framework - 想象无极限', 'describe' => '每个人的生命都是一只小船，梦想是小船的风帆。'])->get();
    }
}