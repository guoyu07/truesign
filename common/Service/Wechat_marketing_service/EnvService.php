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
use Truesign\Adapter\wechat_marketing\envAdapter;

class EnvService extends BaseService
{
    private $Adapter;
    private $Dao;
    private $tableAccess;
    private $rules;

    public function __construct()
    {
        $this->Adapter = new envAdapter();
        $this->Dao = new DAO($this->Adapter);
        $this->tableAccess = $this->Adapter->getTableAccess();
        $this->rules = $this->Adapter->paramRules();
        if($this->tableaccess_ctrl()){
            $access = $this->getAccess();
            $this->AuthTableAccess($access,$this->tableAccess);
        }
    }

    /*
	 * @for
	 */
    public function Desc($params=array(),$search_params=array(),$page_params=array())
    {




    }
    /*
     * @for 获取客户信息接口
     *
     */
    public function Get($params=array(),$search_params=array(),$page_params=array(),$sorter=array())
    {


        $helper = new \Royal\Util\helper();
        $table_access = $this->tableAccess;
        $rules = $this->rules;
        $env = $helper::sysinfo();
        $data = [];
        foreach ($env as $k=>$v){
            if($k != 'IP'){
                $data['server_env'] = $data['server_env'].' '.$k.':'.$v;
            }
        }
        $data['server_ip'] = $env['IP'];
        if(!empty($params['server_help'])){
            $data['server_help'] = $params['server_help'];
        }


        $this->Dao->insertOrupdate($data,array('server_ip'=>$env['IP']));

        $db_resposne = $this->Dao->read(array('server_ip'=>$env['IP']),array(),array(),false,false,true);
        $this->filterRules($rules,$db_resposne['data'][0],$params['rules']);

        $access_rules = array('tableaccess'=>$table_access,'rules'=>$rules);
        $db_resposne['access_rules'] = $access_rules;
        return $db_resposne;

    }


}