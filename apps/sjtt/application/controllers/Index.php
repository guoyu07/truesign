<?php

class IndexController extends AppBaseController {

    public function indexAction()
    {
        echo '123';
	}
	public function getIndexAction()
    {
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\siteIndexAdapter());
        $db_response = $doDao->readSpecified(array(),array(),array(),array('update_time'=>'desc'));
        $this->output2json($db_response);
    }

    public function updateIndexAction()
    {
        $params = $this->getParams(array('document_id','bg','info'));
        $params['id'] = $params['document_id'];
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\siteIndexAdapter());
        $db_response = $doDao->update($params);
        $this->output2json($db_response);
    }
    public function addIndexAction(){
        $params = $this->getParams(array('bg','info'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\siteIndexAdapter());
        $db_response = $doDao->create($params);
        $this->output2json($db_response);
    }
    public function delIndexAction()
    {
        $params = $this->getParams(array('document_id'));
        $params['id'] = $params['document_id'];
        $params['if_delete'] = 1;
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\siteIndexAdapter());
        $db_response = $doDao->update($params);
        $this->output2json($db_response);
    }
}
