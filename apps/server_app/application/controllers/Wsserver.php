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
        $params = $this->getParams(array('fd','unique_auth_code','ua','ip','authway'),array('note','point_key','receive_key'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());

        $preParams = [];
        $preParams['fd'] = $params['fd'];
        $preParams['unique_auth_code'] = $params['unique_auth_code'];
        $preParams['user_agent'] = $params['ua'];
        $preParams['ip'] = $params['ip'];
        $preParams['authway'] = $params['authway'];
        $preParams['point_key'] = $params['point_key'];
        $preParams['receive_key'] = $params['receive_key'];
        if(!empty($params['note'])){
            $preParams['note'] = $params['note'];
        }

        $conditionParams['unique_auth_code'] = $params['unique_auth_code'];
        $db_response = $doDao->insertOrupdate($preParams,$conditionParams);
        if(!empty($db_response)){
            $response = [];
//            $response['id']=$db_response;
            $response['unique_auth_code']= $params['unique_auth_code'];
            $response['ip']= $params['ip'];
            $response['init_status']= json_encode($preParams);
        }
        else{
            $response['init_status']= 0;
        }

        $this->setResponseBody($response);
    }


    public function gettoidbypointkeyAction()
    {

        $params = $this->getParams(array('point_key'));

        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());
        $db_reponse = $doDao->readSpecified(array('receive_key'=>$params['point_key']),array('fd'));
        $to_id = [];
        if(!empty($db_reponse['data'])){
            foreach ($db_reponse['data'] as $k=>$v){
                if(!empty($v['fd'])){
                    $to_id[] = (int)$v['fd'];
                }

            }
        }
        $this->setResponseBody($to_id);
    }

    public function indexAction()
    {


    }


}