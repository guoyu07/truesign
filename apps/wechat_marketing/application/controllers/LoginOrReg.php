<?php

use Truesign\Service\Wechat_marketing_service\BusinessService;
use Royal\Prof\TrueSignConst;
use \Truesign\Service\Wechat_marketing_service\FingerPrintsService;
use \Truesign\Service\Wechat_marketing_service\MasterService;

class LoginOrRegController extends AppBaseController
{


    public function indexAction()
    {
//        echo \Royal\Crypt\Decrypt::encryption('iamsee','5','business');
//        echo '<hr>';
//        $decode_info = (\Royal\Crypt\Decrypt::encryption('U0Q5WXF5WFUYfWQcIR1xRFAVXDVsBAwYGBAY=','','',1));
//        if(empty($decode_info)){
//            self::throwException(TrueSignConst::OPERATION_lOGIC_ERR('token无法识别'));
//        }
//        else{
//            $limit_time = $decode_info['limit_time'];
//            if($limit_time<time()){
//                self::throwException(TrueSignConst::OPERATION_lOGIC_ERR('身份验证已经过期,请重新登录'));
//
//            }
//            else{
//                $businessService = new BusinessService();
//                $userinfo = $businessService->getUserInfoCodeById($decode_info['id']);
//                if(empty($userinfo)){
//                    self::throwException(TrueSignConst::OPERATION_lOGIC_ERR('token识别身份信息错误'));
//                }
//                $decode_info['userinfo'] = $userinfo;
//                echo json_encode($decode_info,256);
//            }
//        }
        $fpservice = new FingerPrintsService();
        echo json_encode($fpservice->setFingerPrints());
    }

    public function sendSmsAction()
    {

        $params = $this->getParams(array('phone_num', 'account_type'), array(), new \Truesign\Adapter\wechat_marketing\businessAdapter());
        $SMS = new \Truesign\Service\Sms\EallSms();
        $sms_code = rand(100000, 999999);


        if ($params['account_type'] == 'business') {

            $businessService = new BusinessService();;
            $service_response = $businessService->UpdatePhoneSms(array('phone_num' => $params['phone_num'], 'sms' => $sms_code));

            if ($service_response > 0) {
                $sms_response = $SMS->smsSend('business_regeister', $params['phone'], $sms_code);
            }
            if($sms_response>0){
                $this->output2json(TrueSignConst::SUCCESS('信息发送成功'));

            }
            else{
                $this->output2json(TrueSignConst::ERROR('信息发送失败'));

            }
        } else {
            $this->throwException(TrueSignConst::ERROR('员工注册未开通'));
        }
    }

    /*
     * 注册接口
     * */
    public function regAction()
    {
        $params = $this->getParams(array('username', 'password', 'email', 'phone_num', 'phone_num_auth_code', 'account_type'), array('business_code'), new \Truesign\Adapter\wechat_marketing\businessAdapter());
        if (empty($params['account_type'])) {
            self::throwException(TrueSignConst::ERROR_DATA_FORMAT('账户类型不能为空'));
        } elseif ($params['account_type'] == 'business') {
            unset($params['business_code']);
            unset($params['account_type']);
            $businessService = new BusinessService();
            $service_response = $businessService->reg($params);
            $this->output2json($service_response);



        } elseif ($params['account_type'] == 'worker') {
            self::throwException(TrueSignConst::ERROR_DATA_FORMAT('员工注册尚未开通'));
        } else {
            self::throwException(TrueSignConst::ERROR_DATA_FORMAT('账户类型不合法'));
        }


    }

    /*登录接口
    */
    public function loginAction()
    {
        $params = $this->getParams(array('username', 'password','account_type'), array('business_code'), new \Truesign\Adapter\wechat_marketing\businessAdapter());
        if (empty($params['account_type'])) {
            self::throwException(TrueSignConst::ERROR_DATA_FORMAT('账户类型不能为空'));
        } elseif ($params['account_type'] == 'business') {
            unset($params['business_code']);
            unset($params['account_type']);

            $businessService = new BusinessService();
            $service_response = $businessService->login($params);
            $this->output2json($service_response);



        } elseif ($params['account_type'] == 'worker') {
            self::throwException(TrueSignConst::ERROR_DATA_FORMAT('员工登录尚未开通'));
        } elseif($params['account_type'] == 'master'){
            $masterService = new MasterService();
            $service_response = $masterService->login($params);
            $this->output2json($service_response);
        }
        else {
            self::throwException(TrueSignConst::ERROR_DATA_FORMAT('账户类型不合法'));
        }
    }

}
