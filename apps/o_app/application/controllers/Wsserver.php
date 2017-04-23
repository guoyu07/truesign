<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class WsserverController extends  OAppBaseController
{
    public function initConnAction(){
        $params = $this->getParams(array('fd','unique_auth_code','ua','ip','authway'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());

        $preParams = [];
        $preParams['fd'] = $params['fd'];
        $preParams['unique_auth_code'] = $params['unique_auth_code'];
        $preParams['user_agent'] = $params['ua'];
        $preParams['ip'] = $params['ip'];
        $preParams['authway'] = $params['authway'];

        $conditionParams['unique_auth_code'] = $params['unique_auth_code'];
        $db_response = $doDao->insertOrupdate($preParams,$conditionParams);
        if(!empty($db_response)){
            $response = [];
//            $response['id']=$db_response;
            $response['unique_auth_code']= $params['unique_auth_code'];
            $response['ip']= $params['ip'];
            $response['init_status']= 1;
        }
        else{
            $response['init_status']= 0;
        }

        $this->setResponseBody($response);
    }





}