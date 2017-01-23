<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

try {
	define('IS_CLI', PHP_SAPI==='cli');
	if(!IS_CLI)exit();
	define('APPLICATION_NAME', 'api');
	define('APPLICATION_PATH', realpath(__DIR__));
	include(sprintf('%s/service/service_%s.php', APPLICATION_PATH, APPLICATION_NAME));
	include(sprintf('%s/service/websocket_api.php', APPLICATION_PATH));

    $socket_server = new socket_server('socket');
    $socket_server->run();
}catch (Exception $e){
	die('run-ERROR: '.$e->getMessage().PHP_EOL);
}