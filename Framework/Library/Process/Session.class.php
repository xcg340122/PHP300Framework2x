<?php

namespace Framework\Library\Process;

use \Framework\Library\Interfaces\SessionInterface as SessionInterfaces;

/**
 * Session操作器
 * Class Session
 * @package Framework\Library\Process
 */
class Session implements SessionInterfaces
{

    /**
     * @var string 缓存名称
     */
    private $Name = 'PHP300SESSION';

    /**
     * @var string 缓存周期,单位：秒
     */
    private $Second = 0;

    /**
     * 开启session
     */
    public function start()
    {
        if (!isset($_SESSION)) {
            ini_set('session.name', $this->Name);
            ini_set('session.auto_start', '1');
            ini_set('session.cookie_lifetime', $this->Second);
            session_start();
        }
    }

    /**
     * 获取session
     * @param string $name
     * @return bool
     */
    public function get($name = '')
    {
        if (!empty($name)) {
            return (!empty($_SESSION[$name])) ? ($_SESSION[$name]) : (FALSE);
        }
        return $_SESSION;
    }

    /**
     * 设置session
     * @param string $name
     * @param string $value
     * @return string
     */
    public function set($name = 'php300', $value = '')
    {
        $_SESSION[$name] = $value;
        return true;
    }

    /**
     * 删除session
     * @param string $name
     * @return string
     */
    public function del($name = '')
    {
        if (empty($name)) {
            session_destroy();
        } else {
            $_SESSION[$name] = NULL;

        }
        return true;
    }
}