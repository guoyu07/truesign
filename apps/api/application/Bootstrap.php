<?php
/**
 * @name Bootstrap
 * @author ql_os
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
if (php_sapi_name() == 'cli') {

}
class Bootstrap extends Yaf_Bootstrap_Abstract{


	
	public function _initView(Yaf_Dispatcher $dispatcher){
		//在这里注册自己的view控制器，例如smarty,firekylin
        $dispatcher->disableView();
	}
}
