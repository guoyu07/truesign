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

class AuthlogService extends BaseService
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
        if ($this->tableaccess_ctrl()) {
            $access = $this->getAccess();
            $this->AuthTableAccess($access, $this->tableAccess);
        }
    }


    public function Desc($params = array(), $search_params = array(), $page_params = array())
    {
        $db_resposne['statistic']['count'] = 1;
        $db_resposne['data'][] = array_flip($this->Dao->getColumn());
        unset($db_resposne['data'][0]['id']);
        unset($db_resposne['data'][0]['update_time']);
        unset($db_resposne['data'][0]['create_time']);
        unset($db_resposne['data'][0]['if_delete']);
        foreach ($db_resposne['data'][0] as $k => $v) {
            $db_resposne['data'][0][$k] = '';
        }

        $this->filterRules($this->rules, $db_resposne['data'], $params['rules']);
        $access_rules = array('tableaccess' => $this->tableAccess, 'rules' => $this->rules);
        $db_resposne['access_rules'] = $access_rules;
        return $db_resposne;
    }

    public function Get($params = array(), $search_params = array(), $page_params = array(), $sorter = array())
    {
        $db_resposne = $this->Dao->readSpecified($search_params, array(), $page_params, $sorter);
        $this->filterRules($this->rules, $db_resposne['data'], $params['rules']);
        $access_rules = array('tableaccess' => $this->tableAccess, 'rules' => $this->rules);
        $db_resposne['access_rules'] = $access_rules;

        return $db_resposne;
    }

    public function Update($params = array(), $search_params = array())
    {
        if (empty($params)) {
            return 0;
        } else {
            $db_reponse = $this->Dao->insertOrupdate($params, $search_params);
            if (empty($db_reponse)) {
                $service_reponse = TrueSignConst::SQL_ERR('authLog数据更新出错');
            } else {
                $service_reponse = TrueSignConst::SUCCESS('authLog数据更新成功');
            }
            return $service_reponse;
        }
    }

    /*
     * 进行登录认证
     * */
    public function Auth($params = array())
    {

        $unique_auth_code = $params['unique_auth_code'];
        $UserSerivce = new UserService();
        $service_reponse = $UserSerivce->AuthAccount($params);
        if (!empty($service_reponse)) {
            $auth_reponse['ctrlname'] = array($service_reponse['document_id'] => $service_reponse['username']);
            $update_response = $this->Update(array('ctrlname'=>json_encode($auth_reponse['ctrlname'],256)),array('unique_auth_code'=>$unique_auth_code));
            if(!empty($update_response)){
                $auth_reponse['ctrlname']['level'] = $service_reponse['level'];
                $auth_reponse['token'] = Decrypt::encryption($service_reponse['username'], $service_reponse['document_id'], 'socket');

                $appService = new AppService();
                self::setParam('applevel','le',$service_reponse['level'],$search_apps_params);
                $apps = $appService->Get(array(),$search_apps_params,array(),array(),array('document_id','appname','applevel'));

                $auth_reponse['apps'] = $apps['data'];
                $final_response = TrueSignConst::SUCCESS(json_encode($auth_reponse));
            }
            else{
                $final_response = TrueSignConst::SQL_ERR('更新authlog账户信息失败');
            }

        } else {
            $final_response = TrueSignConst::AUTH_ERROR();
        }
        return $final_response;

    }


}