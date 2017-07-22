<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class ShadowsocksController extends  OAppBaseController
{
    public function getSSNodeAction(){
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Shadowsocks\appSSNodesAdapter());
        $ssnode = $doDao->readSpecified(array(),array());
//        throw new Exception(json_encode($ssnode),-199);
        foreach ($ssnode['data'] as $k=>$v){
            $ssnode['data'][$k]['re_show_status'] = $ssnode['data'][$k]['server_status'];
        }
        $this->setResponseBody($ssnode);
    }
    public function changeSSNodeAction(){
        $params = $this->getParams(array('type'),array('document_id','server_ip','location','encryption_way','server_status'));
        $type = $params['type'];
        unset($params['type']);
        if(!$params['server_status']){
            $params['server_status'] = 0;
        }
        else{
            $params['server_status'] = 1;
        }
//        if($params['document_id']){
//            $params['id'] = $params['document_id'];
//        }
        unset($params['document_id']);
        $params['if_delete'] = $type==1?0:1;
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Shadowsocks\appSSNodesAdapter());
        $db_reponse = $doDao->insertOrupdate($params,array('server_ip'=>$params['server_ip']));
        if($db_reponse){
            $reponse['status'] = 1;
            $reponse['msg'] = '节点 '.($type=='1'?'更新':'删除').'成功';
            $reponse['type'] = $type;
            $reponse['status'] = $params['status'];
        }
        else{
            $reponse['status'] = 0;
            $reponse['msg'] = '节点 '.($type=='1'?'更新':'删除').'失败';
            $reponse['type'] = $type;

        }
        $this->setResponseBody($reponse);
    }

    public function getSSStatusAction(){
        $params = $this->getParams(array('website_user'),array());
        $website_user =  json_decode( json_encode( $params['website_user'] ), true );
        $params['userid'] = $website_user['document_id'];
        $params['username'] = $website_user['username'];
        $params['email'] = $website_user['email'];
        unset($params['website_user']);
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Shadowsocks\appSSMemberAdapter());
        $auto_arr = array('userid'=>$params['userid'],'username'=>$params['username'],'email'=>$params['email']);
        $db_reponse = $doDao->readSpecified($auto_arr,array(),array(),array(),array(),true);
        if($db_reponse['data']){

            $shadowsocks_status = $db_reponse['data'][0]['status'];
            $reponse['status'] = $shadowsocks_status;
            $reponse['msg'] = ($shadowsocks_status===1?'启动':'停止').'状态';
            $reponse['sysmsg']['port'] = $db_reponse['data'][0]['port'];
            $reponse['sysmsg']['conn_pass'] = $db_reponse['data'][0]['conn_pass'];
        }
        else{
            $reponse['status'] = -1;
            $reponse['msg'] = '停止状态[未初始化]';
        }
        $this->setResponseBody($reponse);
    }

    public function changeSSStatusAction()
    {
        $params = $this->getParams(array('type'),array('conn_pass','port','website_user'));
        $type = $params['type'];
        $website_user =  json_decode( json_encode( $params['website_user'] ), true );
        $params['userid'] = $website_user['document_id'];
        $params['username'] = $website_user['username'];
        $params['email'] = $website_user['email'];
        unset($params['website_user']);
        unset($params['type']);
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Shadowsocks\appSSMemberAdapter());

        $params['status'] = $type;

        $auto_arr = array('userid'=>$params['userid'],'username'=>$params['username'],'email'=>$params['email']);
        $db_reponse = $doDao->readSpecified($auto_arr,array(),array(),array(),array(),true);
        if($db_reponse['data']){
            $db_reponse = $doDao->updateByQuery(array('conn_pass'=>$params['conn_pass'],'status'=>$params['status']),array('userid'=>$params['userid'],'username'=>$params['username'],'email'=>$params['email']));
            if($db_reponse){
                $reponse['status'] = 1;
                $reponse['msg'] = 'shadowsocks '.($type==1?'启动':'关闭').'成功,后端服务器同步中';
            }
            else{
                $reponse['status'] = 0;
                $reponse['msg'] = 'shadowsocks '.$type==1?'启动':'关闭'.'失败';
            }

        }
        else{
            $db_reponse = $doDao->readSpecified(array(),array('port'),array(),array('port'=>'desc'),array(),true);
            if($db_reponse['data'][0]['port']){
                $new_port = random_int(1,8)+$db_reponse['data'][0]['port'];

            }
            else{
                $new_port = 50000;
            }
            $params['port'] = $new_port;
            $db_reponse = $doDao->create($params);
            if($db_reponse){
                $reponse['status'] = 1;
                $reponse['msg'] = 'shadowsocks '.($type==1?'启动':'关闭').'成功,后端服务器同步中';
            }
            else{
                $reponse['status'] = 1;
                $reponse['msg'] = 'shadowsocks '.($type==1?'启动':'关闭').'成功,后端服务器同步中';
            }
        }
        $redis_reponse = \Royal\Data\BaseRedis::get('ss_member_reload');
        if(!empty($redis_reponse)){
            $redis_reponse = json_decode($redis_reponse);
            $redis_reponse[] = $params['port'];
            $redis_reponse = array_unique($redis_reponse);
            \Royal\Data\BaseRedis::set('ss_member_reload',json_encode($redis_reponse));
        }
        else{
            \Royal\Data\BaseRedis::set('ss_member_reload',json_encode(array($params['port'])));

        }
        $this->getSSmemberAction();
        $this->setResponseBody($reponse);

    }

    public function getFlowAllAction(){
        $params = $this->getParams(array(),array('port'));
        $data_title = [date('Y-m-d H:i:s',time())];
        $data_value = [];
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Shadowsocks\appSSMemberAdapter());
        $db_reposne = $doDao->readSpecified(array(),array('flow'));
        if(empty($db_reposne['data'][0]['flow'])){
            $data_value[] = 0;
        }
        else{
            $data_value[] = round((int)($db_reposne['data'][0]['flow'])/1024,2);
        }
        $data_reponse['data_title'] = $data_title;
        $data_reponse['data_value'] = $data_value;
        $this->setResponseBody($data_reponse);
        echo json_encode($data_reponse);
    }
    public function getFlowAction()
    {
        $params = $this->getParams(array(),array('port'));
        $data_title = [];
        $data_value = [];
        if($params['port']){
            $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Shadowsocks\appSSFlowLogAdapter());
            $db_reponse = $doDao->readSpecified(array('port'=>$params['port']),array('time'),array(),array('time'=>'desc'));
            if($db_reponse['data']){
                $now = $db_reponse['data'][0]['time'];
            }
            else{
                $now = time();
            }

            $time_arr = [];
            for($i=0;$i<3;$i++){
                $time_arr[] = $now-$i*60;
            }
            $time_arr = array_reverse($time_arr);
            foreach ($time_arr as $k=>$v){


                $time_start = 0;
                $time_end = $v;

                $search_param = array();
                self::setParam('time','lt',$time_end,$search_param);
                self::setParam('port','eq',$params['port'],$search_param);
                $db_sum = $doDao->dbSum('flow', $search_param,'');
                if(empty($db_sum)){
                   $db_sum = 0;
                }
                else{
                    $db_sum = round($db_sum/1024/1024,2);
                }
                $data_title[] = $time_end;
                $data_value[] = $db_sum;
            }
            foreach ($data_title as $k=>$v){
                $data_title[$k] = date('Y-m-d H:i:s',$v);
            }
            $data_reponse['data_title'] = $data_title;
            $data_reponse['data_value'] = $data_value;
        }
        $this->setResponseBody($data_reponse);
        echo json_encode($data_reponse);
    }


    public function getSSmemberAction()
    {
        $doDao  = new \Royal\Data\DAO(new \Truesign\Adapter\Shadowsocks\appSSMemberAdapter());
        $search_param = [];
        $db_reponse = $doDao->readSpecified(array('status'=>1),array('port','conn_pass'));
        $to_redis_arr = $db_reponse['data'];
        $redis_reponse = \Royal\Data\BaseRedis::set('ss_member',json_encode($to_redis_arr));
//        $this->setResponseBody($redis_reponse);
    }

    public function testAction()
    {
        $redis_reponse = \Royal\Data\BaseRedis::get('ss_member_reload');
        echo $redis_reponse;
    }

}