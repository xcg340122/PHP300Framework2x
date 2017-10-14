<?php

namespace Framework\Library\Process;

/**
 * 异常处理器
 * Class LogicExceptions
 * @package Framework\Library\Process
 */
use \Framework\Library\Interfaces\LogicExceptionsInterface as LogicExceptionsInterfaces;
class LogicExceptions implements LogicExceptionsInterfaces
{
    /**
     * 构造函数,挂载中断回调
     * LogicExceptions constructor.
     */
    public function __construct()
    {
        error_reporting(0);
        register_shutdown_function([&$this,'Mount']);
    }

    /**
     * 挂载异常钩子
     */
    public function Mount()
    {
        $error = error_get_last();
        if($error != NULL){
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
            $error['message'] = Auxiliary::toUTF8($error['message']);
            $error['type'] =  (!empty($errorType[$error['type']]))?($errorType[$error['type']]):('Unknown');
            $log = 'type: ';
            $log .= $error['type'];
            $log .= "\r\n".'message: '.$error['message']."\r\n";
            $log .= 'file: '.$error['file']."\r\n";
            $log .= 'line: '.$error['line'];
            $Project = $this->getProjectName($error['file']);
            if($Project){
                \Framework\App::$app->get('Log')->Record(Running::$framworkPath .
                    '/Project/Runtime/'.$Project.'/Log','Error',$log);
            }
            $this->readErrorFile($error);
        }
    }

    /**
     * 返回关联应用
     * @param $Path
     * @return bool
     */
    private function getProjectName($Path)
    {
        \Framework\App::$app->get('Structure');
        $Path = str_replace('\\','/',$Path);
        $Temporary = explode('Project/',$Path);
        if(!empty($Temporary[1])){
            $Temporary = explode('/',$Temporary[1]);
            if(!empty($Temporary[0]) && in_array($Temporary[0],Structure::$ProjectList)){
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
        header('HTTP/1.1 '.Auxiliary::httpcode(500));
        $path = $error['file'];
        $line = isset($error['line']) && is_int($error['line']) ? $error['line'] : 0;
        if(file_exists($path) && $line > 0){
            $handle = fopen( $path, "r" );
            $count = 1;
            $content = [];
            while($lines =  fgets($handle))
            {
                $content[] = array($count,$lines);
                $count++;
                if($line == ($count - 5)){
                    break;
                }
            }
            fclose($handle);
            if(count($content) > 10){
                $content = array_slice($content,-10,15);
            }
            $code = '';
            foreach($content as $key=>$value){
                if($value[0] == $line){
                    $value[0] = '>>';
                }
                $code .= $value[0] . ' '. $value[1];
            }
        }
        $staticPath = '/Framework/Library/Process/Tpl/';
        include_once \Framework\App::$app->corePath . 'Library/Process/Tpl/error.tpl';
        exit();
    }

}