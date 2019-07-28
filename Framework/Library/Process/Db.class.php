<?php

namespace Framework\Library\Process;

use Framework\App;
/**
 * 数据基础模型
 * Class Db
 * @package Framework\Library\Process
 */
class Db
{

    /**
     * @var array 数据库连接标识组
     */
    private $link = [];

    /**
     * @var string 操作库对象
     */
    private $db = '';

    /**
     * @var array 数据库驱动映射
     */
    private $dbType = [

        'mysqli' => 'Drive\Db\Mysqli',

        'pdo' => 'Drive\Db\Pdo'
    ];

    /**
     * 构造方法
     * Db constructor
     */
    public function __construct()
    {
        $this->init(Config::$AppConfig['db']);
    }

    /**
     * 初始化数据库连接
     * @param array $configArr
     */
    public function init($configArr = [])
    {
        if (is_array($configArr)) {
            foreach ($configArr as $key => $value) {
                $this->addlink($key, $value);
            }
        }
    }

    /**
     * 添加连接信息
     * @param $name
     * @param $config
     */
    private function addlink($name, $config)
    {
        if (!empty($name) && is_array($config) && $config['connect'] === true) {
            if (isset($config['dbType']) && isset($this->dbType[strtolower($config['dbType'])]) && isset($config['username'])) {
                if (!isset($this->link[$name])) {
                    $this->db = \Framework\App::$app->get($this->dbType[strtolower($config['dbType'])]);
                    $this->putlink($name, ['obj' => $this->db, 'link' => $this->db->connect($config)]);
                }
            }
        }
    }

    /**
     * 压入连接
     * @param $link
     */
    private function putlink($name, $link)
    {
        $this->link[$name] = $link;
    }

    /**
     * 获取所有建立的连接
     * @return array
     */
    public function getlink()
    {
        return $this->link;
    }

}