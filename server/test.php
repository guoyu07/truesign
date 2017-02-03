<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2017/2/2
 * Time: 上午2:59
 */
define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
$classLoader = require(APPLICATION_PATH . '/vendor/autoload.php');
$classLoader->setPsr4('ReInit\\', APPLICATION_PATH . '/library/ReInit/');
$classLoader->setPsr4('Royal\\', APPLICATION_PATH . '/library/Royal/');
$classLoader->setPsr4('Truesign\\', APPLICATION_PATH . '/common');
$classLoader->setPsr4('server\\', APPLICATION_PATH . '/server');

$cla = new  server\link_swoole\test_server();
$cla::echo_hello();