<?php
/**
 * @name SamplePlugin
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author ql_os
 */
class SamplePlugin extends Yaf_Plugin_Abstract {

	public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

	public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

	public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        $controller = strtolower($request->getControllerName());
        $action = strtolower($request->getActionName());
        if($controller == 'index'){
            return;
        }
        if($controller == 'website' && $action == 'regorlogin'){
            return;
        }
        if($controller == 'website' && $action == 'dislinksocketid'){
            return;
        }
        if($controller == 'website' && $action == 'checkloginbykey'){
            return;
        }
        if($controller == 'website' && $action == 'getchatlist'){
            return;
        }
        if($controller == 'website' && $action == 'dealcheckemailcode'){
            return;
        }
        if($controller == 'shadowsocks'){
            return;
        }
        if($controller == 'common' || $controller == 'wechat'  ){
            return;
        }

        if($controller == 'apps' && ($action=='getapprule' || $action=='bindapps' || $action== 'getfdbyapp' || $action== 'disconnapp' || $action=='checklogin')){
            return;
        }
        if( $controller == 'wsserver' ){
            return;
        }
        if($action == 'getFdByApp' || $action == 'saveorupdateuserinfoanddanmu'){

            return;
        }
        $params = $request->getParams();
        $website_encryption_key = $request->get('website_encryption_key');
        $encryption_key = $request->get('encryption_key');
        $unique_auth_code = $request->get('unique_auth_code');
        if(empty($unique_auth_code)){
            throw new Exception('请求非法.无法确定唯一识别码, no access',-100);
        }
        elseif(empty($encryption_key) && empty($website_encryption_key)){
            throw new Exception('请求非法.无法确定任何一种授权序列key, no access',-100);
        }
        else{
            $check_encryption_key = self::check_encryption_key($unique_auth_code,$encryption_key);
            $check_website_encryption_key = self::check_encryption_key($unique_auth_code,$website_encryption_key,true);
            if(!empty($check_encryption_key['status']) && (!empty($check_website_encryption_key[status]))){
                return;
            }
            else{
//                throw new Exception(123,-100);
            }

        }
	}

	public function check_encryption_key($unique_auth_code,$key,$website=false){
	    if(!$website){

            $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());
            $needData = $doDao->readSpecified(array(
                'unique_auth_code'=>$unique_auth_code
            ),array());

            if($needData['data'][0]['document_id']){
                $decode_encryption_key = \Royal\Util\Decrypt::encryption($key,$needData['data'][0]['document_id'],1);
                if(sizeof($decode_encryption_key) == 2){
                    [0=>$tmp_unique_auth_code,1=>$limit_time]=$decode_encryption_key;
                    if($limit_time>time()){
                        if($tmp_unique_auth_code == $unique_auth_code && !empty($needData['data'][0]['apps'])){
                            $re_check['status'] = 1;
                            $re_check['msg'] = '验证成功';

                        }
                        elseif(empty($needData['data'][0]['apps'])){
                            $re_check['status'] = 0;
                            $re_check['msg'] = '上次连接已经断开';
                        }
                        else{
                            $re_check['status'] = 0;
                            $re_check['msg'] = '唯一验证key不一致';
                        }
                    }
                    else{
                        $re_check['status'] = 0;
                        $re_check['msg'] = '加密key已经过期';
                    }
                }
                else{
                    $re_check['status'] = 0;
                    $re_check['msg'] = '加密key违法';
                }
            }
            else{
                $re_check['status'] = 0;
                $re_check['msg'] = '此唯一识别码未颁发';

            }
            return $re_check;
        }
        else{
            $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appWebSiteAdapter());
            $needData = $doDao->readSpecified(array(
                'unique_auth_code'=>$unique_auth_code
            ),array());
            if($needData['data'][0]['document_id']){
                $decode_encryption_key = \Royal\Util\Decrypt::encryption($key,$needData['data'][0]['document_id'],1);
                if(sizeof($decode_encryption_key) == 2){
                    [0=>$tmp_unique_auth_code,1=>$limit_time]=$decode_encryption_key;
                    if($limit_time>time()){
                        if($tmp_unique_auth_code == $unique_auth_code){
                            $re_check['status'] = 1;
                            $re_check['msg'] = 'website验证成功';

                        }
                        else{
                            $re_check['status'] = 0;
                            $re_check['msg'] = 'website唯一验证key不一致';
                        }
                    }
                    else{
                        $re_check['status'] = 0;
                        $re_check['msg'] = 'website加密key已经过期';
                    }
                }
                else{
                    $re_check['status'] = 0;
                    $re_check['msg'] = 'website加密key违法';
                }
            }
            else{
                $re_check['status'] = 0;
                $re_check['msg'] = 'website此唯一识别码未颁发';
            }
            return $re_check;
        }

    }
	public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

	public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

	public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}
}
