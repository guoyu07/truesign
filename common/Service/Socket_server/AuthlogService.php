<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 15:52
 */

namespace Truesign\Service\Socket_server;


use function array_values;
use EasyWeChat\Core\Exception;
use function explode;
use function gettype;
use function implode;
use function in_array;
use function is_array;
use function json_decode;
use function json_encode;
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

    public function insertOrupdate($params = array(), $search_params = array())
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
    public function disAuth($params = array())
    {
        if (empty($params)) {
            return 0;
        } else {

            $search_params['fd'] = $params['fd'];
            $params = [];
            $params['app'] = '';
            $params['ctrlname'] = '';
            $params['fd'] = '';
            $params['authway'] = '';
            $db_reponse = $this->Dao->updateByQuery($params, $search_params);

            if (empty($db_reponse)) {
                $service_reponse = TrueSignConst::SQL_ERR('断线  ！ authLog数据更新出错');
            } else {
                $service_reponse = TrueSignConst::SUCCESS('断线  ！ authLog数据更新成功');
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
        $authway = $params['authway'];
        $UserSerivce = new UserService();
        $service_reponse = $UserSerivce->AuthAccount($params);
        if (!empty($service_reponse)) {

            $auth_reponse['ctrlname'] = array($service_reponse['document_id'] => $service_reponse['username']);


            /*、对  相同[账户&密码&认证平台、方式 的之前登录用户进行掉线处理，返回fd]*/
            $stop_fds_check = $this->Dao->readSpecified(
                array('ctrlname'=>json_encode($auth_reponse['ctrlname'],256),'authway'=>$authway),
                array('fd')
            );
            if(!empty($stop_fds_check['statistic']['count'])){
                $stop_fds = [];
                foreach ($stop_fds_check['data'] as $index=>$fd_arr){
                    $fd = (int)implode('',array_values($fd_arr));
                    $stop_fds[] = $fd;
                    self::disAuth(array('fd'=>$fd)); /*强制断线，并更新数据*/
                }
            }
            $update_response = $this->insertOrupdate(array('ctrlname'=>json_encode($auth_reponse['ctrlname'],256),'authway'=>$params['authway']),array('unique_auth_code'=>$unique_auth_code));
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
        $final_response['syscmd']['stop_fds'] = $stop_fds;
        return $final_response;

    }

    /*
     * 获取消息发送fd
     * */
    public function GetFdByUAC($unique_auth_code)
    {
        self::setParam('ctrlname','neq','',$search_params);
        self::setParam('app','neq','',$search_params);
        self::setParam('authway','neq','',$search_params);
        self::setParam('unique_auth_code','eq',$unique_auth_code,$search_params);
        $self_db_reponse = $this->Dao->get($search_params,array('app'));
        $self_app = $self_db_reponse['app'];
        $self_app_ids = [];
        if(!empty($self_app)){
            $self_app = json_decode($self_app,true);
            foreach ($self_app as $k=>$v){
                $self_app_ids[] = (integer)implode('',array_keys($v));
            }
        }
        $apps = $self_app;
        $to_id = [];
        if(!empty($self_app_ids)){
            unset($search_params['unique_auth_code']);
            $all_db_reponse = $this->Dao->readSpecified($search_params,array('app','fd'));
            if(!empty($all_db_reponse['statistic']['count'])){
                $all_db_reponse = $all_db_reponse['data'];
                foreach ($all_db_reponse as $k=>$v){
//                    if(!empty($v['app'])){
//                        foreach (json_decode($v['app'],ture) as $app_id=>$app){
//                            if(!in_array($app,$apps)){
//                                $apps[] = $app;
//
//                            }
//                        }
//                    };
                    $id_list = $this->getIdListfromDataList(json_decode($v['app'],true));
                    foreach ($id_list as $index=>$id){
                        if(in_array($id,$self_app_ids)){
                            $to_id[] = (integer)$v['fd'];
                        }
                    }
                }
            }

//            if(!empty($all_db_reponse)){
//                foreach ($all_db_reponse as $k=>$v) {
//
//                    return $v;
//                }
//            }

        }
        $to_id = array_values(array_unique($to_id));
//        $apps = array_values(array_unique($apps));
        return array('apps'=>$apps,'to_id'=>$to_id);
    }

    public function getIdListfromDataList($datalist)
    {
        $id_list = [];
        if(!empty($datalist)){
            foreach ($datalist as $k=>$v){
                $id_list[] = (integer)implode('',array_keys($v));
            }
        }
        return $id_list;

    }

    public function getUserByFd($fds)
    {
        if(is_array($fds)){
            $server_reposne = [];
            foreach ($fds as $i => $fd) {
                $base_user_info = $this->Dao->get(array('fd'=>$fd),array('ctrlname','authway'));
                if(!empty($base_user_info['ctrlname'])){
                    $user = $base_user_info['ctrlname'];
                    $authway = $base_user_info['authway'];
                }
                else{
                    $user = '';
                    $authway = '';
                }
                $server_reposne[] = array($fd=>array('user'=>$user,'authway'=>$authway));
            }
        }
        else{
            $base_user_info = $this->Dao->get(array('fd'=>$fds),array('ctrlname','authway'));
            if(!empty($base_user_info['ctrlname'])){
                $user = $base_user_info['ctrlname'];
                $authway = $base_user_info['authway'];
            }
            else{
                $user = '';
                $authway = '';
            }
            $server_reposne = array($fds=>array('user'=>$user,'authway'=>$authway));
        }
        return $server_reposne;
    }


}