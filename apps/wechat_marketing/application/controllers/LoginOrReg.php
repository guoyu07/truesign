<?php

use Truesign\Service\Wechat_marketing_service\BusinessService;
use Royal\Prof\TrueSignConst;

class LoginOrRegController extends AppBaseController
{


    public function indexAction()
    {
        echo \Royal\Crypt\Decrypt::encryption('iamsee',1);
        echo '<hr>';
        print_r(\Royal\Crypt\Decrypt::encryption('WFlaRAdWGFQBBQlWClYBU1s=',1,1));
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
            if($service_response>0){
                self::throwException(TrueSignConst::SUCCESS('注册成功'));
            }
            else{
                self::throwException(TrueSignConst::ERROR('注册失败'));

            }
            self::throwException(TrueSignConst::SUCCESS($service_response));


        } elseif ($params['account_type'] == 'worker') {
            self::throwException(TrueSignConst::ERROR_DATA_FORMAT('员工注册尚未开通'));
        } else {
            self::throwException(TrueSignConst::ERROR_DATA_FORMAT('账户类型不合法'));
        }


    }

}
