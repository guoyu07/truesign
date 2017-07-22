<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2017/2/3
 * Time: 上午8:07
 */

namespace server\link_swoole;


use \Royal\Data\resources;
use Yaf\Exception;

class new_socket_server{
    public $host;
    public $port;
    public $sw;
    public $yaf;
    public $env;
    public $uname;
    public $table;
    public $packType; // 0=json, 1=msgpack
    public $packMaxLen;
    public $server_ip;
    public $res_mq_log;
    public $config;
    public $redis;
    public $redis_host;
    public $redis_port;


    public $shadowsocks_client;
    public $ids=[];
    public function __construct(string $uname, string $configFile='/config/service.ini', string $env='websocket'){
        if (!defined('IS_CLI')) {
            throw new \Exception('no set IS_CLI');
        }
        if (!defined('APPLICATION_PATH')) {
            throw new \Exception('no set APPLICATION_PATH');
        }
        $this->uname = $uname;
        $this->env = $this->initEnv($env);
        $this->server_ip=current(swoole_get_local_ip());
        $config = $this->getConfig(APPLICATION_PATH . $configFile);
        $this->config = $config;
//        var_dump($config);
        $config['log_file']=sprintf($config['log_file'],$uname);
        $this->host = $config['host'];
        $this->port = (int)$config['port'];
        $this->redis_host = $config['redis_host'];
        $this->redis_port = (int)$config['redis_port'];


        unset($config['host'], $config['port']);
        $config['package_max_length'] = intval($config['package_max_length']);
        $this->packMaxLen=$config['package_max_length'];
        $this->sw = new \swoole_websocket_server($this->host, $this->port);

        var_dump($config);
        $this->sw->set($config);
        $this->bind($config);

//        $this->table = new \swoole_table(1024);
//        $this->table->column('id', \swoole_table::TYPE_INT, 4);       //1,2,4,8
//        $this->table->create();

    }
    public function run(){

        $this->sw->start();



    }

