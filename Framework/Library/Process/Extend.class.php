<?php

namespace Framework\Library\Process;

/**
 * 系统扩展器
 * Class Extend
 * @package Framework\Library\Process
 */
class Extend
{

    /**
     * 包路径
     * @var string
     */
    public $PackagePath;

    /**
     * 类路径
     * @var string
     */
    public $ClassPath;

    /**
     * 初始化相关路径
     * Extend constructor.
     */
    public function __construct()
    {
        $this->PackagePath = Running::$framworkPath . 'Extend/Package/';
        $this->ClassPath = Running::$framworkPath . 'Extend/Class/';
        if(file_exists(Running::$framworkPath . 'vendor/autoload.php')) require_once Running::$framworkPath . 'vendor/autoload.php';
    }

    /**
     * 加入新的扩展包
     * @param string $PackageName
     */
    public function addPackage($PackageName='')
    {
        if(!empty($PackageName)){

        }
    }

    /**
     * 加入新的扩展类
     * @param string $ClassName
     */
    public function addClass($ClassName='')
    {
        if($ClassName){

        }
    }

}