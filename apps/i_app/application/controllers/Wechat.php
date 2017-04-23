<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
use EasyWeChat\Foundation\Application;
class WechatController extends  IAppBaseController
{
    public function InterfaceAction()
    {
        $token = 'iamseeql';
        $params = $this->getParams(array('timestamp','nonce','signature','echostr'));
        $signature = $params['signature'];
        $echostr = $params['echostr'];
        unset($params['signature']);
        unset($params['echostr']);
        $params['token'] = $token;
        sort($params,SORT_STRING);
        $tmpstr = implode($params);
        $tmpstr = sha1($tmpstr);
        if($tmpstr == $signature){
            echo $echostr;
            exit;
        }

    }




}
