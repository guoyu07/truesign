<?php
namespace Royal;

use Royal\Prof\TimeStack;
use Royal\Prof\TrueSignConst;
abstract class Bootstrap {

    static function loadConfig() {
//        初始化常量
        new TrueSignConst();

        //配置节点
        $section = 'common';
        //如果在phpunit环境中，则加载phpunit的配置节点
        if(defined('IN_PHPUNIT') && IN_PHPUNIT == true){
            $section = 'phpunit';
        }
        $config = new \Yaf_Config_Ini(APPLICATION_PATH.'/config/business.ini', $section);
        \Yaf_Registry::set('config', $config);
        $db_arr =  $config->get('db')->toArray();

        $currect_config = new \Yaf_Config_Ini(CURRECT_APPLICATION_PATH.'/conf/application.ini','common');
        $currect_db_arr = $currect_config->get('db')->toArray();

        $dbconfig = array_merge($db_arr,$currect_db_arr);
        \Yaf_Registry::set('dbconfig',$dbconfig);

        if(!empty($currect_config->get('access'))){
            $currect_access_arr = $currect_config->get('access')->toArray();
            \Yaf_Registry::set('accessconfig',$currect_access_arr);
        }


        return $config;
    }

    static function run($app='o_app')
    {
        define('CURRECT_APPLICATION_PATH', APPLICATION_PATH.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$app);
        static::loadConfig();
        if (php_sapi_name() != 'cli') {
            TimeStack::start();
            $application = new \Yaf_Application(array(
                'application' => array(
                    'directory' => CURRECT_APPLICATION_PATH.DIRECTORY_SEPARATOR.'application',
                    'system' => array(
                        'use_spl_autoload' => 1
                    ),
                    'dispatcher' => array(
                        'catchException' => true
                    ),
                )
            ));
            $application->bootstrap()->run();


        }

    }
}