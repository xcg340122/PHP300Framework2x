<?php

namespace Framework\Library\Process;

use Framework\App;

/**
 * 系统扩展器
 * Class Extend
 * @package Framework\Library\Process
 */
class Extend
{
    /**
     * @var string 包路径
     */
    public $PackagePath;

    /**
     * @var string 类路径
     */
    public $ClassPath;

    /**
     * @var string 已加载的扩展容器
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
        if (file_exists(Running::$framworkPath . 'vendor/autoload.php')){
            require_once Running::$framworkPath . 'vendor/autoload.php';
        }
    }

    /**
     * 加入新的扩展包
     * @param string $PackageName
     * @return bool
     */
    public function addPackage($PackageName = '')
    {
        if (!empty($PackageName) && file_exists($this->PackagePath . $PackageName)) {
            $PackageName = $this->PackagePath . $PackageName;
            $extension = self::get_extension($PackageName);
            if (strtolower($extension) == 'php') {
                include_once $PackageName;
                return true;
            }
            if (in_array($extension, ['zip', 'tar', 'rar'])) {
                $Packagezip = $this->getPackageName($PackageName);
                $this->releasePackage($PackageName, $this->PackagePath . 'Cache', $Packagezip);
            }
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
     * 返回包名
     * @param $Package
     * @return bool|mixed
     */
    private function getPackageName($Package)
    {
        $extension = self::get_extension($Package);
        $path = explode('Package/', $Package);
        if (isset($path[1])) {
            return str_replace(array('.', $extension), '', $path[1]);
        }
        return false;
    }

    /**
     * 释放压缩文件
     * @param string $zipfile
     * @param string $folder
     * @param $Packagezip
     * @return bool
     */
    private function releasePackage($zipfile = '', $folder = '', $Packagezip)
    {
        if ($this->iszipload($folder, $Packagezip)) {
            return true;
        }
        if (class_exists('ZipArchive', false)) {
            $zip = new \ZipArchive;
            $res = $zip->open($zipfile);
            if ($res === TRUE) {
                $zip->extractTo($folder);
                $zip->close();
                $this->iszipload($folder, $Packagezip);
            } else {
                App::$app->get('LogicExceptions')->readErrorFile([
                    'file' => $zipfile,
                    'message' => "'{$zipfile}' 读取文件失败!"
                ]);
            }
        } else {
            App::$app->get('LogicExceptions')->readErrorFile([
                'file' => $zipfile,
                'message' => "你需要先启动 PHP-ZipArchive 扩展!"
            ]);
        }
        return true;
    }

    /**
     * 加载扩展文件
     * @param $folder
     * @param $Packagezip
     * @return bool
     */
    private function iszipload($folder, $Packagezip)
    {
        $autoload = $folder . '/' . $Packagezip . '/autoload.php';
        if (file_exists($autoload)) {
            include_once $autoload;
            $this->Extendbox[$Packagezip] = $this->getPackageInfo($folder . '/' . $Packagezip . '/info.php');
            file_put_contents($folder . '/' . $Packagezip . '/marked.txt', 'This is an automatically unpacked package. Please do not manually modify or delete it!  - PHP300Framework2x');
            return true;
        }
        return false;
    }

    /**
     * 获取zip包信息
     * @param string $infoPath
     * @return bool|mixed
     */
    private function getPackageInfo($infoPath = '')
    {
        if (file_exists($infoPath)) {
            return include $infoPath;
        }
        return false;
    }

    /**
     * 加入新的扩展类
     * @param string $ClassName
     * @return bool
     */
    public function addClass($ClassName = '')
    {
        if (!empty($ClassName) && file_exists($this->ClassPath . $ClassName)) {
            include_once $this->ClassPath . $ClassName;
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