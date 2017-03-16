<?php
use Truesign\Adapter\DbServer\dbsAdapter;
use Royal\Data\DbConfig;


//如果参数中有IN_PHPUNIT，则刷新测试库
if(isset($argv[1]) && $argv[1] == 'IN_PHPUNIT'){
    define('IN_PHPUNIT', true);
}
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
define('APPLICATION_PATH', __DIR__.'/../../' );

$classLoader = require(APPLICATION_PATH . '/vendor/autoload.php');
$classLoader->setPsr4('Royal\\', APPLICATION_PATH . '/library/Royal/');
$classLoader->setPsr4('Truesign\\', APPLICATION_PATH . '/common');
\Royal\Bootstrap::run();

/* *****************************************************************
 * 刷新数据库
 */
$adapters = array();

$filesnames = scandir(APPLICATION_PATH.'/common/Adapter/');
foreach ($filesnames as $k=>$dir){
    if(in_array($dir,array('.','..','Base'))){
        unset($filesnames[$k]);
    }
}

foreach ($filesnames as $name){
    $adapterFiles =APPLICATION_PATH.'/common/Adapter/'.$name.'/*Adapter.php';
    foreach (glob($adapterFiles) as $realfile) {
        if (preg_match('/([^\/]*Adapter).php$/', $realfile, $matched)) {
            $class = 'Truesign\\Adapter\\'.$name.'\\' . $matched[1];

            if (class_exists($class)) {
                $adapter = new $class;
                $adapters[] = $adapter;
            }
        }
    }

}

$model = new DbConfig();
//刷新库
foreach ($adapters as $adapter) {
    $model->refreshTable($adapter);
    $model->refreshKey($adapter);
}
var_dump('ok');

