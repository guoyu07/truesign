<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class apiController extends  OAppBaseController
{
    public function init()
    {
//        $service = new Yar_Server(new $this());
//        $service->handle();
    }
    /**
     * the doc info will be generated automatically into service info page.
     * @params app_name,
     * @return app_rules
     */
    public function getAppRuleAction(){
        $params = $this->getParams(array('app_name'));
        $apprule = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appRuleAdapter());
        $rules = $apprule->readSpecified($params,array());

        print_r($rules);
    }




}
