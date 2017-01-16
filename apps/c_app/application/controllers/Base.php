<?php

use ReInit\YafBase\Controller;

class BaseController extends Controller
{

    public function init()
    {
        $this->doInit();
    }

    public function doInit()
    {
    }
    public function result2JSONP($call,$data){
        $json = json_encode(['data'=>$data]);
        echo $call.'('.$json.')';
    }





}
