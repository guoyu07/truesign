<?php

namespace Royal\Data;

use \Exception;

class Remote {
    private static $connections = array(
        'db' => array(),
        'redis' => array(),
        'es' => array(),
        'pheanstalk' => array(),
    );


    public static function getDb($conf,$mysql_user = null) {

        $config_name = $conf;
        $db = $conf;
        $db_name = '';
        if (isset(static::$connections['db'][$config_name])) {
            return static::$connections['db'][$config_name];
        }
        $config = \Yaf_Registry::get('config');
        $dbConfig = $config->db->{$db};

        if (empty($dbConfig)) {
            $dbConfig = \Yaf_Registry::get('dbconfig')[$db];

            if(empty($dbConfig)){
                throw new Exception(sprintf('config of db %s is not found', $config_name), -9999);
            }
            else{
                $dbConfig = (object)$dbConfig;
            }

        }

        $db = new MySQL(array('host' => $dbConfig->host, 'port' => $dbConfig->port,
            'user' => $dbConfig->user, 'pass' => $dbConfig->pass, 'dbname' => (empty($db_name)?$dbConfig->name:$db_name)));
        return static::$connections['db'][$config_name] = $db;
    }

 // 为解决ERP连接数问题,调整此方法
    public static function getDbOld($conf,$mysql_user = null) {
        $config_name = $conf;
        $db = $conf;
        $db_name = '';
        \Yaf_Registry::set('data_base_name','');

        if($conf == 'chow' || $conf == 'erp') {
            $loginInfo = new LoginInfo();
            if(empty($mysql_user)){
                $mysql_user= $loginInfo->getMysqlUser();
            }

            if(!empty($mysql_user)) {
//                $config_name = $mysql_user;
                $data = explode(':',$mysql_user);
                $db = $data[0];
                $db_name = $data[1];
                $config_name = $db;
                \Yaf_Registry::set('data_base_name',$db_name);

            }
        }

        if (isset(static::$connections['db'][$config_name])) {
            return static::$connections['db'][$config_name];
        }

        $config = \Yaf_Registry::get('config');
        $dbConfig = $config->db->{$db};
        if (empty($dbConfig)) {
            throw new Exception(sprintf('config of db %s is not found', $config_name), -9999);
        }
        $db = new MySQL(array('host' => $dbConfig->host, 'port' => $dbConfig->port,
            'user' => $dbConfig->user, 'pass' => $dbConfig->pass, 'dbname' => (empty($db_name)?$dbConfig->name:$db_name)));
        return static::$connections['db'][$config_name] = $db;
    }
    public static function getTable($table)  {
        $db_name = \Yaf_Registry::get('data_base_name');
        if($db_name) {
            return $db_name.'.'.$table;
        }
        else {
            return $table;
        }
    }


    /**
     * @param $name
     * @return ESWrapper
     * @throws Exception
     */
    public static function getEs($name) {
        if (isset(static::$connections['es'][$name])) {
            return static::$connections['es'][$name];
        }

        $config = \Yaf_Registry::get('config');
        $esConfig = $config->es->{$name};
        if (empty($esConfig)) {
            throw new Exception(sprintf('config of es %s is not found', $name), -9999);
        }

        $es = new ESWrapper($esConfig->host);

//        $db = new RoyalDb(array('host'=>$dbConfig->host, 'user'=>$dbConfig->user, 'pass'=>$dbConfig->pass, 'dbname'=>$dbConfig->name));

        return static::$connections['es'][$name] = $es;
    }

    /**
     * @param $name
     * @return Pheanstalk
     * @throws Exception
     */
    public static function getPheanstalk($name) {
        if (isset(static::$connections['pheanstalk'][$name])) {
            return static::$connections['pheanstalk'][$name];
        }

        $config = \Yaf_Registry::get('config');
        $pheanstalkConfig = $config->beanstalkd->{$name};
        if (empty($pheanstalkConfig)) {
            throw new Exception(sprintf('config of beanstalkd %s is not found', $name), -9999);
        }

        $pheanstalk = new Pheanstalk($pheanstalkConfig->host, $pheanstalkConfig->port);

        return static::$connections['pheanstalk'][$name] = $pheanstalk;
    }

    /**
     * @param string $name
     * @return Redis
     * @throws Exception
     */
    public static function getRedis($name = 'web') {
        $config = \Yaf_Registry::get('config');
        $redisConfig = $config->redis->{$name};
        $prefixName = $name;
        if (isset(static::$connections['redis'][$prefixName])) {
            $redis = static::$connections['redis'][$prefixName];
            $redis->select($redisConfig->db);
            return static::$connections['redis'][$prefixName] = $redis;
        }

        $redis = new \Redis();
        if (empty($redisConfig)) {
            throw new Exception(sprintf('config of redis %s is not found', $name), -9998);
        }

        if ($redisConfig->db) {
            $redis->connect($redisConfig->host, $redisConfig->port);
            if($redisConfig->pass) {
                $redis->auth($redisConfig->pass);
            }
            $redis->select($redisConfig->db);
        } else {
            $redis->pconnect($redisConfig->host, $redisConfig->port);
            if($redisConfig->pass) {
                $redis->auth($redisConfig->pass);
            }
            $redis->select($redisConfig->db);
        }
        return static::$connections['redis'][$prefixName] = $redis;
    }

    public static function close($type, $name) {
        if (isset(static::$connections[$type])) {
            if($type =='redis') {
                $redis = static::$connections[$type][$name];
                $redis->close();

//                var_dump(12);
            }
            unset(static::$connections[$type][$name]);
        }
    }
}


