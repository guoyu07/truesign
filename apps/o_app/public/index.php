<?php
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
header('Content-type: text/html; charset=utf-8');

define('APP_PATH', realpath(__DIR__.'/../'));
$i_app = new Yaf_Application( APP_PATH . "/conf/application.ini");
Yaf_Registry::set('aa','aaa');
Yaf_Registry::set('aa','aaa');
$i_app->bootstrap()->run();
?>
