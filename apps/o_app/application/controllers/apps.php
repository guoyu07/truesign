<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class appsController extends  oAppBaseController
{
    public function getAppRuleAction(){
        $apprule = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appRuleAdapter());
        $rules = $apprule->readSpecified(array(),array());
        $this->output2json($rules);
    }

    public function indexAction(){
        $call = $_GET['callback'];
        $data = array(
            'name'=>'iamsee111',
            'token'=>'1235',
        );
        $this->setBody($data);
    }

    public function index2Action()
    {
        
    }

}