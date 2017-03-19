<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class WSserverController extends  oAppBaseController
{
    public function initConnAction(){
        $params = $this->getParams(array('unique_timestamp_code','ua','ip'));




        $this->setResponseBody($params);
    }





}