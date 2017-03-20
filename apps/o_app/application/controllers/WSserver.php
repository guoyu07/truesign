<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class WSserverController extends  oAppBaseController
{
    public function initConnAction(){
        $params = $this->getParams(array('unique_auth_code','ua','ip','authway'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());

        $preParams = [];
        $preParams['unique_auth_code'] = $params['unique_auth_code'];
        $preParams['user_agent'] = $params['ua'];
        $preParams['ip'] = $params['ip'];
        $preParams['authway'] = $params['authway'];

        $conditionParams['unique_auth_code'] = $params['unique_auth_code'];
        $db_response = $doDao->insertOrupdate($preParams,$conditionParams);
        if(!empty($db_response)){
            $response = [];
            $response['id']=$db_response;
            $response['unique_auth_code']= $params['unique_auth_code'];
            $response['init_status']= 1;
        }
        else{
            $response['init_status']= 0;
        }

        $this->setResponseBody($response);
    }





}