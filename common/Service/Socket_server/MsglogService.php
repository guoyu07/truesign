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

class MsglogService extends BaseService
{
    private $Adapter;
    private $Dao;
    private $tableAccess;
    private $rules;

    public function __construct()
    {
        $this->Adapter = new \Truesign\Adapter\WebsocketServer\appAuthLogAdapter();
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

    public function Get($params=array(),$search_params=array(),$page_params=array(),$sorter=array())
    {
        $db_resposne = $this->Dao->readSpecified($search_params,array(),$page_params,$sorter);
        $this->filterRules($this->rules,$db_resposne['data'],$params['rules']);
        $access_rules = array('tableaccess'=>$this->tableAccess,'rules'=>$this->rules);
        $db_resposne['access_rules'] = $access_rules;

        return $db_resposne;
    }





}