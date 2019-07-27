<?php

/** PHP300Framework默认入口 version:2.5.2 */

if (substr(PHP_VERSION, 0, 3) < 5.4) die('<meta charset="UTF-8">PHP300:请将PHP版本切换至5.3以上运行!');

/** 引入框架文件 */
require '../Framework/frame.php';

/** @var object 实例化应用 $app */
$app = new Framework\App();

/** 设定默认访问(应用,控制器,方法) */
$app()->get('Visit')->bind(array('Home', 'Index', 'index'));

/** 是否调试模式(true => 调试,false => 线上) */
$app()->get('Running')->isDev(true);

/** 运行应用 */
$app()->run();