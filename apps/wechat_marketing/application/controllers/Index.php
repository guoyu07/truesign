<?php

class IndexController extends AppBaseController {

    public function indexAction()
    {
        echo '1231';
	}
	public function dodbAction(){
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\wechat_marketing\weimobAdapter());
        print_r($doDao->read(array(),array()));
    }

}
