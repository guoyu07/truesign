<?php

class aboutusController extends AppBaseController {

    public function getAboutUsAction()
    {
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\aboutUsAdapter());
        $db_response = $doDao->readSpecified(array(),array());
        $this->output2json($db_response);
	}
    public function updateAboutUsAction()
    {
        $params = $this->getParams(array(),array('document_id','company','tel','site','address'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\aboutUsAdapter());
        if(empty($params['document_id'])){
            unset($params['document_id']);
            $db_response = $doDao->create($params);
        }
        else{
            $params['id'] = $params['document_id'];
            $db_response =$doDao->update($params);
        }

        $this->output2json($db_response);
    }

}
