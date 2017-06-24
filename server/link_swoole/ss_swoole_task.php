<?php

$file_path = dirname(__FILE__).'/ss_swoole_config.json';
$json_string = file_get_contents($file_path);
$config = json_decode($json_string);
$service_port = $config->service_port;
$address = $config->address;
$dsn = $config->dsn; //构造数据源，mysql是数据类型，localhost是主机地址，shadow_manage是数据库名称
$db_user = $config->db_user; //数据库用户名
$db_password = $config->db_password; //登录数据库的密码
$interval_time = $config->interval_time; //向SSServer添加端口号的间隔时间
$redis = new \Redis();
$redis_reponse = $redis->pconnect($config->redis_ip,$config->redis_port);
$auth = $redis->auth('zhuotong');
var_dump($auth);
if($redis_reponse){
    echo '连接到redis '.$config->redis_ip.' 服务器'.PHP_EOL;
}
//全局存储器，以防数据库故障
$saver = array();

$client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_ASYNC);
$client->on("connect", function(swoole_client $cli) use($dsn,$db_user,$db_password,$address,$redis) {
    echo 'connect UDP to '.$address.PHP_EOL;
    $ss_member = json_decode($redis->get('ss_member'),true);
    if(sizeof($ss_member)>0){
        echo '存在需要添加的端口数据:'.json_encode($ss_member).PHP_EOL;
        foreach($ss_member as $array){
            $attr = array(
                'server_port' => (int)$array['port'],
                'password' => $array['conn_pass']
            );
            $jsonAttr = 'add:'.json_encode($attr);
            $cli->send($jsonAttr);

        }
        $redis->del('ss_member');
        echo '需要添加的端口数据发送完成，已清空redis -> ss_member'.PHP_EOL;
    }
    else{
        echo '不存在需要添加的端口数据'.PHP_EOL;
    }

});
$client->on("receive", function(swoole_client $cli, $data)  use($dsn,$db_user,$db_password){
        echo "base---------------->Receive: $data\n";
//        updateData($data,$dsn,$db_user,$db_password);
        sleep(1);
    });
$client->on("error", function(swoole_client $cli){
    echo "error\n";
});
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";
});
$client->connect($address, $service_port);

//每5秒遍历一遍数据库，把所有端口都添加到ssserver中,从第5秒开始
swoole_timer_tick($interval_time,function() use($service_port,$address,$dsn,$db_user,$db_password,$redis){
    $client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_ASYNC);
    $client->on("connect", function(swoole_client $cli) use($dsn,$db_user,$db_password,$redis){
        echo 'loop->connect'.PHP_EOL;
        $ss_member_reload = json_decode($redis->get('ss_member_reload'),true);
        if(sizeof($ss_member_reload)>0){
            echo '存在需要reload端口:'.json_encode($ss_member_reload).PHP_EOL;
            foreach($ss_member_reload as $k=>$port){
                $attr = array(
                    'server_port' => (int)$port
                );
                $jsonAttr = 'remove:'.json_encode($attr);
                $cli->send($jsonAttr);
            }
            $redis->del('ss_member_reload');
            echo '需要reload的删除端口数据发送完成，已清空redis -> ss_member_reload'.PHP_EOL;
        }
        else{
            echo '不存在需要reload的端口数据'.PHP_EOL;

        }
        $ss_member = json_decode($redis->get('ss_member'),true);
        if(sizeof($ss_member)>0){
            echo '存在需要添加的端口数据:'.json_encode($ss_member).PHP_EOL;
            foreach($ss_member as $array){
                $attr = array(
                    'server_port' => (int)$array['port'],
                    'password' => $array['conn_pass']
                );
                $jsonAttr = 'add:'.json_encode($attr);
                $cli->send($jsonAttr);

            }
            $redis->del('ss_member');
            echo '需要添加的端口数据发送完成，已清空redis -> ss_member'.PHP_EOL;
        }
        else{
            echo '不存在需要添加的端口数据'.PHP_EOL;
        }




    });
    $client->on("receive", function(swoole_client $cli, $data)  use($dsn,$db_user,$db_password){
        echo "loop---------------->Receive: : $data\n";
        updateData($data,$dsn,$db_user,$db_password);
        sleep(1);
    });
    $client->on("error", function(swoole_client $cli){
        echo "error\n";
    });
    $client->on("close", function(swoole_client $cli){
        echo "Connection close\n";
    });
    $client->connect($address, $service_port);
});


/**
 * 更新端口号的数据流量
 */
function updateData($json,$dsn,$db_user,$db_password){
    global $saver;

    echo json_decode($json);
    $stat = 'stat: ';
    if(!strstr($json, $stat)){
        return;
    }
    $json = str_replace($stat, '', $json);
    try {
        $db = new PDO($dsn,$db_user,$db_password);
    } catch (Exception $e) {
        echo "插入数据库连接失败\n$json\n";
        array_push($saver, json_decode($json));
        echo count($saver)."条数据暂存\n";
        return;
    }

    cleanGlobalSaver();
    $array = json_decode($json);
    if(empty($array)){
        return;
    }
    foreach ($array as $key => $value) {
        try{
            //向数据库中记录一条流量
            date_default_timezone_set('PRC');
            $currentTime = "'".date('Y-m-d H:i:s',time())."'";
            $values = $key.','.time().','.$currentTime.','.$value;
            $sqlFlow = 'insert into app_ss_ssflow_log(port,time,date_time,flow) values('.$values.')';
            $db->exec($sqlFlow);
            echo $sqlFlow.'\n';

            //向数据库中记录总流量
            $sql = 'select * from app_ss_ssmember where port='.$key;
            $query = $db->query($sql);
            $query->setfetchmode(pdo::FETCH_ASSOC); //设置数组关联方式
            $result = $query->fetchAll();
            if(!empty($result)){
                foreach($result as $data){
                    echo "数据流量是：$value\n";
                    $flow = (int)$data['flow']+$value;
                    $update = 'update app_ss_ssmember set flow = '
                        .$flow.' where port = '.$data['port'];
                    $db->exec($update);
                    // echo "\n查询的结果是".$value['port'];
                }
            }
        }catch(PDOException $e){

        }

    };
    $db = null;
}

//清空未入库的数据
function cleanGlobalSaver()
{
    global $dsn,$db_user,$db_password,$saver;
    if (empty($saver)) {
        return;
    }
    $count = count($saver);
    foreach ($saver as $index => $dict) {
        foreach ($dict as $key => $value) {
            try {
                date_default_timezone_set('PRC');
                $currentTime = "'".date('Y-m-d H:i:s',time())."'";
                $values = $key.','.time().','.$currentTime.','.$value;
                $sqlFlow = 'insert into app_ss_ssflow_log(port,time,date_time,flow) values('.$values.')';
                $db = new PDO($dsn,$db_user,$db_password);
                $db->exec($sqlFlow);
                unset($saver[$index]);
            } catch (Exception $e) {
            }
        }
    }
    echo "清空未入库数据:"."($count) 条\n";
}