<?php

namespace Framework\Library\Process\Drive\Db;

use Framework\Library\Interfaces\DbInterface as DbInterfaces;
use Framework\Library\Process\Auxiliary;

/**
 * Mysqli Driver
 * Class Mysqli
 */
class Mysqli implements DbInterfaces
{


    /**
     * 操作表
     * @var string
     */
    public $tableName = '';

    /**
     * 连接资源
     * @var null
     */
    public $link = null;

    /**
     * 对象ID
     * @var
     */
    public $queryId;

    /**
     * 结果集
     * @var bool
     */
    protected $result = false;

    /**
     * 调试信息
     * @var bool
     */
    protected $queryDebug = false;

    /**
     * 影响条数
     * @var
     */
    protected $total;

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        if (is_resource($this->link)) {
            return mysqli_error($this->link);
        }
        return 'Invalid resources';
    }

    /**
     * 连接数据库
     * @param array $config
     * @return \mysqli|null
     */
    public function connect($config = [])
    {
        $this->link = @mysqli_connect($config['host'], $config['username'], $config['password'], $config['database'], $config['port']);
        if ($this->link != null) {
            mysqli_query($this->link, 'set names ' . $config['char']);
            return $this->link;
        } else {
            $error = [
                'file' => __FILE__,
                'message' => 'Mysql Host[ ' . $config['host'] . ' ] :: ' . Auxiliary::toUTF8(mysqli_connect_error())
            ];
            \Framework\App::$app->get('LogicExceptions')->readErrorFile($error);
        }
    }

    /**
     * 操作多数据库连接
     * @param $link
     */
    public function setlink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * 关闭数据库
     */
    public function disconnect()
    {
        mysqli_close($this->link);
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
     * 获取影响条数
     * @return mixed
     */
    public function total()
    {
        return $this->total;
    }

    /**
     * 设定查询表
     * @param $tabName
     * @return $this
     */
    public function table($tabName = '')
    {
        if (!empty($tabName)) {
            $this->tableName = '`'.$tabName.'`';
            return $this;
        } else {
            \Framework\App::$app->get('LogicExceptions')->readErrorFile([
                'file' => __FILE__,
                'message' => 'Need to fill in Table Value!',
            ]);
        }
    }

    /**
     * 插入数据
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
            $field = is_array($qryArray['field']) ? implode(',', $qryArray['field']) : $qryArray['field'];
        }
        if (empty($field)) $field = ' * ';

        if (isset($qryArray['join'])) {
            $join .= ' ' . is_array($qryArray['join']) ? implode(' ', $qryArray['join']) : $qryArray['join'];
            if (empty($join)) {
                $join = ' ' . $join;
            }
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

        $res = $this->query($queryString, true);

        if ($res) {
            $this->result = mysqli_fetch_all($res, MYSQLI_ASSOC);
        }

        $this->total = $this->affectedRows();

        $this->queryDebug = ['string' => $queryString, 'affectedRows' => $this->total];

        return $this;
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
                    $value[1] = mysqli_real_escape_string($this->link, $value[1]);
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
                    $value = mysqli_real_escape_string($this->link, $value);
                    $value = is_numeric($value) ? $value : "'" . $value . "'";
                    $where .= $key . '=' . $value . ' AND ';
                }
            }
            return rtrim($where, '. AND ');
        }
        return $where . $whereData;
    }

    /**
     * 执行SQL
     * @param string $queryString
     * @param bool $select
     * @return $this|bool|\mysqli_result
     */
    public function query($queryString = '', $select = false)
    {
        if ($this->link != null) {
            $this->queryId = mysqli_query($this->link, $queryString);

            if ($this->queryId === false) {
                $status = 'error';
                $errormsg = mysqli_error($this->link);
            } else {
                $status = 'success';
            }
            $Logs = "[{$status}] " . $queryString;
            if (isset($errormsg)) {
                $Logs .= "\r\n[message] " . $errormsg;
            }
            \Framework\App::$app->get('Log')->Record(\Framework\Library\Process\Running::$framworkPath . '/Project/Runtime/Datebase', 'sql', $Logs);
            if ($this->queryId === false) {
                $message = $errormsg . ' (SQL：' . $queryString . ')';
                \Framework\App::$app->get('LogicExceptions')->readErrorFile([
                    'type' => 'DataBase Error',
                    'message' => $message
                ]);

            } else {
                if ($this->startsWith(strtolower($queryString), "select") && $select === false) {
                    $this->result = mysqli_fetch_all($this->queryId, MYSQLI_ASSOC);
                    return $this;
                }
            }
            return $this->queryId;
        } else {
            \Framework\App::$app->get('LogicExceptions')->readErrorFile([
                'message' => '数据库连接失败或尚未连接'
            ]);
        }
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
     * 返回影响记录
     * @return int
     */
    public function affectedRows()
    {
        return mysqli_affected_rows($this->link);
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
     * @return bool
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

            $queryString = 'INSERT INTO `' . $this->tableName . '` (' . $v_key . ') VALUES(' . $v_value . ');';

            $res = $this->query($queryString, true);

            $this->queryDebug = ['string' => $queryString, 'value' => $value, 'insertedid' => $this->insert_id()];

            return $res === false ? false : $this->queryDebug['insertedid'];
        }
        return false;
    }

    /**
     * 获取最后插入的ID
     * @return int|string
     */
    public function insert_id()
    {
        return mysqli_insert_id($this->link);
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
     * @param string $where
     * @return bool
     */
    public function update($dataArray = [], $where = '')
    {
        if (is_array($dataArray) && count($dataArray) > 0) {
            $updata = '';
            foreach ($dataArray as $key => $value) {
                $value = is_int($value) ? $value : "'{$value}'";
                $updata .= "`$key`={$value},";
            }
            if (!empty($where)) $where = $this->structureWhere($where);
            $queryString = 'UPDATE ' . $this->tableName . ' SET ' . rtrim($updata, '.,') . $where;

            $res = $this->query($queryString, true);

            $this->total = $this->affectedRows();

            $this->queryDebug = ['string' => $queryString, 'update' => $updata, 'affectedRows' => $this->total];

            return $res === false ? false : $this->total;
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
     * @param array $where
     * @return bool|int|mixed
     */
    public function delete($where = [])
    {
        if (!empty($where)) $where = $this->structureWhere($where);

        $queryString = 'DELETE FROM ' . $this->tableName . $where;

        $res = $this->query($queryString, true);

        $this->total = $this->affectedRows();

        $this->queryDebug = ['string' => $queryString, 'affectedRows' => $this->total];

        return $res === false ? false : $this->total;
    }

}
