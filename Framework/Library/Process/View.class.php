<?php

namespace Framework\Library\Process;

use \Framework\Library\Interfaces\ViewInterface as ViewInterfaces;

/**
 * 视图处理器
 * Class View
 * @package Framework\Library\Process
 */
class View implements ViewInterfaces
{
    /**
     * 视图编译目录
     * @var string
     */
    private $ViewCompile = '';

    /**
     * 视图存放目录
     * @var string
     */
    private $ViewPath = '';

    /**
     * 视图缓存目录
     * @var string
     */
    private $ViewCache = '';

    /**
     * 视图对象
     * @var object
     */
    private $View;

    /**
     * 模板文件
     * @var string
     */
    private $file = '';

    /**
     * 变量集合
     * @var array
     */
    private $variable = [];

    /**
     * 初始化视图信息
     * @return mixed|\Smarty
     */
    public function init()
    {
        $dir = Running::$iserror ? 'view' : Visit::$param['Project'];
        $this->ViewCompile = Running::$framworkPath . 'Project/runtime/' . $dir . '/view';
        $this->ViewPath = Running::$framworkPath . 'Project/view';
        $this->ViewCache = $this->ViewCompile . '/cache';
        $functions = spl_autoload_functions();
        foreach ($functions as $function) {
            spl_autoload_unregister($function);
        }
        \Framework\App::$app->get('Extend')->addPackage('smarty/Smarty.class.php');
        $this->dirProcessing([
            $this->ViewCompile,
            $this->ViewPath,
            $this->ViewCache
        ]);
        $this->View = $this->ViewConfig($this->ReturnView());
        return $this;
    }

    /**
     * 设定操作的文件
     * @param $fileName
     */
    public function set($fileName)
    {
        $this->file = $fileName;
    }

    /**
     * 获取渲染数据
     * @return bool
     */
    public function get()
    {
        if ($this->file == '') {
            return false;
        }
        foreach ($this->variable as $key => $value) {
            $this->View->assign($key, $value);
        }
        $html = $this->View->fetch($this->file);
        if (strpos($this->file, 'error.tpl') !== false && $html == '') {
            die(Tool::ShowText('程序异常,请查看日志!'));
        }
        return $html;
    }

    /**
     * 模板变量集合
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (!in_array($name, $this->variable)) {
            $this->variable[$name] = $value;
        }
    }

    /**
     * 设定模板变量
     * @param null $data
     * @return $this
     */
    public function data($data = null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->variable[$key] = $value;
            }
        }
        return $this;
    }

    /**
     * 处理文件夹
     * @param $dir
     */
    private function dirProcessing($dir)
    {
        if (is_array($dir)) {
            foreach ($dir as $value) {
                Structure::createDir($value);
            }
        }
    }

    /**
     * 实例化视图
     * @return \Smarty
     */
    private function ReturnView()
    {
        $view = new \Smarty();
        $view->setTemplateDir($this->ViewPath);
        $view->setCompileDir($this->ViewCompile);
        $view->setCacheDir($this->ViewCache);
        return $view;
    }

    /**
     * 配置视图
     * @param $view
     * @return mixed
     */
    private function ViewConfig($view)
    {
        $Config = LogicExceptions::$Config['View'];
        $view->cache_lifetime = $Config['cache_lifetime'];
        $view->caching = $Config['is_cache'];
        $view->left_delimiter = $Config['left_delimiter'];
        $view->right_delimiter = $Config['right_delimiter'];
        return $view;
    }

    /**
     * 获取Smarty原型
     * @return mixed
     */
    public function getView()
    {
        return $this->View;
    }
}