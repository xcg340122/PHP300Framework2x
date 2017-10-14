<?php

/**
 * PHP300Framework默认入口
 */

if(substr(PHP_VERSION,0,3) < 5.4) exit('<meta charset="UTF-8">PHP300:请将PHP版本切换至5.3以上运行!');
require('./Framework/frame.php');

$app = new Framework\App(dirname(__FILE__));

$app()->get('Visit')->bind(array('Home','Index','index'));

$app()->run();