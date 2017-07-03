<?php

namespace Truesign\Service\Sms;



use Royal\CodeLogic\CodeLogic;
use Royal\Logger\Logger;

class EallSms
{
    public $smsTemplates = array(
        'verify_sms_shop' => '【微店宝】微店宝登陆验证码%s，有效期30分钟。买房卖房登陆微店宝，动动手指分分钟搞定',
        'verify_sms_app' => '【易遨公司】易客在线登录验证码%s，有效期30分钟。买房卖房登录易客在线，动动手指分分钟搞定。',
        'verify_sms_hiddencall' => '【易遨公司】易遨经纪人app隐号通话验证码%s，有效期30分钟。请不要将本验证码透露给其他人。',
        'app_login' => '【易遨公司】易遨掌中宝app登录验证码%s，有效期30分钟。请不要将本验证码透露给其他人。',
        'business_regeister' => '【易遨公司】验证码%s，有效期30分钟。',
    );

    public function __construct() {
        $this->_username = 'yiao';
        $this->_password = 'b1b3f23de796a731ba3b0d85831fbce7';
    }



    public function smsSend($template, $phone, $p1, $p2='', $p3='', $p4='', $p5='') {



        if (isset($this->smsTemplates[$template])) {
            $content = sprintf($this->smsTemplates[$template], $p1, $p2, $p3, $p4, $p5);
            $f = $this->juxinSendSms($phone, $content);
            if ($f) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return -1;
        }
    }

    // 聚信发短信
    public function juxinSendSms($phone, $contents) {
        $url = 'http://api.app2e.com/smsBigSend.api.php';
        $data = array(
            'username' => $this->_username,
            'pwd' => $this->_password,
            'p' => $phone,
            'isUrlEncode' => 'no',
            'charSetStr' => 'utf8',
            'msg' => $contents,
        );
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