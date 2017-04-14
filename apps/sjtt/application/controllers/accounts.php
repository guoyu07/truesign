<?php

class accountsController extends AppBaseController {

    public function LoginAction()
    {
        $params = $this->getParams(array('username','pass'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\accountAdapter());
        $db_response = $doDao->readSpecified($params,array());
        $auth_response = [];
        if($db_response['data'][0]){
            $document_id = $db_response['data'][0]['document_id'];
            $username = $params['username'];
            $encryption_response = \Royal\Util\Decrypt::encryption($document_id.','.$username.','.$params['pass'].','.$db_response['data'][0]['img'],'sjtt',0);
            $auth_response['response'] = 1;
            $auth_response['encryption_msg'] = $encryption_response;
        }
        else{
            $auth_response['response'] = 0;

        }
        $this->output2json($auth_response);
    }

    public function getAccountAction()
    {
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\accountAdapter());
        $db_response = $doDao->readSpecified(array(),array());
        $this->output2json($db_response);
    }

    public function updateAccountAction()
    {
        $params = $this->getParams(array('document_id'),array('username','img','pass','tel','email','ip'));
        $params['id'] = $params['document_id'];
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\accountAdapter());
        $db_response = $doDao->update($params);
        $this->output2json($db_response);
    }
    public function addAccountAction(){
        $params = $this->getParams(array('username','pass'),array('img','tel','email','ip'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\accountAdapter());
        $db_response = $doDao->create($params);
        $this->output2json($db_response);
    }
    public function delAccountAction()
    {
        $params = $this->getParams(array('document_id'));
        $params['if_delete'] = 1;
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\accountAdapter());
        $db_response = $doDao->update($params);
        $this->output2json($db_response);
    }
}
