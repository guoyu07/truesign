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
    /*
     * @for 获取客户信息接口
     *
     */
    public function getBusinessInfoAction()
    {
        $params = $this->getParams(array(),array('rules','document_id','search_sort_by'));
        if(!empty($params['search_sort_by'])){
            $search_sort_by = json_decode($params['search_sort_by'],true);
            $page_param['page_size'] = $search_sort_by['page_size'];
            $page_param['page'] = $search_sort_by['page'];
            unset($search_sort_by['page_size']);
            unset($search_sort_by['page']);
        }
        else{
            $page_param = array();
        }
        if($params['document_id']){
            $search_param = array('document_id'=>$params['document_id']);

        }
        else{
            $search_param = array();
        }

        foreach ($search_sort_by as $k=>$v){
            if(empty($v)){
                unset($search_sort_by);
            }
            else{
                self::setParam($k,'prefix',$v,$search_param);

            }

        }

        $doAdapter = new businessAdapter();
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();
        $doDao = new DAO($doAdapter);


        $db_resposne = $doDao->read($search_param,$page_param);

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

    public function GroupDelBusinessInfoAction()
    {
        $params = $this->getParams(array('ids'));

        if(!empty($params['ids'])){
            $params_ids = explode(',',$params['ids']);
        }
        else{
            $params_ids = array();
        }
        $doAdapter = new businessAdapter();
        $doDao = new DAO($doAdapter);
        $updatedata = [];
        foreach ($params_ids as $k=>$v){
            $updatedata_item['id'] = $v;
            $updatedata_item['if_delete'] = 1;
            $updatedata[] = $updatedata_item;
        }

        $db_reponse = $doDao->groupUpdate($params['ids'],$updatedata,'if_delete');
        echo json_encode($db_reponse);
        exit();

    }


}
