<?php
use \Truesign\Adapter\wechat_marketing\siteBaseConfigAdapter;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Royal\Data\DAO;

class BusinessCtrlController extends AppBaseController {


    public function indexAction()
    {
        echo '客户数据';
	}

    public function getBusinessInfoAction()
    {
        $params = $this->getParams(array(),array('rules'));
        $doAdapter = new businessAdapter();
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();
        $doDao = new DAO($doAdapter);

        $db_resposne = $doDao->read();
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