    /*
     * 工具层函数
     */
    public function onPacket($serv, $data, $client_info){
        $this->log("->[onPacket]");
    }
    public function onTimer($serv, $interval){
        $this->log("->[onTimer]");
    }
    public function bind($config){
        $this->sw->on('Start',[$this,'onStart']);
        $this->sw->on('Close',[$this,'onClose']);
        $this->sw->on('Connect',[$this,'onConnect']);
        $this->sw->on('Receive',[$this,'onReceive']);
        $this->sw->on('Shutdown',[$this,'onShutdown']);
        $this->sw->on('WorkerStop',[$this,'onWorkerStop']);
        $this->sw->on('WorkerStart',[$this,'onWorkerStart']);
        $this->sw->on('WorkerError',[$this,'onWorkerError']);
        $this->sw->on('ManagerStop',[$this,'onManagerStop']);
        $this->sw->on('ManagerStart',[$this,'onManagerStart']);
        $this->sw->on("open",[$this,"onOpen"]);
        $this->sw->on("message",[$this,"onMessage"]);


        if(isset($config['task_worker_num']) && boolval($config['task_worker_num'])){
            $this->sw->on('Task',[$this,'onTask']);
            $this->sw->on("Finish",[$this,"onFinish"]);

        }
//
    }
    public function onFinish(\swoole_server $serv, $task_id, $data){
        echo "【s】onFinish \n";

//        echo "implode('|',[$this->task_id,'onFinish',$task_id]) \n";
//        $this->log(implode('|',[$this->task_id,'onFinish',$task_id]));
    }
    public function getConfig($file, $isSelf=true){
        $config = new \Yaf_Config_Ini($file, $this->env);
        if($isSelf){
            return $config->get($this->uname)->toArray();
        }
        return $config;
    }
    static function loadConfig() {
        //配置节点
//        $section = 'dbserver';
        //如果在phpunit环境中，则加载phpunit的配置节点
        if(defined('IN_PHPUNIT') && IN_PHPUNIT == true){
            $section = 'phpunit';
        }
        $config = new \Yaf_Config_Ini(APPLICATION_PATH.'/config/application.ini', false);
        \Yaf_Registry::set('config', $config);
        return $config;
    }
    public function initEnv($env){
        $def ='product';
        $env = !$env ? get_cfg_var('yaf.environ') : $env;
        if(!$env){
            $env=$def;
        }
        $env=strtolower($env);
        if(in_array($env,['product','develop','test','beta','websocket'])){

            return $env;
        }
        return $def;
    }
    public function pack($data){
        try {
            if (!$data) {
                throw new \Exception('pack');
            }
            $typ = intval($this->packType === 'msgpack');
            $msg = $this->packFun($data);
            $end = pack("NC", strlen($msg), $typ) . $msg;
            if (strlen($end) > $this->packMaxLen) {
                throw new \Exception('pack');
            }
            return $end;
        }catch(\Exception $e){
            throw new \Exception('pack', 6001);
        }
    }
    public function unpack($data){
        try{
            if (!$data) {
                throw new \Exception('unpack');
            }

            $res=unpack("Nlen/Ctyp", $data);
            if(!$res){
                throw new \Exception('unpack');
            }
            $len=intval($res['len']);
            $typ=intval($res['typ']);
            $this->packType = $typ === 1 ? 'msgpack' : 'json';
            $msg = substr($data, -$len);
            $rev=$this->packFun($msg, true);
            $this->checkRev($rev);
            return $rev;
        } catch (\Exception $e){
            throw new \Exception('unpack',6000);
        }
    }
    private function checkRev($data){
        if(!$data){
            throw new \Exception('checkRev');
        }
        $arr=['source','count','task_pid','data'];
        foreach ($arr as $v){
            if(!isset($data[$v])){
                throw new \Exception('checkRev');
            }
        }

        if($data['count']!=count($data['data'])){
            throw new \Exception('checkRev');
        }

        $arr1=['module','controller','action','data'];
        foreach ($data['data'] as $key=>$item){
            foreach ($arr1 as $v){
                if(!isset($item[$v])){
                    throw new \Exception('checkRev');
                }
            }
        }
        return true;
    }
    private function packFun($data,$isun=false){
        if($isun){
            if($this->packType==='json'){
                return json_decode($data, true);
            }else{
                return msgpack_unpack($data);
            }
        }else{
            if($this->packType==='json'){
                return json_encode($data, JSON_UNESCAPED_UNICODE);
            }else{
                return msgpack_pack($data);
            }
        }
    }
    private function handleCounts($s_task_id,$s_task_pid,$rev,$data,$client){
        $serv=explode('|', $data['serv']);
        if(!$rev){ //处理错误500情况
            $rev=[
                'source'=>'unknown',
                'count'=>0
            ];
            $data=[
                'code'=>500
            ];
            $serv=[$serv[0],'msgpack',0,0,0,0,$serv[3],$serv[4]];
        }
        $rs=[
            'task_id'=>$s_task_id,
            'task_pid'=>$s_task_pid, //用来处理子请求,之后可以用这个看全部请求链
            'source'=>$rev['source'],
            'count'=>$rev['count'],
            'code'=>$data['code'],
            'serv_pack'=>$serv[1],
            'serv_work_wid'=>$serv[5],
            'serv_exec_time'=>$serv[7],
            'serv_start_time'=>$serv[6],
            'client'=>$client,
            'items'=>[]
        ];
        unset($data['code'],$data['serv']);
        foreach ($data as $k=>$v){
            $tmp = explode('|',$v['serv']);
            $rou = $rev['data'][$k];
            $rs['items'][]=[
                'var_name'=>$k,
                'code'=>$v['code'],
                'info'=>$v['info'],
                'serv_task_wid'=>$tmp[1],
                'serv_work_wid'=>$tmp[2],
                'serv_exec_time'=>$tmp[4],
                'serv_start_time'=>$tmp[3],
                'serv_rout_module'=>$rou['module'],
                'serv_rout_controller'=>$rou['controller'],
                'serv_rout_action'=>$rou['action'],
                'serv_rout_data'=>json_encode((array)$rou['data'],JSON_UNESCAPED_UNICODE)
            ];
        }
//		echo (print_r($rs, 1));
        //send MQ
        $routing_key='';
        try {
            $this->res_mq_log->publish(msgpack_pack($rs), $routing_key, AMQP_NOPARAM, ['delivery_mode' => 2]);    //消息持久化，在投递时指定 delivery_mode => 2（1 是非持久化）
        }catch (\Exception $e){
            $this->log(implode('|',[$s_task_id,'sendMqLog-Error',$e->getMessage()]));
        }
        return true;
    }
    public function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }
    public function log($msg,$control=0){
        $msg=$msg.PHP_EOL;
        switch($control){
            case 0:
                echo $msg;
                break;
            case 1:
            case 2:
                die($msg);
                break;
            case 3:
            case 4:
                echo $msg;
                error_log($msg,3,"/var/log/php-{$this->uname}.log");
                break;
            default:
                echo $msg;
        }
    }
    /*
     * 系统层函数
     */
    public function onStart(\swoole_server $serv){
        echo "【s】onStart \n";
        $this->log("->[onStart] SERVER_TYPE=".$this->env." PHP=".PHP_VERSION." Yaf=".\YAF_VERSION." swoole=".SWOOLE_VERSION." Master-Pid={$this->sw->master_pid} Manager-Pid={$this->sw->manager_pid}");
        swoole_set_process_name("php-{$this->uname}:master");
    }
    public function onWorkerStart($serv, $worker_id){
            /*
                 * 初始化 db yaf redis 等
             */


            $this->ids=[]; // reload时清空
            if ($serv->taskworker) {
                $type = 'task';


            } else {
                $type = 'work';
            $loader=\Yaf_Loader::getInstance(APPLICATION_PATH.'/application/library');
            $loader::import('helper.php');
            if(empty($this->yaf)){
                $this->initYaf();
            }

            $this->heartbeat($serv,'');

        }
        swoole_set_process_name("php-{$this->uname}:{$type}:" . $worker_id);
        $this->log("->[on<".$type.">Start] Type: {$type} WorkerId: {$worker_id} WorkerPid: {$serv->worker_pid}");
    }
    public function onWorkerStop($serv, $worker_id){
        $this->log("->[onWorkerStop] WorkerId: {$worker_id}");
    }
    public function onWorkerError($serv, $worker_id, $worker_pid, $exit_code){
        $this->log("->[onWorkerError] WorkerId: {$worker_id} WorkerPid: {$worker_pid} ExitCode: {$exit_code}");
    }
    public function onManagerStart($serv){
        $this->log("->[onManagerStart]");
        swoole_set_process_name("php-{$this->uname}:manager");
    }
    public function onManagerStop($serv){
        $this->log('->[onManagerStop]');
    }
    public function onShutdown($serv){
        $this->log("->[onShutdown]");
    }
    public function onPipeMessage($serv, $from_worker_id, $message){
        $this->log("->[onPipeMessage] FromWorkerId: {$from_worker_id} Message:".strlen($message));
    }

    /*
     * 业务层函数
     */
    public function onOpen( $serv , $request ){
        echo "【s】onOpen=> \n";
        echo "【s】server=> \n";
        echo "【s】request=> \n";
        $header_app = $request->server['path_info'];
        $query_string = $request->server['query_string'];
        parse_str($query_string,$query_arr);
        echo $query_string.PHP_EOL;
        var_dump($query_arr);
        if(!empty($query_arr['unique_auth_code'])){
            echo "存在唯一识别id\n";

            $unique_auth_code = strtoupper($query_arr['unique_auth_code']);
        }
        else{

            $unique_auth_code = strtoupper(session_create_id());

            echo "生成唯一识别id->".$unique_auth_code.PHP_EOL;

        }
        $serv->task([
            'to' => [$request->fd],
            'except' => [],
            'data' => json_encode(array('type'=>'open','msg'=>array('unique_auth_code'=>$unique_auth_code,'cid'=>$request->fd)))
        ]);



    }
    public function onConnect(\swoole_server $serv, $fd, $from_id){
        echo '【onConnect】'."\n";
    }
    public function onReceive(\swoole_server $serv, $fd, $from_id, $data){
        echo "【s】onReceive \n";

    }
    public function onMessage( $serv , $request ){
        echo '====================》【onmessage】'.PHP_EOL;
        $receive = json_decode($request->data,true);
        $to_id = [];
        $from = [
            'fd_type'=>'client',
            'fd'=>$request->fd,
        ];
        $to = [
            'id_type'=>'client',
            'id'=>$to_id
        ];
        if(!empty($to_id)){
            $me_id = $to_id;
//            $me_nickname = $this->table->get($to);
        }
        else{
            $me_id = 'unknow';
//            $me_nickname = 'unknow';
        }
        $me = [
            'id'=>$me_id,
//            'nickname'=>$me_nickname
        ];
        $msg = [];
        if(empty($to_id)){
            $task = [
                'to' => [],
//            'except' => [$request->fd],
                'except' => [],
                'data' => $msg
            ];
        }
        else{
            $to_id = gettype($to_id) == 'array'?$to_id:[$to_id];

            $task = [
                'to' => $to_id,
//            'except' => [$request->fd],
                'except' => [],
                'data' => $msg
            ];
        }



        $serv->task($task);



    }
    public function onTask(\swoole_server $serv, $task_id, $src_worker_id, $data){
        echo "【s】onTask \n";


        if (count($data['to']) > 0) {
            echo "存在指定接收人,开始遍历发送消息".PHP_EOL;
            echo json_encode($data);

            $clients = $data['to'];
            foreach ($clients as $fd) {
                if (!in_array($fd, $data['except'])) {
                    $serv->push($fd,$data['data'],2);
                }
            }
        }
        else{

            echo "循环向所有人发送消息\n";

            foreach($serv->connections as $fd)
            {
                echo "当前fd=>".$fd."\n";
                $serv->push($fd,$data['data'],2);
            }
        }

        /*
         * 处理shadowsocks服务端通信
         */

        $this->sw->reload();


    }

    public function onClose(\swoole_server $serv, $fd, $from_id){
        echo "【s】onCLose \n";
//        $this->log(implode('|',[$this->ids[$fd],'onClose',$fd,$from_id]));
        $this->unlinkfd($fd);
        unset($this->ids[$fd]);
//        $this->table->del($fd);
        echo 'fd=>'.$fd;
    }

    /*
     * 调用yaf层函数
     */
    public function initYaf($app='server_app'){
//        echo "initYaf".PHP_EOL;
        define('CURRECT_APPLICATION_PATH', APPLICATION_PATH.DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$app);
        $configs=new \Yaf_Config_Ini(APPLICATION_PATH.'/config/business.ini','common');
        \Yaf_Registry::set('config', $configs);
        $application = ['application'=>$configs->get('application')->toArray()];
        $application['application']['directory']=APPLICATION_PATH.'/apps/'.$app.'/application';
        if(empty($this->yaf)){
            $this->yaf=new \Yaf_Application($application);
            $this->yaf->bootstrap();
        }

    }
    private function buildYaf($moudle,$controller,$action,$payload_data,$s_task_id=1,$payload_type='未定义'){
        $yaf_payload = [];
        $yaf_payload['moudle']=$moudle;
        $yaf_payload['controller']=$controller;
        $yaf_payload['action']=$action;
        $yaf_payload['data']=$payload_data;
        $yaf_payload['s_task_id']=$s_task_id;
        return $yaf_payload;
    }
    private function buildMsg($from,$to,$me,$data,$type,$status = 200){
        $response_data = array();
        $response_data['response']=$data;
//        $response_data['user_list']=$user_list;
        $relation = array();
        $response_data['relation']['from']=$from;
        $response_data['relation']['to']=$to;
        $response_data['relation']['me']=$me;
        $msg = json_encode([
            'status' => $status,
            'type' => $type,
            'data' => (object)($response_data)
        ]);
        return $msg;
    }
    public function runYaf(array $datas,$reload = true){
//        echo "[s]runYaf=>\n";


        ['module'=>$module,'controller'=>$controller,'action'=>$action,'data'=>$data,'s_task_id'=>$taskId]=$datas;
        $request = new \Yaf_Request_Simple('CLI', $module, $controller, $action, $data);
        $request->task_id=$taskId;

        $response = $this->yaf->getDispatcher()->returnResponse(true)->dispatch($request);

//        $this->sw->reload();

        return $response->contentBody;
    }



    /*
     * 处理断开掉线情况
     */
    public function unlinkfd($fd)
    {


    }

    /*
     * 处理心跳
     */
    public function heartbeat($serv,$request)
    {
        if($request){
            $fd = $request->fd;
            if(!empty($fd)){
                $serv->tick(1500, function() use ($fd, $serv) {
                    $chat_list = $this->getChatList();
                    $ping_reponse = array();
                    $ping_reponse['type'] = 'ping';
                    $ping_reponse['accompany'] = [];
                    $serv->push($fd,json_encode($ping_reponse),2);
                });
            }
        }
        else{

            foreach($serv->connections as $fd)
            {
                $serv->tick(1500, function() use ($fd, $serv) {
                    $chat_list = $this->getChatList();
                    $ping_reponse = array();
                    $ping_reponse['type'] = 'ping';
                    $ping_reponse['accompany'] = [];
                    $serv->push($fd,json_encode($ping_reponse),2);
                });
            }


        }

    }

    /*
     * 获取聊天列表 并通过心跳发送
     */
    public function getChatList(){
        $yaf_payload = self::buildYaf('index', 'website', 'getchatlist', array());
        $db_response = $this->runYaf($yaf_payload);
        return $db_response;
    }
}