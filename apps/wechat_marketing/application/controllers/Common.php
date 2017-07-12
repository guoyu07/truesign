<?php
use Browser\Casper;
class CommonController extends AppBaseController {

    public function indexAction()
    {
        $debug_backtrace = \Royal\Prof\TrueSignConst::GET_DEBUG_BACKTRACE();
        echo json_encode($debug_backtrace);
    }
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

    public function testaddAction()
    {
        $params = $this->getParams(array('age','name'));
        $doservices = new \Truesign\Service\Wechat_marketing_service\testService();
        echo $doservices->add($params);
	}
	public function casperjsAction(){
        $casper = new Casper();

// forward options to phantomJS
// for example to ignore ssl errors
        $casper->setOptions([
            'ignore-ssl-errors' => 'yes'
        ]);

// navigate to google web page
        $casper->start('https://item.taobao.com/item.htm?spm=a21ig.146272.757693.1.1a44f7bREiEOT&id=543589675746');

// fill the search form and submit it with input's name

// check the urls casper get through
        var_dump($casper->getRequestedUrls());

// need to debug? just check the casper output
        var_dump($casper->getOutput());
    }

    public function logAction()
    {
        $f = fopen('post.log','w') or die('Unable to open file!');
        fwrite($f,date('Y-m-d H:i:s').PHP_EOL);
        fwrite($f,json_encode(array_merge($_POST,$_GET),256));
        fwrite($f,PHP_EOL);
        fclose($f);
        echo 'done';
    }



}
