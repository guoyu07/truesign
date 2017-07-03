<?php

use Truesign\Service\Wechat_marketing_service\BusinessService;
class LoginOrRegController  extends AppBaseController {


    public function sendSmsAction()
    {

        $params = $this->getParams(array('phone','account_type'));
        $SMS = new \Truesign\Service\Sms\EallSms();
        $sms_code = rand(100000, 999999);
        $sms_response = $SMS->smsSend('business_regeister',$params['phone'],$sms_code);
        var_dump($sms_response);

	}

}
