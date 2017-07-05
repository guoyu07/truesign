<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 15:52
 */

namespace Truesign\Service\Wechat_marketing_service;


use Royal\Crypt\Decrypt;
use Royal\Crypt\SHA256;
use Royal\Data\DAO;
use Royal\Logger\Logger;
use Royal\Prof\TrueSignConst;
use function Sodium\crypto_pwhash_scryptsalsa208sha256;
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
        if ($this->tableaccess_ctrl()) {
            $access = $this->getAccess();
            $this->AuthTableAccess($access, $this->tableAccess);
        }

    }

    /*
	 * @for客户信息新增字段获取接口
	 */
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

    /*
     * @for 获取客户信息接口
     *
     */
    public function Get($params = array(), $search_params = array(), $page_params = array(), $sorter = array())
    {

        $db_resposne = $this->Dao->readSpecified($search_params, array(), $page_params, $sorter);

        $this->filterRules($this->rules, $db_resposne['data'], $params['rules']);
        $access_rules = array('tableaccess' => $this->tableAccess, 'rules' => $this->rules);
        $db_resposne['access_rules'] = $access_rules;
        echo json_encode($db_resposne);
        exit();
        return $db_resposne;


    }

    /*
         * @for 客户信息更新、软删除接口
         *
         */
    public function Update($params = array(), $search_params = array(), $page_params = array())
    {

        $search_params['id'] = $params['document_id'];
        unset($search_params['document_id']);
        $params['password'] = self::SHA256Pass($params['password']);
        $db_response = $this->Dao->insertOrupdate($params, $search_params);
        return $db_response;
    }

    /*
     * @for 客户信息批量软删除接口
     */
    public function GroupDel($params = array())
    {

        if (!empty($params['ids'])) {
            $params_ids = explode(',', $params['ids']);
        } else {
            $params_ids = array();
        }
        $doAdapter = new businessAdapter();
        $doDao = new DAO($doAdapter);
        $updatedata = [];
        foreach ($params_ids as $k => $v) {
            $updatedata_item['id'] = $v;
            $updatedata_item['if_delete'] = 1;
            $updatedata[] = $updatedata_item;
        }

        $db_reponse = $doDao->groupUpdate($params['ids'], $updatedata, 'if_delete');
        return $db_reponse;

    }

    /*
     * 根据id获取商户信息基本
     * */
    public function getUserInfoCodeById($id){
        $db_reponse = $this->Dao->read(array('id'=>$id));
        if(!empty($db_reponse['data'][0])){
            unset($db_reponse['data'][0]['password']);
        }
        return $db_reponse['data'][0];

    }
    /*
     * 根据手机号和验证码在business表里创建记录
     * 流程->判断此手机号是否已经验证了其它账户
     * ->是->返回提示
     * ->否->保存手机号和验证码（有手机号则更新，无则插入数据），在客户点击注册后根据手机号判定验证码是否正确，
     * ->否重复上一步
     * ->是保存其他信息更新状态为已验证->账户注册完成
     *
     * */
    public function UpdatePhoneSms($params)
    {
        $phone = $params['phone_num'];
        $sms = $params['sms'];
        $count = $this->Dao->count(array('phone_num' => $phone, 'phone_num_auth_status' => 1));
        if (empty($count)) {
            $db_response = $this->Dao->insertOrupdate(array('phone_num' => $phone, 'phone_num_auth_code' => $sms, 'phone_num_auth_code_updatetime' => time()), array('phone_num' => $phone));

            return $db_response;
        } elseif ($count > 1) {
            Logger::log('CODELOGIC', '存在' . $count . '条相同手机号注册信息,手机号不应有重复数据', array(TrueSignConst::CODE_LOGIC_ERR(), 'phone_num' => $phone));
            self::throwException(TrueSignConst::CODE_LOGIC_ERR('此手机号已经绑定其他账户'));

        } else {
            self::throwException(TrueSignConst::ERROR('此手机号已经绑定其他账户'));
        }
    }

    /*商户注册逻辑*/
    public function reg($params)
    {
        $count = $this->Dao->count(array('phone_num' => $params['phone_num'] , 'phone_num_auth_status' => 1));
        if (empty($count)) {

            $count = $this->Dao->count(array('username' => $params['username'], 'phone_num_auth_status' => 1));
            if(!empty($count)){
                self::throwException(TrueSignConst::OPERATION_lOGIC_ERR('此用户名已经注册'));
            }
            $count = $this->Dao->count(array('email' => $params['email'], 'phone_num_auth_status' => 1));
            if(!empty($count)){
                self::throwException(TrueSignConst::OPERATION_lOGIC_ERR('此邮箱已经注册'));
            }
            $count = $this->Dao->count(array('phone_num' => $params['phone_num']));
            if (empty($count)) {
                self::throwException(TrueSignConst::OPERATION_lOGIC_ERR('此手机号暂未获取验证码，非法验证'));
            } elseif ($count == 1) {
                $db_reponse = $this->Dao->readSpecified(array('phone_num' => $params['phone_num']), array('document_id', 'phone_num_auth_code', 'phone_num_auth_code_updatetime'));

                $db_data = $db_reponse['data'][0];
                if (time() - $db_data['phone_num_auth_code_updatetime'] > 30 * 60) {

                    self::throwException(TrueSignConst::OPERATION_lOGIC_ERR('验证码失效，请重新获取'));
                } elseif ($db_data['phone_num_auth_code'] != $params['phone_num_auth_code']) {
                    self::throwException(TrueSignConst::OPERATION_lOGIC_ERR('验证码不正确,请重新获取'));
                } else {

                    $db_reponse = array();
                    $params['password'] = self::SHA256Pass($params['password']);
                    $identifier_code = $this->IdentifierGenerator('B',new businessAdapter(),'identifier_code');
                    $update_params = array_merge(array('id' => $db_data['document_id'],'identifier_code'=>$identifier_code , 'phone_num_auth_status' => 1), $params);
                    $db_reponse = $this->Dao->update($update_params);

                    if($db_reponse>0){
                        /*
                         * 指纹信息记录
                         * */
                        $response = TrueSignConst::SUCCESS('注册成功');
                        $fgservice =  new FingerPrintsService();
                        $db_reponse = 0;
                        $db_reponse = $fgservice->setFingerPrints('businessAdapter',
                            $db_data['document_id'],$params['username'],
                            'reg','','PC','ipv4');
                        $userinfo = $this->getUserInfoCodeById($db_data['document_id']);
                        if(!empty($db_reponse)){

                            $userinfo['fg'] = $fgservice->getLastFingerPrints(
                                'businessAdapter',$db_data['document_id'],'reg'
                            );
                        }

                        /*
                         * 注册相应返回
                         * */
                        $response['response'] = array(
                            'token'=>Decrypt::encryption($params['username'],$db_data['document_id'],'business'),
                            'userinfo'=> $userinfo
                        );

                    }
                    else{
                        $response = TrueSignConst::ERROR('注册失败');
                    }

                    return $response;
                }
            } else {
                self::throwException(TrueSignConst::CODE_LOGIC_ERR('手机号[' . $params['phone_num'] . ']存在冗余数据，请联系管理员或者换号注册'));
                Logger::log('CODELOGIC', '存在' . $count . '条相同手机号注册信息,手机号不应有重复数据', array(TrueSignConst::CODE_LOGIC_ERR(), 'phone_num' => $params['phone_num']));

            }
        } elseif ($count > 1) {
            Logger::log('CODELOGIC', '存在' . $count . '条相同手机号注册信息,手机号不应有重复数据', array(TrueSignConst::CODE_LOGIC_ERR(), 'phone_num' => $phone));
            self::throwException(TrueSignConst::CODE_LOGIC_ERR('此手机号已经绑定注册过'));
        } else {
            self::throwException(TrueSignConst::ERROR('此手机号已经绑定注册过'));
        }
    }

    /*商户登录逻辑*/
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
                $db_reponse = $fgservice->setFingerPrints('businessAdapter',
                    $db_response['document_id'],$params['username'],
                    'login','','PC','ipv4');
                $userinfo = $this->getUserInfoCodeById($db_response['document_id']);
                if(!empty($db_reponse)){

                    $userinfo['fg'] = $fgservice->getLastFingerPrints(
                        'businessAdapter',$db_response['document_id'],'login'
                    );
                }
                $response['response'] = array(
                    'token'=>Decrypt::encryption($params['username'],$db_response['document_id'],'business'),
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