<?php

class Bootstrap extends \Yaf_Bootstrap_Abstract{

	public function _initConfig($dispatcher) {

		if(IS_CLI)return;
		$arrConfig = \Yaf_Application::app()->getConfig();
		\Yaf_Registry::set('config', $arrConfig);
	}
	public function _initView($dispatcher) {


        $dispatcher->disableView();
	}
	public function _initHelper()
	{

		if (!IS_CLI) {
			\Yaf_Loader::import('ref/ref.php');
			ref::config('expLvl', 2);
		}
		\Yaf_Loader::import('helper.php');
		\Yaf_Loader::import('resources.php');
	}
}