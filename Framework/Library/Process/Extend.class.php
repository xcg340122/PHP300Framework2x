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
     * 已加载的扩展容器
     * @var string
     */
    public $Extendbox;

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
        if(!empty($PackageName) && file_exists($this->PackagePath . $PackageName)){
            $PackageName = $this->PackagePath . $PackageName;
            $extension = self::get_extension($PackageName);
            if(strtolower($extension)  == 'php'){
                include_once $PackageName;
                return true;
            }
            if(in_array($extension,['zip','tar'])){
                $Packagezip = $this->getPackageName($PackageName);
                $this->releasePackage($PackageName,$this->PackagePath.'PackCache',$Packagezip);
            }
        }
        return false;
    }

    /**
     * 加入新的扩展类
     * @param string $ClassName
     */
    public function addClass($ClassName='')
    {
        if(!empty($ClassName) && file_exists($this->ClassPath . $ClassName)){
            include_once $this->ClassPath . $ClassName;
        }
        return false;
    }

    /**
     * 获取扩展信息
     * @param $file
     * @return mixed
     */
    public static function get_extension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * 获取zip包信息
     */
    private function getPackageInfo($infoPath='')
    {
        if(file_exists($infoPath)){
            return include $infoPath;
        }
    }

    /**
     * 释放压缩文件
     * @param string $zipfile
     * @param string $folder
     */
    private function releasePackage($zipfile='',$folder='',$Packagezip)
    {
        if(class_exists('ZipArchive',false)){
            $zip = new \ZipArchive;
            $res = $zip->open($zipfile);
            if ($res === TRUE) {
                $zip->extractTo($folder);
                $zip->close();

                $autoload = $folder.$Packagezip.'/autoload.php';
                if(file_exists($autoload)){
                    include_once $autoload;
                    $this->Extendbox[$Packagezip] = $this->getPackageInfo($folder.$Packagezip.'/info.php');
                }
            } else {
                $error = [
                    'file' => $zipfile,
                    'message' => "'{$zipfile}' read file failure!"
                ];
                \Framework\App::$app->get('LogicExceptions')->readErrorFile($error);
            }
        }else{
            $error = [
                'file' => $zipfile,
                'message' => "You need to start the PHP ZipArchive extension first!"
            ];
            \Framework\App::$app->get('LogicExceptions')->readErrorFile($error);
        }
    }


    /**
     * 返回包名
     * @param $Package
     * @return bool|mixed
     */
    private function getPackageName($Package)
    {
        $extension = self::get_extension($Package);
        $path = explode('Package/',$Package);
        if(isset($path[1])){
            return str_replace(array('.',$extension),'',$path[1]);
        }
        return false;
    }

    /**
     * 返回已加载的包信息
     * @return string
     */
    public function getPackagebox()
    {
        return $this->Extendbox;
    }
}