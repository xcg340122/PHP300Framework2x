<?php

namespace Framework\Library\Interfaces;

/**
 * 数据基础模型接口
 * Class Db
 */
interface DbInterface{

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError();

    /**
     * 连接数据库
     * @param array $config
     * @return mixed
     */
    public function connect($config = []);

    /**
     * 执行SQL
     * @param $queryString
     * @param $method
     * @return $select
     */
    public function query($queryString,$select);

    /**
     * 设置表
     * @param $tabName
     * @return mixed
     */
    public function table($tabName);

    /**
     * 查询数据
     * @param array $qryArray
     * @return mixed
     */
    public function select($qryArray = []);

    /**
     * 新增数据
     * @param array $dataArray
     * @param array $unique
     * @return mixed
     */
    public function insert($dataArray = []);

    /**
     * 修改数据
     * @param array $dataArray
     * @param $where
     * @param array $unique
     * @return mixed
     */
    public function  update($dataArray = [], $where);

    /**
     * 删除数据
     * @param $where
     * @return mixed
     */
    public function delete($where);

    /**
     * 打印调试信息
     * @return mixed
     */
    public function debug();

    /**
     * 获取单条记录
     * @return mixed
     */
    public function find();

    /**
     * 获取多条记录
     * @return mixed
     */
    public function get();

    /**
     * 获取影响的条数
     * @return mixed
     */
    public function total();
}