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

/* *****************************************************************
 * 刷新数据库
 */
$adapters = array();

$filesnames = scandir(APPLICATION_PATH.'/common/Adapter/');
foreach ($filesnames as $k=>$dir){
    if(!in_array(strtolower($dir),array('techies'))){
        unset($filesnames[$k]);
    }
}
foreach ($filesnames as $name){
    $adapterFiles =APPLICATION_PATH.'/common/Adapter/'.$name.'/*Adapter.php';

    foreach (glob($adapterFiles) as $realfile) {

        if (preg_match('/([^\/]*Adapter).php$/', $realfile, $matched)) {
            $class = 'Truesign\\Adapter\\'.$name.'\\' . $matched[1];

            if (class_exists($class)) {
//                $adapter = new $class;
                $adapters[] = $class;
            }
        }
    }

}

//生成相对MVC demo代码
foreach ($adapters as $adapter) {
    $filepath = str_replace('\\','/',$adapter);

    $adpName = basename($filepath);
    $adp = new $adapter;
    $app = strtolower($adp->belongApp());

    $database = $adp->database();
    $table = $adp->table();

    $desc = $adp->tableDesc();


    define('TEMPLATE_PATH',APPLICATION_PATH.'/library/Royal/InitCommand/templates');
    define('CURRECT_APPLICATION_PATH',APPLICATION_PATH.'apps'.DIRECTORY_SEPARATOR.$app );
    $application = new \Royal\InitCommand\GenerateMVCCommand();
    echo $table.' '. $desc.' '.$adpName;
    $application->execute('index',$table,$desc,$adpName);
    sleep(1);

}




