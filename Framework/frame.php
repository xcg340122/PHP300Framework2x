<?php

namespace Framework;

use Framework\Library\Process\Structure;
use Framework\Library\Process\Visit;

/**
 * 系统总线
 * Class App
 * @package Framework
 */
class App{

	/**
	* 框架路径
	* @var String
	*/
	public $corePath;

	/**
	 * 钩子列表
	 * @var array
	 */
	private $hook = [];
	
	/**
	* 扩展实例
	* @var Object
	*/	
	static public $extend;
	
	/**
	* 应用实例
	* @var Object
	*/
	static public $app;

	/**
	 * App constructor.
	 * @param $Path
	 */
	public function __construct($Path = '')
    {
		self::$app = $this;
		$this->corePath = is_dir($Path) ? $Path . '/Framework/' : __DIR__ . '/';
		$this->inBatch(['Running','Auxiliary','Structure','Config','LogicExceptions']);
		$this->get('Running')->startRecord();
	}

	/**
	 * 处理实例化实现过程
	 * @param $Pointer
	 * @return mixed
	 */
	public function inProcess($Pointer)
    {
		$PNamespace = "\Framework\Library\Process\\{$Pointer}";
        $Path = $this->corePath.'Library/Process/'.str_replace('\\','/',$Pointer);
        $Path .= strpos($Pointer,'Drive') !==false ? '.php' : '.class.php';
        if(file_exists($Path)){
			require_once $Path;
			return new $PNamespace();
		}
	}

	/**
	 * 处理寄存队列
	 * @param $array
	 */
	public function inBatch($array)
    {
		if(is_array($array)){
			foreach($array as $value){
				$this->get($value);
			}
		}
	}

	/**
	 * 寄存实例对象
	 * @param $Obj
	 */
	public function put($Name,$Obj)
    {
		if(!empty($Name) && is_object($Obj) && empty($this->hook[$Name])) $this->hook[$Name] = $Obj;
	}

	/**
	 * 获取寄存数据
	 * @param $Name
	 * @return mixed
	 */
	public function get($Name)
    {
		if(!empty($this->hook[$Name]) && is_object($this->hook[$Name])) return $this->hook[$Name];
		$this->put($Name,$this->inProcess($Name));
		return $this->hook[$Name];
	}

	/**
	 * 处理应用
	 */
	public function __invoke()
    {
		$this->inBatch(['Visit','ReturnHandle','Db','Extend']);
		return $this;
	}

	/**
	 * 运行应用
	 */
	public function run()
    {
        $object = Visit::mergeParam();

        $function = Visit::getfunction();

        $app = new $object();

        if(method_exists($app,$function))
        {
            $this->get('ReturnHandle')->Output($app->$function());

        }else{
            $error = [
                'file' => Structure::$endfile,
                'message' => "'{$function}' Method does not exist!"
            ];
            $this->get('LogicExceptions')->readErrorFile($error);
        }

        $this->get('Running')->endRecord();
	}
}