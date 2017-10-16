<?php
namespace App\Home;

class  Index {

    public function index()
    {
        $FrameworkInfo =  ['version' => '2.0' , 'message' => '更专业,更高效,全面提高产品质量!' , '_methon' => 'json'];

        return $FrameworkInfo;
    }
}