<?php
use \Truesign\Adapter\wechat_marketing\siteBaseConfigAdapter;
use Truesign\Adapter\wechat_marketing\envAdapter;
use Royal\Data\DAO;

class SiteInfoController extends AppBaseController {


    public function indexAction()
    {
        echo '1231';
	}

    public function getOSSCfgAction()
    {
        $config = Yaf_Registry::get('config');
        $oss =   $config->get('cdn')->toArray();
        echo json_encode($oss);
    }
    public function getEnvAction()
    {
        $params = $this->getParams(array(),array('rules'));
        $helper = new \Royal\Util\helper();
        $doAdapter = new envAdapter();
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();
        $doDao = new DAO($doAdapter);
        $env = $helper::sysinfo();
        $data = [];
        foreach ($env as $k=>$v){
            if($k != 'IP'){
                $data['server_env'] = $data['server_env'].' '.$k.':'.$v;
            }
        }
        $data['server_ip'] = $env['IP'];
        $doDao->insertOrupdate($data,array('server_ip'=>$env['IP']));
        $db_resposne = $doDao->read(array('server_ip'=>$env['IP']),array(),array(),false,false,true);
        $this->filterRules($rules,$db_resposne['data'][0],$params['rules']);

        $access_rules = array('tableaccess'=>$table_access,'rules'=>$rules);
        $db_resposne['access_rules'] = $access_rules;
        $this->output2json($db_resposne);
	}

    public function descSiteBaseConfigInfoAction($rules = 0)
    {

        $params = $this->getParams(array(),array('rules'));
        if(empty($params['rules'])){
            $params['rules'] = $rules;
        }
        $doAdapter = new siteBaseConfigAdapter();
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();
        $doDao = new DAO($doAdapter);


        $db_resposne['statistic']['count'] = 1;
        $db_resposne['data'][] = array_flip($doDao->getColumn());
        unset($db_resposne['data'][0]['id']);
        unset($db_resposne['data'][0]['update_time']);
        unset($db_resposne['data'][0]['create_time']);
        unset($db_resposne['data'][0]['if_delete']);

//        $arr_tmp_id = array('document_id'=>'未生成');
//        $new_arr['statistic'] = $db_resposne['statistic'];
//        $new_arr['data'][0] = array_merge($arr_tmp_id,$db_resposne['data'][0]);


        foreach ($db_resposne['data'][0] as $k=>$v){
            $db_resposne['data'][0][$k] = '';
        }
//        $new_arr['data'][0]['document_id'] = '未定义';
        $this->filterRules($rules,$db_resposne['data'][0],$params['rules']);
        $access_rules = array('tableaccess'=>$table_access,'rules'=>$rules);
        $db_resposne['access_rules'] = $access_rules;
        $this->output2json($db_resposne);
	}
	public function getSiteBaseConfigAction(){

        $params = $this->getParams(array(),array('rules'));
        $doAdapter = new siteBaseConfigAdapter();
        $doDao = new DAO($doAdapter);
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();

        if($this->tableaccess_ctrl()){
            $access = $this->getAccess();
            $this->AuthTableAccess($access,$table_access);
        }
        $db_resposne = $doDao->readSpecified(array(),array());
        if(empty($db_resposne['statistic']['count'])){
            $this->descSiteBaseConfigInfoAction(1);
        }
        else{
            $this->filterRules($rules,$db_resposne['data'][0],$params['rules']);

            $access_rules = array('tableaccess'=>$table_access,'rules'=>$rules);
            $db_resposne['access_rules'] = $access_rules;
            $this->output2json($db_resposne);
        }

    }

    /*
     * sitebaseconfig adapter 数据更新接口
     */
    public function updateSiteBaseConfigAction()
    {
        $params = $_POST;
        $doAdapter = new siteBaseConfigAdapter();
        $doDao = new DAO($doAdapter);
        $condition['id'] = $params['document_id'];
        unset($params['document_id']);
        $db_response = $doDao->insertOrupdate($params,$condition);
        $this->output2json($db_response);
    }


}
