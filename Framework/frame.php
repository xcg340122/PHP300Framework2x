<?php

namespace Framework;

use Framework\Library\Process\Running;
use Framework\Library\Process\Structure;
use Framework\Library\Process\Visit;

/**
 * 系统总线
 * Class App
 * @author chungui
 * @version 2.5.3
 * @package Framework
 */
class App
{

    /**
     * @var Object 扩展实例
     */
    static public $extend;

    /**
     * @var Object 应用实例
     */
    static public $app;

    /**
     * @var String 框架路径
     */
    public $corePath;

    /**
     * @var array 钩子列表
     */
    private $hook = [];

    /**
     * App constructor.
     * @param $Path
     */
    public function __construct($Path = '')
    {
        $ini = ini_get('date.timezone');
        if (empty($ini)) {
            ini_set('date.timezone', 'Asia/Shanghai');
        }
        self::$app = $this;
        $this->corePath = is_dir($Path) ? $Path . '/Framework/' : __DIR__ . '/';
        $this->inBatch(['Running', 'Tool', 'Structure', 'Config', 'Log', 'LogicExceptions']);
        $this->get('Running')->startRecord();
    }

    /**
     * 处理寄存队列
     * @param $array
     */
    public function inBatch($array)
    {
        if (is_array($array)) {
            foreach ($array as $value) {
                $this->get($value);
            }
        }
    }

    /**
     * 获取寄存数据
     * @param $Name
     * @return mixed
     */
    public function get($Name)
    {
        if (!empty($this->hook[$Name]) && is_object($this->hook[$Name])) return $this->hook[$Name];
        $this->put($Name, $this->inProcess($Name));
        return $this->hook[$Name];
    }

    /**
     * 寄存实例对象
     * @param string $Name
     * @param $Obj
     */
    public function put($Name, $Obj)
    {
        if (!empty($Name) && is_object($Obj) && empty($this->hook[$Name])) $this->hook[$Name] = $Obj;
    }

    /**
     * 处理实例化实现过程
     * @param $Pointer
     * @return mixed
     */
    public function inProcess($Pointer)
    {
        $PNamespace = "\Framework\Library\Process\\{$Pointer}";
        $Path = $this->corePath . 'Library/Process/' . str_replace('\\', '/', $Pointer);
        $Path .= strpos($Pointer, 'Drive') !== false ? '.php' : '.class.php';
        if (file_exists($Path)) {
            require_once($Path);
            return new $PNamespace();
        }
        return false;
    }

    /**
     * 处理应用
     * @return $this
     */
    public function __invoke()
    {
        $this->inBatch(['Visit', 'Db', 'Extend']);
        return $this;
    }

    /**
     * 运行应用
     */
    public function run()
    {
        Running::$runMode = php_sapi_name();
        if (Running::$runMode == 'cli') {
            Visit::setCliParam();
        }
        $object = Visit::mergeParam();
        $function = Visit::getfunction();
        Running::setconstant();
        $app = new $object();
        if (method_exists($app, $function)) {
            $this->get('ReturnHandle')->Output($app->$function());
        } else {
            $this->get('LogicExceptions')->readErrorFile([
                'file' => Structure::$endfile,
                'message' => "[{$function}] 方法不存在!"
            ]);
        }
    }
}