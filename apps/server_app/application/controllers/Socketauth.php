<?php
use Royal\Prof\TrueSignConst;
use  \Truesign\Service\Socket_server\UserService;

class SocketauthController extends ServerAppBaseController
{

    public function indexAction()
    {

        $this->output2json(\Royal\Prof\TrueSignConst::SUCCESS('serverauth_index'));
    }

    public function connAction()
    {
        $params = $this->getParams(array('sysinfo'));
        if (!empty($params['sysinfo'])) {
            $params['sysinfo']['user_agent'] = str_replace('\"', '-', $params['sysinfo']['user_agent']);
            $params_conn = $params['sysinfo'];
            $doService = new \Truesign\Service\Socket_server\AuthlogService();
            $search_params['ip'] = $params_conn['ip'];
//            $search_params['user_agent'] = $params_conn['user_agent']; /*ql-error:加上后会出现ua查询不出的情况存在一些问题*/
            $search_params['unique_auth_code'] = $params_conn['unique_auth_code'];
            $server_reponse = $doService->insertOrupdate($params_conn, $search_params);
            $yaf_reponse = $server_reponse;
        } else {
            $yaf_reponse = \Royal\Prof\TrueSignConst::EMPTY_PARAMS();
        }
//        $this->setResponseBody(\Royal\Prof\TrueSignConst::SUCCESS(json_encode($params_conn,256)));
        $this->setResponseBody(\Royal\Prof\TrueSignConst::SUCCESS(json_encode($search_params, JSON_UNESCAPED_SLASHES)));
    }

    public function authAction()
    {
        $params = $this->getParams(array('username', 'password', 'unique_auth_code','authway'));
        $doService = new \Truesign\Service\Socket_server\AuthlogService();
        $yaf_response = $doService->Auth($params);
        $this->setResponseBody($yaf_response);
    }
    public function disAuthAppAction(){
        $params = $this->getParams(array('fd'));
        if(!empty($params['fd'])){

            $doService = new \Truesign\Service\Socket_server\AuthlogService();
            $service_response = $doService->disAuth($params['fd']);
            $this->setResponseBody($service_response);
        }
        else{
            $this->setResponseBody(\Royal\Prof\TrueSignConst::EMPTY_PARAMS('断线fd参数不能为空'));
        }

    }
    public function update_appsAction()
    {
        $params = $this->getParams(array('app', 'unique_auth_code'));
        $search_params['unique_auth_code'] = $params['unique_auth_code'];
        if(!empty($params['app'])){
            foreach ($params['app'] as $k=>$v){
                $params['app'][$k] = json_decode($v,true);
            }
        }
        $doService = new \Truesign\Service\Socket_server\AuthlogService();
        $params['app'] = empty($params['app']) ? "" : json_encode($params['app']);
        $server_reponse = $doService->insertOrupdate(array('app' => $params['app']), $search_params);
        $this->setResponseBody($server_reponse);
    }

    public function getUserByFdAction()
    {
        $params = $this->getParams(array('fds'),array());
        $fds = $params['fds'];
        if(empty($fds)){
            $this->setResponseBody(TrueSignConst::EMPTY_PARAMS('fds参数为空'));
        }
        else{
            $doService = new \Truesign\Service\Socket_server\AuthlogService();
            $yaf_response = $doService->getUserByFd($params['fds']);
            $this->setResponseBody($yaf_response);
        }
    }
}
