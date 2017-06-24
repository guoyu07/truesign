<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 15:52
 */

namespace Truesign\Service\Wechat_marketing_service;


use Royal\Data\DAO;
use Truesign\Adapter\wechat_marketing\businessAdapter;

class BusinessService extends BaseService
{
    private $Adapter;
    private $Dao;
    private $tableAccess;
    private $rules;

    public function __construct()
    {
        $this->Adapter = new businessAdapter();
        $this->Dao = new DAO($this->Adapter);
        $this->tableAccess = $this->Adapter->getTableAccess();
        $this->rules = $this->Adapter->paramRules();
        if($this->tableaccess_ctrl()){
            $access = $this->getAccess();
            $this->AuthTableAccess($access,$this->tableAccess);
        }
    }

    /*
	 * @for客户信息新增字段获取接口
	 */
    public function Desc($params=array(),$search_params=array(),$page_params=array())
    {



        $db_resposne['statistic']['count'] = 1;
        $db_resposne['data'][] = array_flip($this->Dao->getColumn());
        unset($db_resposne['data'][0]['id']);
        unset($db_resposne['data'][0]['update_time']);
        unset($db_resposne['data'][0]['create_time']);
        unset($db_resposne['data'][0]['if_delete']);
        foreach ($db_resposne['data'][0] as $k=>$v){
            $db_resposne['data'][0][$k] = '';
        }

        $this->filterRules($this->rules,$db_resposne['data'][0],$params['rules']);
        $access_rules = array('tableaccess'=>$this->tableAccess,'rules'=>$this->rules);
        $db_resposne['access_rules'] = $access_rules;
        return $db_resposne;
    }
    /*
     * @for 获取客户信息接口
     *
     */
    public function Get($params=array(),$search_params=array(),$page_params=array(),$sorter=array())
    {

        $db_resposne = $this->Dao->readSpecified($search_params,array(),$page_params,$sorter);

        $this->filterRules($this->rules,$db_resposne['data'][0],$params['rules']);
        $access_rules = array('tableaccess'=>$this->tableAccess,'rules'=>$this->rules);
        $db_resposne['access_rules'] = $access_rules;
        echo json_encode($db_resposne);
        exit();
        return $db_resposne;


    }
    /*
         * @for 客户信息更新、软删除接口
         *
         */
    public function Update($params=array(),$search_params=array(),$page_params=array()){

        $search_params['id'] = $params['document_id'];
        unset($search_params['document_id']);
        $db_response = $this->Dao->insertOrupdate($params,$search_params);
        return $db_response;
    }

    /*
     * @for 客户信息批量软删除接口
     */
    public function GroupDel($params=array())
    {

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
        return $db_reponse;

    }

}