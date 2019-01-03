<?php

namespace Framework\Library\Process\Drive\Db;

use Framework\Library\Interfaces\DbInterface as DbInterfaces;

/**
 * PDO Driver
 * Class PDO
 */
class Pdo implements DbInterfaces
{

    /**
     * 数据错误信息
     * @var string
     */
    public $dbErrorMsg = 'SQL IN WRONG: ';

    /**
     * 获取方式
     * @var int
     */
    public $fetchMethod = \PDO::FETCH_OBJ;

    /**
     * 实例对象
     * @var
     */
    protected $pdo;

    /**
     * 结果集
     * @var bool
     */
    protected $result = false;

    /**
     * 影响条数
     * @var
     */
    protected $total;

    /**
     * 操作表名
     * @var null
     */
    protected $tableName = NULL;

    /**
     * debug
     * @var array
     */
    protected $queryDebug = [];

    /**
     * 数据主键
     * @var string
     */
    protected $key = '';

    /**
     * 是否缓存数据
     * @var bool
     */
    protected $iscache = false;

    /**
     * 数据表信息
     * @var array
     */
    protected $data = [];

    /**
     * 数据库名
     * @var string
     */
    protected $database = '';

    /**
     * 表前缀
     * @var string
     */
    protected $tabprefix = '';

    /**
     * 获取异常信息
     * @return mixed
     */
    public function getError()
    {
        return $this->pdo->errorInfo();
    }

