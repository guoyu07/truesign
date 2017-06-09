<?php

class SiteInfoController extends AppBaseController {

    public function indexAction()
    {
        echo '1231';
	}
	public function getBaseInfoAction(){
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\wechat_marketing\weimobAdapter());
        print_r($doDao->read(array(),array()));
    }

}
