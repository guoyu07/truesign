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
        if($controller == 'website'){
            return;
        }
        if($controller == 'apps' || $controller == 'wsserver'){
            return;
        }
        if($action == 'getFdByApp' || $action == 'saveorupdateuserinfoanddanmu'){

            return;
        }
        $params = $request->getParams();
        $encryption_key = $params['encryption_key'];
        $unique_auth_code = $params['unique_auth_code'];
        if(empty($encryption_key) || empty($unique_auth_code)){
//            $response->clearBody();
//            $response->setBody( '请求非法 1no access');
            throw new Exception('请求非法 no access',-100);

//            throw new Exception('请求非法 no access',-199);
        }
        else{
            $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());
            $needData = $doDao->readSpecified(array(
                'unique_auth_code'=>$params['unique_auth_code']
            ),array());
            if($needData['data'][0]['document_id']){
                $decode_encryption_key = \Royal\Util\Decrypt::encryption($params['encryption_key'],$needData['data'][0]['document_id'],1);
                if(sizeof($decode_encryption_key) == 2){
                    [0=>$tmp_unique_auth_code,1=>$limit_time]=$decode_encryption_key;
                    if($limit_time>time()){
                        if($tmp_unique_auth_code == $params['unique_auth_code'] && !empty($needData['data'][0]['apps'])){
                            $re_check['status'] = 1;
                            $re_check['note'] = '验证成功';
                            return;
                        }
                        elseif(empty($needData['data'][0]['apps'])){
                            $re_check['status'] = 0;
                            $re_check['note'] = '上次连接已经断开';
                        }
                        else{
                            $re_check['status'] = 0;
                            $re_check['note'] = '唯一验证key不一致';
                        }
                    }
                    else{
                        $re_check['status'] = 0;
                        $re_check['note'] = '加密key已经过期';
                    }
                }
                else{
                    $re_check['status'] = 0;
                    $re_check['note'] = '加密key违法';
                }
            }
    //
    //        ['0'=>$encryption_key,'1'=>$limit_time] = \Royal\Util\Decrypt::en
//            $response->clearBody() ;
//            $response->setBody( $re_check) ;
            throw new Exception('请求非法 1no access',-100);

        }
//
	}

	public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

	public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

	public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}
}