    /**
     * 连接数据库
     * @param array $config
     * @return bool|PDO
     */
    public function connect($config = [])
    {
        if (count($config) == 0) {
            return false;
        }
        try {

            $dsn = !empty($config['dsn']) ? $config['dsn'] : "mysql:host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['database'];
            $opt = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . $config['char'] . "'"
            );

            $this->pdo = new \PDO($dsn, $config['username'], $config['password'], $opt);
            $this->database = $config['database'];
            if(!empty($config['tabprefix'])){
                $this->tabprefix = $config['tabprefix'];
            }
            return $this->pdo;
        } catch (\PDOException $ex) {
            exit($this->dbErrorMsg . $ex->getMessage());
        }
    }

    /**
     * 操作多数据库连接
     * @param $link
     * @return $this
     */
    public function setlink($link)
    {
        $this->pdo = $link;
        return $this;
    }


    /**
     * 销毁连接
     * @return bool
     */
    public function disconnect()
    {
        $this->pdo = NULL;

        return true;
    }


    /**
     * 获取所有记录
     * @return bool
     */
    public function get()
    {
        return $this->result;
    }


    /**
     * 获取默认记录
     * @return null
     */
    public function find()
    {
        if ($this->result) {
            return count($this->result) > 0 ? $this->result[0] : NULL;
        }

        return NULL;
    }


    /**
     * debug
     * @return array
     */
    public function debug()
    {
        return $this->queryDebug;
    }

    /**
     * 获取影响的条数
     * @return mixed
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * 执行SQL
     * @param $queryString
     * @param bool $select
     * @return $this
     */
    public function query($queryString, $select = false)
    {
        try {
            $qry = $this->pdo->prepare($queryString);
            $qry->execute();
            $qry->setFetchMode($this->fetchMethod);

            if ($this->startsWith(strtolower($queryString), "select")) {
                $this->result = $qry->fetchAll();
            }

            $this->total = $qry->rowCount();

            $this->queryDebug = ['string' => $queryString, 'total' => $this->total];

            $this->handleres($queryString);

        } catch (\PDOException $ex) {
            $this->handleres($queryString, true . $ex);
        }

        return $this;
    }

    /**
     * If string starts with
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    protected function startsWith($haystack, $needle)
    {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

    /**
     * 处理结果
     * @param $sql
     * @param bool $iserror
     * @param array $ex
     */
    private function handleres($sql, $iserror = false, $ex = [])
    {
        if ($iserror) {
            $status = 'error';
            $errormsg = $ex->getMessage();
        } else {
            $status = 'success';
        }
        $Logs = "[{$status}] " . $sql;
        if (isset($errormsg)) {
            $Logs .= "\r\n[message] " . $errormsg;
        }

        \Framework\App::$app->get('Log')->Record(\Framework\Library\Process\Running::$framworkPath . '/Project/Runtime/Datebase', 'sql', $Logs);
        if ($iserror) {
            $message = $errormsg . ' (SQL：' . $sql . ')';
            \Framework\App::$app->get('LogicExceptions')->readErrorFile([
                'type' => 'DataBase Error',
                'message' => $message
            ]);
        }
    }

    /**
     * 选择数据表
     * @param $tableName
     * @return $this
     */
    public function table($tableName = '')
    {
        if (!empty($tableName)) {
            $this->tableName = '`' . $this->tabprefix . $tableName . '`';
            $this->getTableInfo();
            return $this;
        } else {
            \Framework\App::$app->get('LogicExceptions')->readErrorFile([
                'file' => __FILE__,
                'message' => 'Need to fill in Table Value!',
            ]);
        }
    }

    /**
     * 返回数据表信息
     * @return array|bool
     */
    protected function getTableInfo()
    {
        if ($this->tableName == '') {
            \Framework\App::$app->get('LogicExceptions')->readErrorFile([
                'message' => '尚未设定操作的数据表名称'
            ]);
        } else {
            $queryString = str_replace('`', '', "select COLUMN_NAME,DATA_TYPE,COLUMN_DEFAULT,COLUMN_KEY,COLUMN_COMMENT  from information_schema.COLUMNS where table_name = '" . $this->tableName . "';");

            $qry = $this->pdo->prepare($queryString);

            $qry->execute();

            $qry->setFetchMode(\PDO::FETCH_ASSOC);

            $this->data = $qry->fetchAll();

            if ($this->data) {
                foreach ($this->data as $key => $value) {
                    if ($this->data[$key]['COLUMN_KEY'] == 'PRI') {
                        $this->key = $this->data[$key]['COLUMN_NAME'];
                        break;
                    }
                    return $this->data;
                }
                return false;
            }
        }
        return false;
    }

    /**
     * 查询数据
     * @param array $qryArray
     * @return $this
     */
    public function select($qryArray = [])
    {
        $field = '';
        $join = '';
        $where = '';
        $order = '';
        $group = '';
        $limit = '';

        if (isset($qryArray['field'])) {
            if(is_array($qryArray['field'])){
                if(isset($qryArray['field']['NOT'])){
                    if(is_array($qryArray['field']['NOT'])){
                        $field_arr = $this->getField();
                        if(is_array($field_arr)){
                            foreach ($field_arr as $key=>$value) {
                                if(!in_array($value['COLUMN_NAME'] , $qryArray['field']['NOT'])){
                                    $field .= '`'.$value['COLUMN_NAME'] . '`,';
                                }
                            }
                            $field = rtrim($field,'.,');
                        }
                    }
                }else{
                    foreach ($qryArray['field'] as $key=>$value){
                        $field .= '`'.$value . '`,';
                    }
                    $field = rtrim($field,'.,');
                }
            }else{
                $field = $qryArray['field'];
            }
        }
        if (empty($field)) $field = ' * ';

        if (isset($qryArray['join'])) {
            $join = is_array($qryArray['join']) ? ' '.implode(' ', $qryArray['join']) : ' '.$qryArray['join'];
        }
        if (isset($qryArray['where'])) {
            $where = $this->structureWhere($qryArray['where']);
        }
        if (isset($qryArray['orderby'])) {
            $order = is_array($qryArray['orderby']) ? implode(',', $qryArray['orderby']) : $qryArray['orderby'];
            $order = ' ORDER BY ' . $order;
        }
        if (isset($qryArray['groupby'])) {
            $group = is_array($qryArray['groupby']) ? implode(',', $qryArray['groupby']) : $qryArray['groupby'];
            $group = ' GROUP BY ' . $group;
        }
        if (isset($qryArray['limit'])) {
            $limit = is_array($qryArray['limit']) ? implode(',', $qryArray['limit']) : $qryArray['limit'];
            $limit = ' LIMIT ' . $limit;
        }
        $queryString = 'SELECT ' . $field . ' FROM ' . $this->tableName . $join . $where . $group . $order . $limit;

        try {

            $qry = $this->pdo->prepare($queryString);

            $qry->execute();

            $qry->setFetchMode($this->fetchMethod);

            $this->result = $qry->fetchAll();

            $this->total = $qry->rowCount();

            $this->queryDebug = ['string' => $queryString, 'affectedRows' => $this->total];

            $this->handleres($queryString);

            return $this;
        } catch (\PDOException $ex) {
            $this->handleres($queryString, true, $ex);
        }
    }

    /**
     * 条件构造
     * @param array $whereData
     * @return string
     */
    private function structureWhere($whereData = [])
    {
        $where = ' WHERE ';
        if (is_array($whereData)) {
            foreach ($whereData as $key => $value) {
                if (is_array($value) && count($value) > 1) {
                    $value[1] = addslashes($value[1]);
                    switch (strtolower($value[0])) {
                        case 'in':
                            $where .= $key . ' IN(' . $value[1] . ') AND ';
                            break;
                        case 'string':
                            $where .= $key . $value[1] . ' AND ';
                            break;
                        default:
                            $value[1] = is_numeric($value[1]) ? $value[1] : "'" . $value[1] . "'";
                            $where .= $key . ' ' . $value[0] . ' ' . $value[1] . ' AND ';
                            break;
                    }
                } else {
                    $value = addslashes($value);
                    $value = is_numeric($value) ? $value : "'" . $value . "'";
                    $where .= $key . '=' . $value . ' AND ';
                }
            }
            return rtrim($where, '. AND ');
        }
        return $where . $whereData;
    }

    /**
     * 插入数据(别名)
     * @param array $dataArray
     * @return bool
     */
    public function add($dataArray = [])
    {
        return $this->insert($dataArray);
    }

    /**
     * 插入数据
     * @param array $dataArray
     * @return bool|mixed
     */
    public function insert($dataArray = [])
    {
        if (is_array($dataArray) && count($dataArray) > 0) {
            $v_key = '';
            $v_value = '';
            foreach ($dataArray as $key => $value) {
                $v_key .= '`' . $key . '`,';
                $v_value .= is_int($value) ? $value . ',' : "'{$value}',";
            }
            $v_key = rtrim($v_key, '.,');
            $v_value = rtrim($v_value, '.,');

            $queryString = 'INSERT INTO ' . $this->tableName . ' (' . $v_key . ') VALUES(' . $v_value . ');';

            try {
                $this->pdo->exec($queryString);

                $lastInsertedId = $this->pdo->lastInsertId();

                $this->queryDebug = ['string' => $queryString, 'value' => $value, 'insertedid' => $lastInsertedId];

                $this->handleres($queryString);

                return $lastInsertedId;
            } catch (\PDOException $ex) {
                $this->handleres($queryString, true, $ex);
            }
        }
        return false;
    }

    /**
     * 修改数据(别名)
     * @param array $dataArray
     * @param string $where
     * @return bool
     */
    public function save($dataArray = [], $where = '')
    {
        return $this->update($dataArray, $where);
    }

    /**
     * 修改数据
     * @param array $dataArray
     * @param array $where
     * @return bool
     */
    public function update($dataArray = [], $where = [])
    {
        if (is_array($dataArray) && count($dataArray) > 0) {
            $updata = '';
            foreach ($dataArray as $key => $value) {
                $value = is_int($value) ? $value : "'{$value}'";
                $updata .= "`$key`={$value},";
            }
            if (!empty($where)) $where = $this->structureWhere($where);
            $queryString = 'UPDATE ' . $this->tableName . ' SET ' . rtrim($updata, '.,') . $where;

            try {
                $this->total = $this->pdo->exec($queryString);

                $this->queryDebug = ['string' => $queryString, 'update' => $updata, 'affectedRows' => $this->total];

                $this->handleres($queryString);

                return $this->total;
            } catch (\PDOException $ex) {
                $this->handleres($queryString, true, $ex);
            }

        }
        return false;
    }

    /**
     * 删除数据(别名)
     * @param array $where
     * @return bool
     */
    public function del($where = [])
    {
        return $this->delete($where);
    }

    /**
     * 删除数据
     * @param $where
     * @return mixed
     */
    public function delete($where)
    {
        if (!empty($where)) $where = $this->structureWhere($where);

        $queryString = 'DELETE FROM ' . $this->tableName . $where;

        try {
            $this->total = $this->pdo->exec($queryString);

            $this->queryDebug = ['string' => $queryString, 'affectedRows' => $this->total];

            $this->handleres($queryString);

            return $this->total;
        } catch (\PDOException $ex) {
            $this->handleres($queryString, true, $ex);
        }
    }

    /**
     * 获取数据表主键
     * @return string
     */
    public function getkey()
    {
        return $this->key;
    }

    /**
     * 获取所有字段信息
     * @return array
     */
    public function getField()
    {
        return $this->data;
    }

    /**
     * 获取所有数据表
     * @return array|bool|null
     */
    public function getTables()
    {
        $queryString = "select table_name from information_schema.tables where table_schema='" . $this->database . "' and table_type='base table';";

        $qry = $this->pdo->prepare($queryString);

        $qry->execute();

        $qry->setFetchMode(\PDO::FETCH_ASSOC);

        $list = $qry->fetchAll();

        if (is_array($list)) {
            $_list = [];
            foreach ($list as $key => $value) {
                $_list[] = $list[$key]['table_name'];
            }
            return $_list;
        }
        return $list;
    }

}
