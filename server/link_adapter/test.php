<?php


date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
define('APPLICATION_PATH', __DIR__.'/../../' );

$classLoader = require(APPLICATION_PATH . '/vendor/autoload.php');
$classLoader->setPsr4('Royal\\', APPLICATION_PATH . '/library/Royal/');
$classLoader->setPsr4('Truesign\\', APPLICATION_PATH . '/common');
\Royal\Bootstrap::run('o_app');


$user_dao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());

var_dump($user_dao->read());

//var_dump($data);
