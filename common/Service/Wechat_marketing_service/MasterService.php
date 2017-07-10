<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 15:52
 */

namespace Truesign\Service\Wechat_marketing_service;


use EasyWeChat\Core\Exception;
use Royal\Crypt\Decrypt;
use Royal\Data\DAO;
use Royal\Prof\TrueSignConst;
use Truesign\Adapter\wechat_marketing\masterAdapter;

class MasterService extends BaseService
{
    private $Adapter;
    private $Dao;
    private $tableAccess;
    private $rules;

    public function __construct()
    {
        $this->Adapter = new masterAdapter();
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
    /*
     * @for
     *
     */
    public function Get($params=array(),$search_params=array(),$page_params=array(),$sorter=array())
    {

        $db_resposne = $this->Dao->readSpecified($search_params,array(),$page_params,$sorter);

        $this->filterRules($this->rules,$db_resposne['data'],$params['rules']);
        $access_rules = array('tableaccess'=>$this->tableAccess,'rules'=>$this->rules);
        $db_resposne['access_rules'] = $access_rules;
//        if($params['document_id']){
//            $db_resposne['data'][0]['password'] = '';
//        }

        return $db_resposne;


    }
    /*
         * @for
         *
         */
    public function Update($params=array(),$search_params=array(),$page_params=array()){

        $search_params['id'] = $params['document_id'];
        unset($search_params['document_id']);
        $params['password'] = self::SHA256Pass($params['password']);
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

    /*管理员登录逻辑*/
    public function login($params){

        $search_params = array('username'=>$params['username']);
        $password = self::SHA256Pass($params['password']);
        $count = $this->Dao->count($search_params);

        if(empty($count)){
            $response = TrueSignConst::OPERATION_lOGIC_ERR('账户不存在');
        }
        else if($count == 1){
            $db_response = $this->Dao->get($search_params,array('document_id','password'));
            if($db_response['password'] === $password){
                $response = TrueSignConst::SUCCESS('登录成功');

                $fgservice =  new FingerPrintsService();
                $db_reponse = 0;
                $db_reponse = $fgservice->setFingerPrints($this->Adapter->table().'Adapter',
                    $db_response['document_id'],$params['username'],
                    'login','','PC','ipv4',TrueSignConst::GET_DEBUG_BACKTRACE());
                $userinfo = $this->Get(array('document_id'=>$db_response['document_id']));
                $userinfo = $userinfo['data'][0];
                unset($userinfo['password']);
                if(!empty($db_reponse)){
                    $userinfo['lable_type'] = '管理员';
                    $userinfo['fg'] = $fgservice->getLastFingerPrints(
                        $this->Adapter->table().'Adapter',$db_response['document_id'],'login'
                    );
                }
                $response['response'] = array(
                    'token'=>Decrypt::encryption($params['username'],$db_response['document_id'],'master'),
                    'userinfo'=> $userinfo
                );
            }
            else{
                $response = TrueSignConst::ERROR('密码错误');
            }
        }
        else{
            $response = TrueSignConst::CODE_LOGIC_ERR('账户['.$params['username'].']存在问题，请联系客服！');
            Logger::log('CODELOGIC', '存在' . $count . '条相同 用户名 注册信息,用户名不应有重复数据', array(TrueSignConst::CODE_LOGIC_ERR(), 'username' => $params['username']));

        }

        return $response;

    }

}