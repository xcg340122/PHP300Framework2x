<?php

namespace Framework\Library\Process;

use Framework\App;
use \Framework\Library\Interfaces\ConfigInterface as ConfigInterfaces;

/**
 * 配置处理器
 * Class Config
 * @package Framework\Library\Process
 */
class Config implements ConfigInterfaces
{

    /**
     * @var string 配置路径
     */
    private $ConfigPath;

    /**
     * @var array 配置容器
     */
    private $Config = [];

    /**
     * @var array 应用配置
     */
    static public $AppConfig = [];

    /**
     * 初始化配置信息
     * Config constructor.
     */
    public function __construct()
    {
        $this-> ConfigPath = Running::$framworkPath . 'Project/config';
        if (!file_exists($this->ConfigPath)) {
            App::$app->get('Structure')->createDir($this->ConfigPath);
        } else {
            $fileList = App::$app->get('Structure')->getDir($this->ConfigPath);
            if (is_array($fileList)) {
                foreach ($fileList as $key => $value) {
                    if (strpos(strtolower($value), '.cfg.php')) {
                        $this->read(str_replace('.cfg.php', '', $value), $this->ConfigPath . '/' . $value);
                    }
                }
                if(isset($this->Config['App'])){
                    self::$AppConfig = $this->Config['App'];
                }
            }
        }
        $this->loadFrameconf();
    }

    /**
     * 读取配置文件
     * @param string $configName
     * @param string $filePath
     */
    private function read($configName = '', $filePath = '')
    {
        if (is_file($filePath)) $this->Config[$configName] = include_once $filePath;
    }

    /**
     * 获取配置项
     * @param $keys
     * @return mixed
     */
    public function get($keys = null)
    {
        if (is_null($keys)) return $this->Config;
        if (count($this->Config) > 0 && !empty($keys) && isset($this->Config[$keys])) {
            return $this->Config[$keys];
        }
        return false;
    }

    /**
     * 设置临时配置项
     * @param $key
     * @param $val
     */
    public function set($key, $val)
    {
        if (!empty($key) && isset($val)) {
            $this->Config[] = [$key => $val];
        }
    }

    /**
     * 加载框架配置文件
     */
    private function loadFrameconf()
    {
        $this->read('frame', App::$app->corePath . 'Config/frame.cfg.php');
    }
}