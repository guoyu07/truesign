<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class ChaosController extends  oAppBaseController
{
    public function wserverAction(){
        $baseid = session_create_id();
        $this->setResponseBody(['baseid'=>$baseid]);
    }





}