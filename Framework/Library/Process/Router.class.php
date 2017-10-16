<?php

namespace Framework\Library\Process;

use \Framework\Library\Interfaces\RouterInterface as RouterInterfaces;
/**
 * 系统路由
 * Class Router
 * @package Framework\Library\Process
 */
class Router implements RouterInterfaces
{

    /**
     * 用户请求地址
     * @var string
     */
    static public $requestUrl;

    /**
     * 路由配置信息
     * @var array
     */
    private $RouteConfig = [];

    /**
     * 初始化构造
     * Router constructor.
     */
    public function __construct()
    {
        self::$requestUrl = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $this->RouteConfig = \Framework\App::$app->get('Config')->get('Router');
        $this->Matching();
        $this->Route();
    }

    /**
     * 默认路由
     * @param $entrance
     */
    public function Route()
    {
        if (strpos(self::$requestUrl, '?')) {
            preg_match_all('/^\/(.*)\?/', self::$requestUrl, $Url);
        } else {
            preg_match_all('/^\/(.*)/', self::$requestUrl, $Url);
        }

        if(empty($Url[1][0])) return;

        $Url = $Url[1][0];

        $Url = explode('/', $Url);

        \Framework\App::$app->get('Visit')->bind($Url);
    }

    /**
     * 路由匹配
     * @param $Url
     */
    private function Matching()
    {
        $Url = self::$requestUrl;
        if(isset($this->RouteConfig['useRule']) && is_array($this->RouteConfig['useRule'])){

            foreach($this->RouteConfig['useRule'] as $key=>$value){

                $key = $this->replaceMatch($key,$this->RouteConfig['Acter']);
                
                preg_match($key,$Url, $subject);
                if(count($subject) > 1){
                    $Url = $this->replaceMatch($value,$subject);
                    if(!empty($Url)){
                        self::$requestUrl = $Url;
                    }
                    break;
                }
            }
        }
    }

    /**
     * 字序替换
     * @param $keys
     * @param $array
     * @return mixed
     */
    private function replaceMatch($keys,$array)
    {
        if(!empty($keys) && is_array($array)){
            foreach($array as $key=>$value){
                $keys = str_replace('{'.$key.'}',$value,$keys);
            }
        }
        return $keys;
    }

}