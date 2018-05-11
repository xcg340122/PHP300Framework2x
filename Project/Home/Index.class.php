<?php
namespace App\Home;

use Framework\Library\Process\Auxiliary;

class  Index {

    public function index()
    {
        $View = View('Home/index');

        $View->public = Auxiliary::getPublic();

        $View->show  = 'PHP300Framework - 想象无极限';

        $View->version = '2.2.1';

        return $View->get();
    }
}