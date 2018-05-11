<?php

namespace Framework\Library\Process;

/**
 * 系统结构加载器
 * Class Structure
 * @package Framework\library\_class
 */
class Structure {

    /**
     * 应用列表
     * @var array
     */
    static public $ProjectList = [];

	/**
	 * 后缀信息
	 * @var string
	 */
	private $extend;

    /**
     * 最后读入的文件
     * @var string
     */
    static public $endfile = null;

	/**
	 * 初始化构造
	 * Structure constructor.
	 */
    public function __construct()
    {
        spl_autoload_register([&$this,'__autoload']);
        $this->getProjectList();
        $this->RunTimeInit();
    }

    /**
	 * 自动加载实现
	 * @param string $class 加载对象
	 */
	public function __autoload($class)
    {
		$class = str_replace('\\','/',$class);
        $strpos =  strpos($class,'/Interfaces');
        $framworkPath = $strpos ? \Framework\App::$app->corePath : Running::$framworkPath;
        $this->extend = $strpos ? '.php' : '.class.php';
		$fileObj = strpos($class,'App/')!==false ? $framworkPath . str_replace('App/','Project/',$class) . $this->extend :  $framworkPath . str_replace('Framework/','',$class) . $this->extend;
		if(file_exists($fileObj)){
            self::$endfile = $fileObj;
			include_once $fileObj;
		}else{
            $error = [
                'file' => $fileObj,
                'message' => '404 Not found!'
            ];
            \Framework\App::$app->get('LogicExceptions')->readErrorFile($error);
        }
	}

    /**
     * 获取应用列表
     */
    public function getProjectList()
    {
        $getPath = Running::$framworkPath .'/Project/';
        $Project = $this->getDir($getPath);
        if(is_array($Project) && count($Project) > 0){
            $array = ['Runtime','Common','favicon.ico'];
            foreach($Project as $value){
                if(is_dir($getPath.$value) && !in_array($value,$array)){
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
		if(is_dir($path)){
			return array_merge(array_diff(scandir($path), array('.','..')));
		}
		return false;
	}

	/**
	 * 遍历创建目录
	 * @param $path
	 */
	static public function createDir($path)
    {
		if(!is_dir($path)){
			self::createDir(dirname($path));
			mkdir($path, 0777);
		}
	}

	/**
	 * 初始化结构信息
	 */
	private function RunTimeInit()
    {
        $getPath = Running::$framworkPath . 'Project/';
		$CreateDefaultDir = ['Log'];
		if(is_array(self::$ProjectList) && count(self::$ProjectList) > 0){
			foreach(self::$ProjectList as $value){
				foreach($CreateDefaultDir as $default){
					self::createDir($getPath . 'Runtime/' . $value.'/' .$default);
				}
			}
		}
		require \Framework\App::$app->corePath . 'Library/Common/helper.php';
	}
}
