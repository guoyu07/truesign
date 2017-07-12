<?php

class IndexController extends AppBaseController {

    public function indexAction()
    {
        echo json_encode(array(1=>'oss',2=>'本地'));
	}
	public function dodbAction(){
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\wechat_marketing\weimobAdapter());
        print_r($doDao->read(array(),array()));
    }

    public function curlAction()
    {
        $params = $this->getParams(array(),array('uri'));
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $params['uri']);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        echo "<pre>";
        echo curl_error($ch);
        //echo output
        echo $output;

        // close curl resource to free up system resources
        curl_close($ch);
    }

}
