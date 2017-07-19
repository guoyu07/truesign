<?php
/**
 * @name IndexController
 * @author ql_os
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends Yaf_Controller_Abstract {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/i_app/index/index/index/name/ql_os 的时候, 你就会发现不同
     */
	public function indexAction() {
		echo json_encode($_COOKIE);
	}

    public function getRequestAction()
    {
        $helper =  new \Royal\Util\helper();
        $ip = $helper::getClientIP();
        $ip['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        echo json_encode($ip,256);
        $f = fopen('debug_ip.txt','a');
        fwrite($f,date('Y-m-d H:i:s',time()).PHP_EOL);
        fwrite($f,json_encode($ip,256).PHP_EOL);

        fclose($f);
	}
}
