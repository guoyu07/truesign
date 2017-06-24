<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class WechatController extends  OAppBaseController
{
    public function InterfaceAction()
    {
        $token = 'iamseeql';
        $params = $this->getParams(array('timestamp','nonce','signature','echostr'));
        $signature = $params['signature'];
        $echostr = $params['echostr'];
        unset($params['signature']);
        unset($params['echostr']);
        $params[] = $token;
        $params = sort($params);
        $tmpstr = implode('',$params);
        $tmpstr = sha1($tmpstr);
        if($tmpstr == $signature){
            echo $echostr;
            exit;
        }

    }




}
