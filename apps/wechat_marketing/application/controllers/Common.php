<?php

class CommonController extends AppBaseController {

	public function updateimg2ossByClientAction() {
        $params = $this->getParams(array('filename','type'));
        $pre_data  = \Royal\Aliyun\Aliyun::AppSign($params['type'].'/'.$params['filename'],array(),'/common/cb_updateimg2ossByClient');
        if(!empty($param['nowater']))
        {
            $pre_data['nowater']=true;
        }
        $pre_data['status'] = 1;
        $pre_data['sys_msg'] = 'oss';
        $this->output2json($pre_data);

	}
    public function updateimg2ossByClientOnEditorAction() {
	    $type = 'editor';
        $fileExt = $_POST['fileExt'];
        $filename = md5(time() . mt_rand(1,1000000)).'.'.$fileExt;
        $pre_data  = \Royal\Aliyun\Aliyun::AppSign($type.'/'.$filename,array(),'/common/cb_updateimg2ossByClient');
        if(!empty($param['nowater']))
        {
            $pre_data['nowater']=true;
        }
        $pre_data['status'] = 1;
        $pre_data['sys_msg'] = 'oss';
        $this->output2json($pre_data);

    }

    public function cb_updateimg2ossByClientAction()
    {
//        $params = $this->getParamsAll();
        $params = $this->getParams(array('file_name'));
        $config = \Yaf_Registry::get('config');
        $callConfig = $config->cdn;
        $host = $callConfig['BucketHost'];
        $cb_response = [];
        if(!empty($params['file_name'])){
            $cb_response['file_path'] = $host.'/'.$params['file_name'];
        }
        $this->output2json($cb_response);
	}

    public function sendSmsAction()
    {

        $params = $this->getParams(array('phone'),array('source'));
        $SMS = new \Truesign\Service\Sms\EallSms();
        $sms_code = rand(100000, 999999);
        $sms_response = $SMS->smsSend('business_regeister',$params['phone'],$sms_code);
        var_dump($sms_response);

	}

}
