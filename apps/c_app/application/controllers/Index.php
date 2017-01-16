<?php
/**
 * @name IndexController
 * @author ql_os
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/i_app/index/index/index/name/ql_os 的时候, 你就会发现不同
     */
	public function indexAction() {
        $call = $_GET['callback'];
        $data = array(
            'name'=>'iamsee',
            'token'=>'123',
        );
        echo $this->result2JSONP($call,$data);
	}
}
