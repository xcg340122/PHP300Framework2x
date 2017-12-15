<?php

namespace Framework\Library\Process\Drive\Db;

use \Framework\Library\Interfaces\DbInterface as DbInterfaces;
/**
 * PDO Driver
 * Class PDO
 */
class Pdo implements DbInterfaces
{

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
     * 数据错误信息
     * @var string
     */
    public $dbErrorMsg = 'We are currently experiencing technical difficulties. Need check back soon: ';

    /**
     * 操作表名
     * @var null
     */
    protected $tableName = NULL;

    /**
     * 获取方式
     * @var int
     */
    public $fetchMethod = \PDO::FETCH_OBJ;

    /**
     * debug
     * @var array
     */
    protected $queryDebug = [];

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

            $dsn = !empty($config['dsn']) ? $config['dsn'] : "mysql:host=" . $config['host'] . ";port=".$config['port'].";dbname=" . $config['database'];
            $opt = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '".$config['char']."'"
            );

            $this->pdo = new \PDO($dsn, $config['username'], $config['password'], $opt);

            return $this->pdo;
        } catch (PDOException $ex) {
            exit($this->dbErrorMsg . $ex->getMessage());
        }
    }

    /**
     * 操作多数据库连接
     * @param $link
     */
    public function setlink($link){
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
     * 执行SQL
     * @param $queryString
     * @param null $method
     * @return $this
     */
    public function query($queryString, $method = NULL)
    {
        if (!$method) {
            $method = $this->fetchMethod;
        }

        try {
            $qry = $this->pdo->prepare($queryString);
            $qry->execute();
            $qry->setFetchMode($method);

            if ($this->startsWith(strtolower($queryString), "select")) {
                $this->result = $qry->fetchAll();
            }

            $this->total = $qry->rowCount();

            $this->queryDebug = ['string' => $queryString, 'value' => NULL, 'method' => $method];

        } catch (PDOException $ex) {
            echo $this->dbErrorMsg . $ex->getMessage();
            exit();
        }

        return $this;
    }


    /**
     * 选择数据表
     * @param $tableName
     * @return $this
     */
    public function table($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }


    /**
     * 查询数据
     * @param array $qryArray
     * @return $this
     */
    public function select($qryArray = [])
    {
        $fetchFields = (isset($qryArray['field']) && count($qryArray['field'])>0) ? implode(', ',$qryArray['field']): '*';

        $qryStr = 'SELECT '.$fetchFields.' FROM `'.$this->tableName.'` '.((isset($qryArray['condition']) && $qryArray['condition']!=NULL)?$qryArray['condition']:'');

        if(isset($qryArray['groupby']) && $qryArray['groupby'] != NULL) {
            $qryStr .= ' GROUP BY '.$qryArray['groupby'];
        }

        if(isset($qryArray['orderby']) && $qryArray['orderby'] != NULL) {
            $qryStr .= ' ORDER BY '.$qryArray['orderby'];
        }

        if(isset($qryArray['limit']) && $qryArray['limit'] != NULL) {
            $qryStr .= ' LIMIT '.$qryArray['limit'];
        }

        try {
            $qry = $this->pdo->prepare($qryStr);
            $qry->execute();

            if(isset($qryArray['method']) && $qryArray['method']!=NULL) {
                $qry->setFetchMode($qryArray['method']);
            }
            else {
                $qry->setFetchMode($this->fetchMethod);
            }

            $this->result = $qry->fetchAll();

            $this->total = $qry->rowCount();

            $this->queryDebug = ['string' => $qryStr, 'value' => NULL, 'method' => (isset($qryArray['method']) ? $qryArray['method'] : $this->fetchMethod)];
        }
        catch (PDOException $ex){
            exit($this->dbErrorMsg . $ex->getMessage(). ' query: '.$qryStr);
        }
        return $this;
    }


    /**
     * 插入数据
     * @param array $dataArray
     * @param array $unique
     * @return array
     */
    public function insert($dataArray = [], $unique = [])
    {
        $fields = [];
        $executeArray = [];
        $duplicate = false;

        foreach($dataArray as $key=>$val){
            $fields[] = ':'.$key;
            $executeArray[':'.$key] = $val;

        }

        $fields_str = implode(',',$fields);
        $rawFieldsStr = implode(',', str_replace(':','',$fields));

        if( count($unique) > 0 ){
            $condition = array();
            foreach($unique as $fieldName){
                $condition[] = $fieldName." = '".$dataArray[$fieldName]."' ";
            }

            $cQryStr = "SELECT ".$unique[0]." FROM ".$this->tableName." WHERE ".implode('AND ',$condition);
            $cQry = $this->pdo->query($cQryStr);

            if( $cQry->rowCount() > 0 ) $duplicate = true;
            else $duplicate = false;
        }

        $affectedRow = 0;
        $lastInsertedId = 0;

        if(!$duplicate) {
            $qryStr = 'INSERT INTO '.$this->tableName.' ('.$rawFieldsStr.') VALUES('.$fields_str.')';

            try {
                $qry = $this->pdo->prepare($qryStr);
                $qry->execute($executeArray);

                $affectedRow = $qry->rowCount();

                $lastInsertedId = $this->pdo->lastInsertId();

                $this->queryDebug = ['string' => $qryStr, 'value' => $executeArray, 'method' => false];
            }
            catch (PDOException $ex){
                exit($this->dbErrorMsg . $ex->getMessage());
            }
        }

        return [
            'affected_row' => $affectedRow,
            'inserted_id' => $lastInsertedId,
            'is_duplicate' => (bool) $duplicate
        ];
    }

    /**
     * 修改数据
     * @param array $dataArray
     * @param $where
     * @param array $unique
     * @return array
     */
    public function update($dataArray = [], $where = [], $unique = [])
    {
        $cQryStr = '';
        $tableName = $this->tableName;

        $fields = [];
        $executeArray = [];

        foreach($dataArray as $key=>$val){
            $fields[] = $key.' = :'.$key;
            $executeArray[':'.$key] = $val;

        }

        $fields_str = implode(', ',$fields);

        if( count($unique) > 0 ){
            $condition = [];

            foreach($unique as $fieldName){
                $condition[] = $fieldName." = '".$dataArray[$fieldName]."' ";
            }

            $extendedCondition = [];

            if( is_array($where) && count($where) > 0 ){
                foreach($where as $whereKey=>$whereVal){
                    $extendedCondition[] = $whereKey." != '".$whereVal."' ";
                }
            }

            $cQryStr = "SELECT ".$unique[0]." FROM ".$tableName." WHERE ".implode('AND ',$condition);
            if( count($extendedCondition) > 0 ) $cQryStr .= "AND ".implode('AND ', $extendedCondition);

            $cQry = $this->pdo->query($cQryStr);

            if( $cQry->rowCount() > 0 ) $duplicate = true;
            else $duplicate = false;
        }
        else {
            $duplicate = false;
        }

        $affectedRow = 0;

        if(!$duplicate && !empty($where) ) {

            if(is_array($where)) {
                $affectedTo = [];

                foreach($where as $key=>$val){
                    if(is_array($val) && count($val) > 1) {
                        if(trim(strtolower($val[0])) == 'in'){
                            $affectedTo[] = $key." in($val[1]) ";
                        }else{
                            $affectedTo[] = $key." {$val[0]} '".$val[1]."'";
                        }
                    }else {
                        $affectedTo[] = $key." = '".$val."'";
                    }
                }

                $whereCond = ' WHERE '.implode(" AND ", $affectedTo);
            }
            else {
                $whereCond = ' WHERE '.$where;
            }

            $qryStr = 'UPDATE '.$tableName.' SET '.$fields_str.$whereCond;

            try {
                $qry = $this->pdo->prepare($qryStr);

                $qry->execute($executeArray);

                $affectedRow = $qry->rowCount();

                $this->queryDebug = ['string' => $qryStr, 'value' => $executeArray, 'method' => false];

            }
            catch (PDOException $ex){
                echo $this->dbErrorMsg . $ex->getMessage();
                exit();
            }
        }
        else {
            $this->queryDebug = ['string' => $cQryStr, 'value' => NULL, 'method' => $this->fetchMethod];
        }

        return [
            'affected_row' => $affectedRow,
            'is_duplicate' => (bool) $duplicate
        ];
    }


    /**
     * 删除数据
     * @param $where
     * @return array
     */
    public function delete($where)
    {
        $tableName = $this->tableName;

        $affectedRow = 0;
        if($where!=NULL || (is_array($where) && count($where)) > 0 ){
            if(is_array($where)) {
                $affectedTo = array();
                foreach($where as $key=>$val){
                    $affectedTo[] = $key." = '".$val."'";
                }
                $whereCond = 'WHERE '.implode(" AND ", $affectedTo);
            }
            else {
                $whereCond = 'WHERE '.$where;
            }

            $qryStr = 'DELETE FROM '.$tableName.' '.$whereCond;

            try {
                $qry = $this->pdo->prepare($qryStr);
                $qry->execute();

                $affectedRow = $qry->rowCount();

                $this->queryDebug = ['string' => $qryStr, 'value' => NULL, 'method' => false];

            }
            catch (PDOException $ex){
                exit($this->dbErrorMsg . $ex->getMessage());
            }
        }

        return [
            'affected_row' => $affectedRow
        ];
    }

}
