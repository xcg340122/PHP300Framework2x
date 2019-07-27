<?php

namespace Framework\Library\Process;

/**
 * 系统辅助器
 * Class Tool
 * @package Framework\Library\Process
 */
class Tool
{
    /**
     * HTTP状态码
     * @param string $code 状态码
     * @return mixed|string
     */
    static public function httpcode($code = '200')
    {
        $http = [
            '100' => '100 Continue',
            '101' => '101 Switching Protocols',
            '200' => '200 OK',
            '201' => '201 Created',
            '202' => '202 Accepted',
            '203' => '203 Non-Authoritative Information',
            '204' => '204 No Content',
            '205' => '205 Reset Content',
            '206' => '206 Partial Content',
            '300' => '300 Multiple Choices',
            '301' => '301 Moved Permanently',
            '302' => '302 Found',
            '303' => '303 See Other',
            '304' => '304 Not Modified',
            '305' => '305 Use Proxy',
            '307' => '307 Temporary Redirect',
            '400' => '400 Bad Request',
            '401' => '401 Unauthorized',
            '402' => '402 Payment Required',
            '403' => '403 Forbidden',
            '404' => '404 Not Found',
            '405' => '405 Method Not Allowed',
            '406' => '406 Not Acceptable',
            '407' => '407 Proxy Authentication Required',
            '408' => '408 Request Time-out',
            '409' => '409 Conflict',
            '410' => '410 Gone',
            '411' => '411 Length Required',
            '412' => '412 Precondition Failed',
            '413' => '413 Request Entity Too Large',
            '414' => '414 Request-URI Too Large',
            '415' => '415 Unsupported Media Type',
            '416' => '416 Requested range not satisfiable',
            '417' => '417 Expectation Failed',
            '500' => '500 Internal Server Error',
            '501' => '501 Not Implemented',
            '502' => '502 Bad Gateway',
            '503' => '503 Service Unavailable',
            '504' => '504 Gateway Time-out',
            '505' => '505 HTTP Version not supported'
        ];
        return !empty($http[$code]) ? $http[$code] : 'Unknown';
    }

    /**
     * 返回一段兼容网页编码的文本
     * @param string $text 编码的内容
     * @param string $char 编码格式
     * @return string
     */
    static public function ShowText($text = '', $char = "UTF-8")
    {
        return '<meta charset="' . $char . '">' . $text;
    }

