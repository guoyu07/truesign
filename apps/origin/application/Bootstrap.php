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
    define('APPLICATION_PATH', realpath(__DIR__ . '/../../../'));
     require(APPLICATION_PATH.'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php');

    $classLoader = require(APPLICATION_PATH . '/vendor/autoload.php');

    $classLoader->setPsr4('ReInit\\', APPLICATION_PATH . '/library/ReInit/');
    $classLoader->setPsr4('Royal\\', APPLICATION_PATH . '/library/Royal/');
    $classLoader->setPsr4('Truesign\\', APPLICATION_PATH . '/common');
}
class Bootstrap extends Yaf_Bootstrap_Abstract{

    public function _initConfig() {
		//把配置保存起来
		$arrConfig = Yaf_Application::app()->getConfig();
	}

	public function _initPlugin(Yaf_Dispatcher $dispatcher) {
		//注册一个插件
		$objSamplePlugin = new SamplePlugin();
		$dispatcher->registerPlugin($objSamplePlugin);
	}

	public function _initRoute(Yaf_Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用简单路由
	}
	
	public function _initView(Yaf_Dispatcher $dispatcher){
		//在这里注册自己的view控制器，例如smarty,firekylin
        $dispatcher->disableView();
	}
}
