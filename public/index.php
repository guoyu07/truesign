<?php
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
header('Content-type: text/html; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept,Authorization");
\Yaf_Registry::set('START_EXEC_TIME',microtime(true));
\Yaf_Registry::set('START_EXEC_MEMORY',memory_get_usage());
if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
    exit;
};
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
$app = 'wechat_marketing';
foreach ($_REQUEST as $k=>$v){
    if($k=='app'){
        $app = $v;
    }
}
define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
$classLoader = require(APPLICATION_PATH . '/vendor/autoload.php');
$classLoader->setPsr4('ReInit\\', APPLICATION_PATH . '/library/ReInit/');
$classLoader->setPsr4('Royal\\', APPLICATION_PATH . '/library/Royal/');
$classLoader->setPsr4('Truesign\\', APPLICATION_PATH . '/common');
\Royal\Bootstrap::run($app);