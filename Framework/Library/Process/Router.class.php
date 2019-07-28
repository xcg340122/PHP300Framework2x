<?php

namespace Framework\Library\Process;

use Framework\App;
use Framework\Library\Interfaces\RouterInterface as RouterInterfaces;

/**
 * 系统路由
 * Class Router
 * @package Framework\Library\Process
 */
class Router implements RouterInterfaces
{

    /**
     * @var string 用户请求地址
     */
    static public $requestUrl = '';

    /**
     * @var array 路由配置信息
     */
    private $RouteConfig = [];

    /**
     * 初始化构造
     * Router constructor.
     */
    public function __construct()
    {
        $this->RouteConfig = Config::$AppConfig['router'];
        self::$requestUrl = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
        $this->Route();
        $this->Matching();
        $this->TraditionUrl();
    }

    /**
     * 默认路由
     * @return mixed|void
     */
    public function Route()
    {
        if (strpos(self::$requestUrl, '?')) {
            preg_match_all('/^\/(.*)\?/', self::$requestUrl, $Url);
        } else {
            preg_match_all('/^\/(.*)/', self::$requestUrl, $Url);
        }
        if (empty($Url[1][0])) return;
        $Url = $Url[1][0];
        $Url = explode('/', $Url);
        App::$app->get('Visit')->bind($Url);
    }

    /**
     * 路由匹配
     */
    private function Matching()
    {
        if (self::$requestUrl == '') {
            $Url = '/';
        } else {
            $Url = '/' . ucwords(Visit::$param['Project']) . '/' . ucwords(Visit::$param['Controller']) . '/' . Visit::$param['Function'];
        }
        $Url = strtolower($Url);
        if (count($this->RouteConfig) > 0 && isset($this->RouteConfig[$Url])) {
            $function = $this->RouteConfig[$Url];
            if (gettype($function) == 'object') {
                App::$app->get('ReturnHandle')->Output($function());
                die();
            }
        }
    }

    /**
     * 传统URL请求匹配
     */
    private function TraditionUrl()
    {
        $Project = get(Visit::$request['Project']);
        $Controller = get(Visit::$request['Controller']);
        $Function = get(Visit::$request['Function']);
        if (!empty($Project)) {
            $list = Structure::$ProjectList;
            $ins = ['model','view'];
            foreach ($list as $key=>$val){
                if(in_array($val,$ins)){ unset($list[$key]); }
            }
            if(in_array($Project,$list)){
                Visit::$param['Project'] = $Project;
            }else{
                App::$app->get('LogicExceptions')->readErrorFile([
                    'message' => '抱歉,您请求的实例不存在!(请检查您的GET参数'.Visit::$request['Project'].')'
                ]);
            }
        }
        if (!empty($Controller)) {
            Visit::$param['Controller'] = $Controller;
        }
        if (!empty($Function)) {
            Visit::$param['Function'] = $Function;
        }
    }
}