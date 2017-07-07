<?php
/**
 * @name SamplePlugin
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author ql_os
 */
use \Royal\Prof\TrueSignConst;
class AuthPlugin extends Yaf_Plugin_Abstract {
    public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {

        $controller = strtolower($request->getControllerName());
        $action = strtolower($request->getActionName());
        if($controller == 'loginorreg' || $controller == 'common'){
            return;
        }
        else{
            $token  = $_SERVER['HTTP_AUTHORIZATION'];
            if(empty($token)){
                self::throwException(TrueSignConst::REQUIRE_AUTH('无权限访问,重新登陆'));
            }
            else{
                self::checkToken($token);
            }



        }

    }

	public function dispatchLoopStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {


	}

	public function preDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

	public function postDispatch(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

	public function dispatchLoopShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
	}

    public function throwException(array $exception){
        $code = $exception['code'];
        $desc = $exception['desc'];
        throw new \Exception($desc,$code);
    }

    public function checkToken($token)
    {
        $decodeinfo = \Royal\Crypt\Decrypt::encryption($token,'','',1);
        if($decodeinfo['limit_time'] < time()){
            self::throwException(TrueSignConst::REQUIRE_AUTH('身份信息已过期，请重新登陆'));
        }
        else{
            $account_type = $decodeinfo['account_type'];
            $id = $decodeinfo['id'];
            $name = $decodeinfo['name'];
            switch ($account_type){
                case 'master':
                    $Adapter = new \Truesign\Adapter\wechat_marketing\masterAdapter();
                    break;
                case 'business':
                    $Adapter = new \Truesign\Adapter\wechat_marketing\businessAdapter();
                    break;
                case 'worker':
                    self::throwException(TrueSignConst::REQUIRE_AUTH('员工鉴权尚未开通，请重新登录'));
                    break;
                default:
                    self::throwException(TrueSignConst::REQUIRE_AUTH('鉴权错误，请重新登录'));
            }
            /*进行数据库二次认证*/
            $doDao = new \Royal\Data\DAO($Adapter);
            $db_response = $doDao->get(array('id'=>$id),array('username'));
            if($name == $db_response['username']){
                return;
            }
            else{
                self::throwException(TrueSignConst::REQUIRE_AUTH('鉴权失败，请重新登录'));
            }

        }
    }
}
