<?php

namespace Framework\Library\Process;

use Framework\App;

/**
 * 系统结构加载器
 * Class Structure
 * @package Framework\library\_class
 */
class Structure
{

    /**
     * @var array 应用列表
     */
    static public $ProjectList = [];

    /**
     * @var string 最后读入的文件
     */
    static public $endfile = null;

    /**
     * @var string 后缀信息
     */
    private $extend;

    /**
     * 初始化构造
     * Structure constructor.
     */
    public function __construct()
    {
        spl_autoload_register([&$this, '__autoload']);
        $this->getProjectList();
        $this->RunTimeInit();
    }

    /**
     * 获取应用列表
     */
    public function getProjectList()
    {
        $getPath = Running::$framworkPath . '/Project/';
        $Project = $this->getDir($getPath);
        if (is_array($Project) && count($Project) > 0) {
            $array = ['runtime','config'];
            foreach ($Project as $value) {
                if (is_dir($getPath . $value) && !in_array($value, $array)) {
                    self::$ProjectList[] = $value;
                }
            }
        }
    }

    /**
     * 获取目录
     * @param $path
     * @return array|bool
     */
    static public function getDir($path)
    {
        if (is_dir($path)) {
            return array_merge(array_diff(scandir($path), array('.', '..')));
        }
        return false;
    }

    /**
     * 初始化结构信息
     */
    private function RunTimeInit()
    {
        $this->checkPower(Running::$framworkPath . 'tmp');
        $getPath = Running::$framworkPath . 'Project/';
        $CreateDefaultDir = ['log'];
        if (is_array(self::$ProjectList) && count(self::$ProjectList) > 0) {
            foreach (self::$ProjectList as $value) {
                foreach ($CreateDefaultDir as $default) {
                    self::createDir($getPath . 'runtime/' . $value . '/' . $default);
                }
            }
        }
        require App::$app->corePath . 'Library/Common/helper.php';
    }

    /**
     * 遍历创建目录
     * @param $path
     */
    static public function createDir($path)
    {
        if (!is_dir($path)) {
            self::createDir(dirname($path));
            if (mkdir($path, 0777) === false) {
                die('PHP300:No written permission -> (PATH:' . $path . ')');
            }
        }
    }

    /**
     * 自动加载实现
     * @param string $class 加载对象
     */
    public function __autoload($class)
    {
        $class = str_replace('\\', '/', $class);
        $istrpos = strpos($class, '/Interfaces');
        $framworkPath = $istrpos ? App::$app->corePath : Running::$framworkPath;
        $this->extend = $istrpos ? '.php' : '.class.php';
        $fileObj = strpos($class, 'App/') !== false ? $framworkPath . str_replace('App/', 'Project/', $class) . $this->extend : $framworkPath . str_replace('Framework/', '', $class) . $this->extend;
        if (file_exists($fileObj)) {
            self::$endfile = $fileObj;
            include_once($fileObj);
        } else {
            Running::$iserror = true;
            if (strpos($fileObj, 'Project/')) {
                $this->getStaticTpl();
                $error = [
                    'message' => '您请求的方法不存在'
                ];
            } else {
                $error = [
                    'file' => $fileObj,
                    'message' => '您引用了一个不存在的文件!'
                ];
            }
            App::$app->get('LogicExceptions')->readErrorFile($error);
        }
    }

    /**
     * 寻找静态模板
     */
    public function getStaticTpl()
    {
        $file = Running::$framworkPath.'Project/view'.Router::$requestUrl;
        if (file_exists($file) && is_file($file)) {
            die(View('', $file)->get());
        }
    }

    /**
     * 检查权限
     * @param $path
     */
    private function checkPower($path)
    {
        if (Tool::isWin() === false && file_exists($path) === false) {
            if (file_put_contents($path, '') === false) {
                die('PHP300Framework Adequate privileges are required to run!(' . $path . ')');
            } else {
                unlink($path);
            }
        }
    }
}
