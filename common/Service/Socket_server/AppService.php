<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 15:52
 */

namespace Truesign\Service\Socket_server;


use EasyWeChat\Core\Exception;
use Royal\Crypt\Decrypt;
use Royal\Data\DAO;
use Royal\Prof\TrueSignConst;
use Truesign\Adapter\Apps\appCtrlLevelAdapter;
use Truesign\Adapter\wechat_marketing\masterAdapter;

class AppService extends BaseService
{
    private $Adapter;
    private $Dao;
    private $tableAccess;
    private $rules;

    public function __construct()
    {
        $this->Adapter = new \Truesign\Adapter\WebsocketServer\appRuleAdapter();
        $this->Dao = new DAO($this->Adapter);
        $this->tableAccess = $this->Adapter->getTableAccess();
        $this->rules = $this->Adapter->paramRules();
        if($this->tableaccess_ctrl()){
            $access = $this->getAccess();
            $this->AuthTableAccess($access,$this->tableAccess);
        }
    }


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

        $this->filterRules($this->rules,$db_resposne['data'],$params['rules']);
        $access_rules = array('tableaccess'=>$this->tableAccess,'rules'=>$this->rules);
        $db_resposne['access_rules'] = $access_rules;
        return $db_resposne;
    }

    public function Get($params=array(),$search_params=array(),$page_params=array(),$sorter=array(),$need_params=array())
    {
        $db_resposne = $this->Dao->readSpecified($search_params,$need_params,$page_params,$sorter);
        $this->filterRules($this->rules,$db_resposne['data'],$params['rules']);
        $access_rules = array('tableaccess'=>$this->tableAccess,'rules'=>$this->rules);
        $db_resposne['access_rules'] = $access_rules;

        return $db_resposne;
    }

    public function Update($params=array(),$search_params=array(),$page_params=array()){

        $search_params['id'] = $params['document_id'];
        unset($search_params['document_id']);
//        $db_response = $this->Dao->get(array('id'=>$search_params['id']),array('password'));
//        if($params['password']  == $db_response['password']){
//            $db_response = 0;
//            $db_response = $this->Dao->insertOrupdate($params,$search_params);
//            if($db_response > 0){
//                $response = TrueSignConst::SUCCESS('更新成功');
//            }
//            else{
//                $response = TrueSignConst::ERROR('更新失败');
//            }
//        }
//        else{
//            $response = TrueSignConst::OPERATION_lOGIC_ERR('密码错误，无法更新数据');
//        }
        $db_response = $this->Dao->insertOrupdate($params,$search_params);
        if($db_response > 0){
            $response = TrueSignConst::SUCCESS('更新成功');
        }
        else{
            $response = TrueSignConst::ERROR('更新失败');
        }
        return $response;

    }



}