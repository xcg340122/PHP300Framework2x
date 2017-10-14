<?php
namespace App\Home;

use Framework\Library\Process\Auxiliary;
class  Index {

    public function index()
    {


        $arr =  ['code' => 1001 , 'message' => 'hello world','_methon' => 'json'];

        return $arr;
    }


    public function find(){
//        $res = Db('php300')->table('db')->select([
//            'field' => ['Host','Db'],
//            'condition' => 'where Host = \'%\'',
//            'limit' => '0,10',
//            'orderby' => 'Db',
//        ])->get();
//        $res = Db('php300')->table('iq_dynamic')->insert(
//            ['from_id' => '12', 'image' => 'john@email.com' , 'created_at' => time() , 'updated_at' => time()]
//        );
//        $res = Db('php300')->table('iq_dynamic')->delete(['Id' => 80]);
//        $res = Db('php300')->table('iq_dynamic')->update(['from_id' => 66],['Id' => 81]);
//        var_dump($res);
        Auxiliary::URL(['Admin','index','?id=2']);
        return ['name' => 'joins' , '_methon' => 'json'];
    }

    public function get(){
        echo '获取到ID：'.$_GET['id'];
    }
}