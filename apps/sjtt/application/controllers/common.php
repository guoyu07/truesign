<?php

class commonController extends AppBaseController {

	public function updateimg2ossByClientAction() {
        $params = $this->getParams(array('filename','type'));
        $pre_data  = \Royal\Aliyun\Aliyun::AppSign($params['type'].'/'.$params['filename'],array(),'/common/cb_updateimg2ossByClient');
        if(!empty($param['nowater']))
        {
            $pre_data['nowater']=true;
        }
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

}
