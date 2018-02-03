<?php

/** PHP300Framework默认入口 */

if(substr(PHP_VERSION,0,3) < 5.4) exit('<meta charset="UTF-8">PHP300:请将PHP版本切换至5.3以上运行!');

/** 引入框架文件 */
require('../Framework/frame.php');

/** @var object 实例化应用 $app */
$app = new Framework\App();

/** 设定默认访问 */
$app()->get('Visit')->bind(array('Home','Index','index'));

/** 运行应用 */
$app()->run();