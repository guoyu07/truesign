<?php

namespace Truesign\Service\Sms;



class QfSms
{
    public $smsTemplates = array(
        'verify_sms_shop' => '【微店宝】微店宝登陆验证码%s，有效期30分钟。买房卖房登陆微店宝，动动手指分分钟搞定',
        'verify_sms_app' => '【易遨公司】易客在线登录验证码%s，有效期30分钟。买房卖房登录易客在线，动动手指分分钟搞定。',
        'verify_sms_hiddencall' => '【易遨公司】易遨经纪人app隐号通话验证码%s，有效期30分钟。请不要将本验证码透露给其他人。',
        'app_login' => '【易遨公司】易遨掌中宝app登录验证码%s，有效期30分钟。请不要将本验证码透露给其他人。',
        'business_regeister' => '【易遨公司】<%s>验证码%s，有效期30分钟。',
    );

    public function __construct() {
        $this->_aliSMS_url = 'http://gw.api.taobao.com/router/rest';
        $this->_appkey = '23315429';
        $this->_secret = '806c66e349ba15f33ec35485c5523e1e';
        $this->_template_code = 'SMS_5064133';
        $this->_sign_desc = "身份验证";
    }



    public function smsSend($template, $phone, $verifyCode, $p2='', $p3='', $p4='', $p5='') {

        $sms_param = [];
        $sms_param = ['code']=$verifyCode;
        $sms_param = ['product']='[测试]';
        $sms_param = json_encode($sms_param);
        $f = $this->aliSendSms($this->_sign_desc,$this->_template_code,$sms_param,$phone, $this->_aliSMS_url,$this->_appkey,$this->_secret);
    }

    // 聚信发短信
    public function juxinSendSms($sign_desc,$template_code,$sms_param,$phone,$sms_url,$appkey,$secret) {
        $url = $sms_url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $r = curl_exec($ch);
        curl_close($ch);
        echo $r;

        $r = json_decode($r, 1);

        return (isset($r['status']) && $r['status'] = 100);
    }

    public  function sendShopSms($phone, $code){
        $this->smsSend('verify_sms_shop', $phone, $code, 'http://m.eallcn.com', '', '', '', 'eallcn');
        return true;
    }

    public  function sendAppSms($phone, $code){
        $this->smsSend('verify_sms_shop', $phone, $code, 'http://m.eallcn.com', '', '', '', 'eallcn');
        return true;
    }

    public  function sendHiddenCallSms($phone, $code){
        return $this->smsSend('verify_sms_hiddencall', $phone, $code, '', '', '', '', 'eallcn');
    }

}