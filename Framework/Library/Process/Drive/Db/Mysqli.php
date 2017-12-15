<?php

namespace Framework\Library\Process\Drive\Db;

use \Framework\Library\Process\Auxiliary;
use \Framework\Library\Interfaces\DbInterface as DbInterfaces;
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
        if(is_resource($this->link)){
            return mysqli_error($this->link);
        }
    }

    /**
     * 连接数据库
     * @param array $config
     * @return \mysqli|null
     */
    public function connect($config = [])
    {
        $this->link = @mysqli_connect($config['host'], $config['username'], $config['password'], $config['database'],$config['port']);
        if ($this->link != null) {
            mysqli_query($this->link, 'set names '.$config['char']);
            return $this->link;
        } else {
            $error = [
                'file' => __FILE__,
                'message' => 'Mysql Host[ ' . $config['host'] . ' ] :: ' . Auxiliary::toUTF8(mysqli_connect_error()),
                'line' => 69,
            ];
            \Framework\App::$app->get('LogicExceptions')->readErrorFile($error);
        }
    }

    /**
     * 操作多数据库连接
     * @param $link
     */
    public function setlink($link){
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
     * @param string $sql
     * @return mixed
     */
    public function query($queryString = '',$method = null,$select=false)
    {
        if ($this->link != null) {
            $this->queryId = mysqli_query($this->link, $queryString);
            $status = $this->queryId !== false ? 'success' : 'error';
            \Framework\App::$app->get('Log')->Record(\Framework\Library\Process\Running::$framworkPath .'/Project/Runtime/datebase','sql',"[{$status}] ".$queryString);
            if ($this->startsWith(strtolower($queryString), "select") && $select===false) {
                $this->result = mysqli_fetch_all($this->queryId,MYSQLI_ASSOC);
                return $this;
            }
            return $this->queryId;
        } else {
            exit('数据库连接失败或尚未连接!');
        }
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
     * 返回影响记录
     * @return int
     */
    public function affectedRows()
    {
        return mysqli_affected_rows($this->link);
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
     * 设定查询表
     * @param $tabName
     * @return $this
     */
    public function table($tabName)
    {
        if (!empty($tabName)) {
            $this->tableName = $tabName;
        }
        return $this;
    }

    /**
     * 插入数据
     * @param array $qryArray
     * @return $this
     */
    public function select($qryArray = [])
    {
        $fetchFields = (isset($qryArray['field']) && count($qryArray['field'])>0) ? implode(',',$qryArray['field']): '*';

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

        $STORE = MYSQLI_ASSOC;

        if(isset($qryArray['method']) && $qryArray['method']!=NULL) {
            $STORE = $qryArray['method'];
        }

        $res = $this->query($qryStr,'',true);

        if($res){
            $this->result = mysqli_fetch_all($res,$STORE);
        }

        $this->total = $this->affectedRows();

        $this->queryDebug = ['string' => $qryStr, 'value' => NULL, 'method' => (isset($qryArray['method']) ? $qryArray['method'] : $STORE)];

        return $this;
    }

    /**
     * 插入数据
     * @param array $dataArray
     * @param array $unique
     * @return array
     */
    public function insert($dataArray = [], $unique = []){
        $fields = [];
        $executeArray = [];
        $duplicate = false;
        $values = [];

        foreach($dataArray as $key=>$val){
            $fields[] = "`{$key}`";
            $values[] = "'{$val}'";
        }

        $rawFieldsStr = implode(',', $fields);
        $valuesStr = implode(',',$values);

        if( count($unique) > 0 ){
            $condition = array();
            foreach($unique as $fieldName){
                $condition[] = $fieldName." = '".$dataArray[$fieldName]."' ";
            }

            $cQryStr = "SELECT ".$unique[0]." FROM ".$this->tableName." WHERE ".implode('AND ',$condition);
            $cQry = $this->query($cQryStr);

            if( $this->affectedRows() > 0 ) $duplicate = true;
            else $duplicate = false;
        }

        $affectedRow = 0;
        $lastInsertedId = 0;

        if(!$duplicate) {
            $qryStr = 'INSERT INTO '.$this->tableName.' ('.$rawFieldsStr.') VALUES('.$valuesStr.')';

            $this->query($qryStr);

            $affectedRow = $this->affectedRows();

            $lastInsertedId = $this->insert_id();

            $this->queryDebug = ['string' => $qryStr, 'value' => $valuesStr, 'method' => false];
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
    public function update($dataArray = [], $where, $unique = []){
        $tableName = $this->tableName;

        $fields = [];

        foreach($dataArray as $key=>$val){
            $fields[] = "`{$key}` = '{$val}'";
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

            $cQry = $this->query($cQryStr);

            if( $this->affectedRows() > 0 ) $duplicate = true;
            else $duplicate = false;
        }
        else {
            $duplicate = false;
        }

        $affectedRow = 0;

        if(!$duplicate && !empty($where)) {

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

            $this->query($qryStr);

            $affectedRow = $this->affectedRows();

            $this->queryDebug = ['string' => $qryStr, 'value' => $fields_str, 'method' => false];
        }
        else {
            $this->queryDebug = ['string' => $cQryStr, 'value' => NULL];
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
    public function delete($where){
        $tableName = $this->tableName;

        $affectedRow = 0;
        if($where!=NULL || (is_array($where) && count($where)) > 0 ){
            if(is_array($where)) {
                $affectedTo = array();
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
                $whereCond = 'WHERE '.implode(" AND ", $affectedTo);
            }
            else {
                $whereCond = 'WHERE '.$where;
            }

            $qryStr = 'DELETE FROM '.$tableName.' '.$whereCond;

            $this->query($qryStr);

            $affectedRow = $this->affectedRows();

            $this->queryDebug = ['string' => $qryStr, 'value' => NULL, 'method' => false];
        }

        return [
            'affected_row' => $affectedRow
        ];
    }

}
