<?php
namespace Royal;
abstract class Bootstrap {

    static function loadConfig() {
        //配置节点
        $section = 'dbserver';
        //如果在phpunit环境中，则加载phpunit的配置节点
        if(defined('IN_PHPUNIT') && IN_PHPUNIT == true){
            $section = 'phpunit';
        }
        $config = new \Yaf_Config_Ini(APPLICATION_PATH.'/config/business.ini', $section);
        \Yaf_Registry::set('config', $config);
        return $config;
    }

    static function run($app)
    {
        static::loadConfig();

        $application = new \Yaf_Application(array(
            'application' => array(
                'directory' => APPLICATION_PATH . '/apps/'.$app.'/application',
                'system' => array(
                    'use_spl_autoload' => 1
                ),
                'dispatcher' => array(
                    'catchException' => true
                ),
            )
        ));

        $application->bootstrap();

        if (php_sapi_name() != 'cli') {
            $application->run();
        }

    }
}