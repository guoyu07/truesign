<?php
use \Truesign\Adapter\wechat_marketing\siteBaseConfigAdapter;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Royal\Data\DAO;

class BusinessCtrlController extends AppBaseController {


    public function indexAction()
    {
        echo '客户数据';
	}

    public function GetBusinessColsInfoAction()
    {
        $params = $this->getParams(array(),array('rules'));
        $doAdapter = new businessAdapter();
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();
        $doDao = new DAO($doAdapter);


        $db_resposne['statistic']['count'] = 1;
        $db_resposne['data'][] = array_flip($doDao->getColumn());
        unset($db_resposne['data'][0]['id']);
        unset($db_resposne['data'][0]['update_time']);
        unset($db_resposne['data'][0]['create_time']);
        unset($db_resposne['data'][0]['if_delete']);
        foreach ($db_resposne['data'][0] as $k=>$v){
            $db_resposne['data'][0][$k] = '';
        }

        $this->filterRules($rules,$db_resposne['data'][0],$params['rules']);
        $access_rules = array('tableaccess'=>$table_access,'rules'=>$rules);
        $db_resposne['access_rules'] = $access_rules;
        $this->output2json($db_resposne);
	}
    public function getBusinessInfoAction()
    {
        $params = $this->getParams(array(),array('rules','document_id'));
        $doAdapter = new businessAdapter();
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();
        $doDao = new DAO($doAdapter);
        if($params['document_id']){
            $search_param = array('document_id'=>$params['document_id']);

        }
        else{
            $search_param = array();
        }
        $db_resposne = $doDao->read($search_param);

        $this->filterRules($rules,$db_resposne['data'][0],$params['rules']);

        $access_rules = array('tableaccess'=>$table_access,'rules'=>$rules);
        $db_resposne['access_rules'] = $access_rules;
        $this->output2json($db_resposne);
    }

    public function UpdateBusinessInfoAction(){
        $params = $_POST;
        $doAdapter = new businessAdapter();
        $doDao = new DAO($doAdapter);
        $condition['id'] = $params['document_id'];
        unset($params['document_id']);
        $db_response = $doDao->insertOrupdate($params,$condition);
        $this->output2json($db_response);
    }


}
