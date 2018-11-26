<?php

namespace Framework\Library\Process;

/**
 * 异常处理器
 * Class LogicExceptions
 * @package Framework\Library\Process
 */
use Framework\Library\Interfaces\LogicExceptionsInterface as LogicExceptionsInterfaces;

class LogicExceptions implements LogicExceptionsInterfaces
{
    /**
     * 配置信息
     * @var array
     */
    static public $Config;

    /**
     * 构造函数,挂载中断回调
     * LogicExceptions constructor.
     */
    public function __construct()
    {
        error_reporting(0);
        register_shutdown_function([&$this, 'Mount']);
        self::$Config = \Framework\App::$app->get('Config')->get('frame');
        Running::$Debug = self::$Config['Exception']['display_switch'];
    }

    /**
     * 挂载异常钩子
     */
    public function Mount()
    {
        $error = error_get_last();
        if ($error != NULL) {
            $errorType = [
                1 => 'E_ERROR',
                2 => 'E_WARNING',
                4 => 'E_PARSE',
                8 => 'E_NOTICE',
                16 => 'E_CORE_ERROR',
                32 => 'E_CORE_WARNING',
                64 => 'E_COMPILE_ERROR',
                128 => 'E_COMPILE_WARNING',
                256 => 'E_USER_ERROR',
                512 => 'E_USER_WARNING',
                1024 => 'E_USER_NOTICE',
                2048 => 'E_STRICT',
                4096 => 'E_RECOVERABLE_ERROR',
                8192 => 'E_DEPRECATED',
                16384 => 'E_USER_DEPRECATED',
                30719 => 'E_ALL',
            ];
            $error['type'] = (!empty($errorType[$error['type']])) ? ($errorType[$error['type']]) : ('Unknown');
            $error['message'] = Auxiliary::toUTF8($error['message']);
            if (isset(self::$Config['log']['error_switch']) && self::$Config['log']['error_switch'] === true) {

                if ($this->judgeLevel($error['type'], self::$Config['log']['error_level'])) {
                    $log = 'type: ';
                    $log .= $error['type'];
                    $log .= "\r\n" . 'message: ' . $error['message'] . "\r\n";
                    $log .= 'file: ' . $error['file'] . "\r\n";
                    $log .= 'line: ' . $error['line'];

                    $Project = $this->getProjectName($error['file']);
                    if ($Project) {
                        \Framework\App::$app->get('Log')->Record(Running::$framworkPath . '/Project/Runtime/' . $Project . '/Log', 'Error', $log);
                    }
                }
            }
            if (isset(self::$Config['Exception']['display_switch']) && self::$Config['Exception']['display_switch'] === true) {
                if ($this->judgeLevel($error['type'], self::$Config['Exception']['display_level'])) {
                    $this->readErrorFile($error);
                }
            }
        }
    }

    /**
     * 判断错误级别
     * @param $errorlevel
     * @param $error
     * @return bool
     */
    private function judgeLevel($errorlevel, $error)
    {
        if (!empty($errorlevel) && !empty($error)) {
            if ($error == 'E_ALL') return true;
            $errorList = explode('|', $error);
            foreach ($errorList as $key => $value) {
                $errorList[$key] = trim($value);
            }
            return in_array($errorlevel, $errorList);
        }
        return false;
    }

    /**
     * 返回关联应用
     * @param $Path
     * @return bool
     */
    private function getProjectName($Path)
    {
        \Framework\App::$app->get('Structure');
        $Path = str_replace('\\', '/', $Path);
        $Temporary = explode('Project/', $Path);
        if (!empty($Temporary[1])) {
            $Temporary = explode('/', $Temporary[1]);
            if (!empty($Temporary[0]) && in_array($Temporary[0], Structure::$ProjectList)) {
                return $Temporary[0];
            }
            return false;
        }
        return false;
    }

    /**
     * 处理错误文件
     * @param $error
     */
    public function readErrorFile($error)
    {
        if (Running::$runMode == 'cli') {
            $error_string = "\r\nPHP300FrameworkError:\r\nerror_messag:{$error['message']}";
            if (isset($error['file'])) $error_string .= "\r\nerror_file:{$error['file']}";
            if (isset($error['line'])) $error_string .= "\r\nerror_line:{$error['line']}";
            die($error_string);
        } else {
            ob_clean();
        }
        if (Running::$Debug === true) {
            header('HTTP/1.1 ' . Auxiliary::httpcode(500));
            if (isset($error['file'])) {
                $path = $error['file'];
                $line = isset($error['line']) && is_int($error['line']) ? $error['line'] : 0;
                if (file_exists($path) && $line > 0) {
                    $handle = fopen($path, "r");
                    $count = 1;
                    $content = [];
                    while ($lines = fgets($handle)) {
                        $content[] = array($count, $lines);
                        $count++;
                        if ($line == ($count - 5)) {
                            break;
                        }
                    }
                    fclose($handle);
                    if (count($content) > 10) {
                        $content = array_slice($content, -10, 15);
                    }
                    $code = '';
                    foreach ($content as $key => $value) {
                        if ($value[0] == $line) {
                            $value[0] = '<font color="red">>></font>';
                        }
                        $code .= $value[0] . ' ' . $value[1];
                    }
                    $error['code'] = $code;
                }
            }
            $View = View('', \Framework\App::$app->corePath . 'Library/Process/Tpl/error.tpl');
            $View->getView()->left_delimiter = '{';
            $View->getView()->right_delimiter = '}';
            die($View->data([
                'Path' => Auxiliary::getPublic(),
                'Error' => $error,
                'Server' => $_SERVER
            ])->get());
        }
        $this->displayed('error', [
            'title' => '网站抽风啦!',
            'second' => '3',
            'message' => '出错啦~~~',
            'describe' => '系统异常，请联系管理员!',
            'url' => ''
        ]);
    }

    /**
     * 展示状态页
     * @param string $page
     * @param array $data
     */
    public function displayed($page = 'success', $data = array())
    {
        $View = View('', \Framework\App::$app->corePath . 'Library/Process/Tpl/' . $page . '_page.tpl');
        $View->getView()->left_delimiter = '{';
        $View->getView()->right_delimiter = '}';
        die($View->data(['data' => $data])->get());
    }
}