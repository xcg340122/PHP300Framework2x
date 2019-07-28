<?php

namespace Framework\Library\Interfaces;
use Framework\Library\Process\Db;

/**
 * 数据基础模型接口
 * Interface DbInterface
 * @package Framework\Library\Interfaces
 */
interface DbInterface
{

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError();

    /**
     * 连接数据库
     * @param array $config 配置信息
     * @return mixed
     */
    public function connect($config = []);

    /**
     * 执行SQL
     * @param string $queryString 执行的SQL语句
     * @param bool $select 是否系统查询
     * @return mixed|self
     */
    public function query($queryString, $select = false);

    /**
     * 设置表
     * @param string $tabName 表名称
     * @return self
     */
    public function table($tabName);

    /**
     * 查询数据
     * @param array $qryArray 条件信息
     * @return self
     */
    public function select($qryArray = []);

    /**
     * 新增数据
     * @param array $dataArray 插入的数据
     * @return bool|int
     */
    public function insert($dataArray = []);

    /**
     * 新增数据
     * @param array $dataArray 插入的数据
     * @return bool|int
     */
    public function add($dataArray = []);

    /**
     * 修改数据
     * @param array $dataArray 修改的数据
     * @param array|string $where 修改条件
     * @return mixed|bool|int
     */
    public function update($dataArray = [], $where = []);

    /**
     * 修改数据
     * @param array $dataArray 修改的数据
     * @param array|string $where 修改条件
     * @return mixed|int
     */
    public function save($dataArray = [], $where = []);

    /**
     * 删除数据
     * @param array|string $where 删除的条件
     * @return bool
     */
    public function delete($where = []);

    /**
     * 删除数据
     * @param array|string $where 删除的条件
     * @return bool
     */
    public function del($where = []);

    /**
     * 打印调试信息
     * @return mixed|array
     */
    public function debug();

    /**
     * 获取单条记录
     * @return mixed|array
     */
    public function find();

    /**
     * 获取多条记录
     * @return mixed|array
     */
    public function get();

    /**
     * 获取影响的条数
     * @return int
     */
    public function total();
}