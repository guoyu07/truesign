<?php
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
header('Content-type: text/html; charset=utf-8');

try {
    define('IS_CLI', PHP_SAPI==='cli');
    if(!IS_CLI)exit();

    $app = 'server_app';
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
    $classLoader->setPsr4('Server\\', APPLICATION_PATH . '/server');

    $socket_server = new \Server\link_swoole\new_socket_server('socket');
    $socket_server->run();
}catch (Exception $e){
    die('run-ERROR: '.$e->getMessage().PHP_EOL);
}