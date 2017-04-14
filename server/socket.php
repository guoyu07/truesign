<?php
/*
 * Sometime too hot the eye of heaven shines
 */

define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
$classLoader = require(APPLICATION_PATH . '/vendor/autoload.php');
$classLoader->setPsr4('ReInit\\', APPLICATION_PATH . '/library/ReInit/');
$classLoader->setPsr4('Royal\\', APPLICATION_PATH . '/library/Royal/');
$classLoader->setPsr4('Truesign\\', APPLICATION_PATH . '/common');
$classLoader->setPsr4('Server\\', APPLICATION_PATH . '/server');

$socket = new \Server\link_swoole\socket_demo();
$socket->start();