    /**
     * 获取客户端IP
     * @return string
     */
    static public function getIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }

    /**
     * 302重定向
     * @param $url
     */
    static public function redirect($url)
    {
        header("Location: {$url}");
        die();
    }

    /**
     * 生成URL
     * @param string $name 地址
     * @param string $parm 参数
     * @return String
     */
    static public function Url($name, $parm = '')
    {
        if (!empty($name)) {
            $SSL = (self::isSSL()) ? ('https://') : ('http://');
            $Port = self::Receive('server.SERVER_PORT');
            $Port = ($Port != '80') ? ($Port) : ('');
            $ExecFile = explode('.php', self::Receive('server.PHP_SELF'));
            $Path = (count($ExecFile) > 0) ? ($ExecFile[0] . '.php') : ('');
            $Url = $SSL . self::Receive('server.HTTP_HOST') . $Port . $Path;
            if (strpos($name, '/')) {
                $PathArr = explode('/', $name);
                foreach ($PathArr as $val) {
                    if (!empty($val)) $Url .= '/' . $val;
                }
                if (!empty($parm)) $Url .= '?' . $parm;
            }
            return $Url;
        }
        return false;
    }

    /**
     * 判断是否为SSL连接
     * @return bool
     */
    static public function isSSL()
    {
        $HTTPS = self::Receive('server.HTTPS');
        $PORT = self::Receive('server.SERVER_PORT');
        if (isset($HTTPS) && ('1' == $HTTPS || 'on' == strtolower($HTTPS))) {
            return true;
        } elseif (isset($PORT) && ('443' == $PORT)) {
            return true;
        }
        return false;
    }

    /**
     * 获取http数据
     * @param string $name 获取的名称
     * @param string $null 默认返回
     * @param bool $isEncode 是否编码
     * @param string $function 编码函数
     * @return string
     */
    static public function Receive($name = '', $null = '', $isEncode = true, $function = 'htmlspecialchars')
    {
        if (strpos($name, '.')) {
            $method = explode('.', $name);
            $name = $method[1];
            $method = $method[0];
        } else {
            $method = '';
        }
        switch (strtolower($method)) {
            case 'get':
                $Data = &$_GET;
                break;
            case 'post':
                $Data = &$_POST;
                break;
            case 'put':
                parse_str(file_get_contents('php://input'), $Data);
                break;
            case 'globals':
                $Data = &$GLOBALS;
                break;
            case 'session':
                $Data = &$_SESSION;
                break;
            case 'server':
                $Data = &$_SERVER;
                break;
            default:
                switch ($_SERVER['REQUEST_METHOD']) {
                    default:
                        $Data = &$_GET;
                        break;
                    case 'POST':
                        $Data = &$_POST;
                        break;
                    case 'PUT':
                        parse_str(file_get_contents('php://input'), $Data);
                        break;
                };
                break;
        }
        if (isset($Data[$name])) {
            if (is_array($Data[$name])) {
                foreach ($Data[$name] as $key => $val) {
                    $Data[$key] = ($isEncode) ? ((function_exists($function)) ? ($function($val)) : ($val)) : ($val);
                }
                return $Data[$name];
            } else {
                $value = ($isEncode) ? ((function_exists($function)) ? ($function($Data[$name])) : ($Data[$name])) : ($Data[$name]);
                return (!is_null($value)) ? ($value) : (($null) ? ($null) : (''));
            }
        } else {
            return $null;
        }
    }

    /**
     * 将字符编码转换到utf8
     * @param string $string 欲转换的字符串
     * @return string
     */
    static public function toUTF8($string = '')
    {
        if (!empty($string)) {
            $encoding = mb_detect_encoding($string, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            if ($encoding != 'UTF-8') {
                return iconv($encoding, 'UTF-8', $string);
            }
            return $string;
        }
        return false;
    }

    /**
     * 判断是否为get请求
     * @return bool
     */
    static public function isGET()
    {
        if (self::Receive('server.REQUEST_METHOD') == 'GET') {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为POST请求
     * @return bool
     */
    static public function isPOST()
    {
        if (self::Receive('server.REQUEST_METHOD') == 'POST') {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为Ajax请求
     * @return bool
     */
    static public function isAjax()
    {
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"])){
            if(strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == 'xmlhttprequest'){ return true; }
        } else {
            if(strpos('application/json',self::Receive('server.HTTP_ACCEPT'))){ return true; }
            return false;
        }
        return false;
    }

    /**
     * 判断是否为cli运行模式
     * @return bool
     */
    static public function isCli()
    {
        if (Running::$runMode == 'cli') {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为windows
     * @return bool
     */
    static public function isWin()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return true;
        }
        return false;
    }

    /**
     * 生成随机字符串
     * @param int $len 字符长度
     * @param bool $lower 是否转小写
     * @return int|mixed|string
     */
    static public function RandStr($len = 8, $lower = false)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i = 0; $i < $len; $i++) {
            $str .= $strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return ($lower) ? strtolower($str) : $str;
    }

    /**
     * 通过一个文本影响加密字符
     * @param $pwd
     * @param string $str
     * @return string
     */
    static public function impact($pwd,$str='')
    {
        return md5(md5(base64_encode($pwd)) . md5($str));
    }

    /**
     * 获取Public路径
     * @return string
     */
    static public function getPublic()
    {
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if (empty($scriptName)) $scriptName = '/';
        if (substr($scriptName, -1) != '/') $scriptName .= '/';
        return rtrim($scriptName) . 'Public/';
    }
}
