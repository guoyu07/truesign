<?php

class ProductController extends AppBaseController {

    public function getTypeAction()
    {
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productTypeAdapter());
        $db_response = $doDao->readSpecified(array(),array());
        $this->output2json($db_response);
    }
    public function updateTypeAction()
    {
        $params = $this->getParams(array('document_id','type'));
        $params['id'] = $params['document_id'];
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productTypeAdapter());
        $db_response = $doDao->update($params);
        $this->output2json($db_response);
    }
    public function addTypeAction()
    {
        $params = $this->getParams(array('type'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productTypeAdapter());
        $db_response = $doDao->create($params);
        $this->output2json($db_response);
    }
    public function delTypeAction()
    {
        $params = $this->getParams(array('document_id'));
        $params['id']= $params['document_id'];
        $params['if_delete'] = 1;
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productTypeAdapter());
        $db_response = $doDao->update($params);
        $this->output2json($db_response);
    }
    public function getProductAction()
    {
        $params = $this->getParams(array('type_id'),array('document_id'));
        if(empty($params['type_id'])){
            unset($params['type_id']);
        }
        elseif(empty($params['document_id'])){
            unset($params['document_id']);
        }
        $doDao_producttype = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productTypeAdapter());
        $db_product_response = $doDao_producttype->readSpecified(array('document_id'=>$params['type_id']),array());
        $type_name = $db_product_response['data'][0]['type'];
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productAdapter());
        $db_response = $doDao->readSpecified($params,array(),array(),array('sort_ord'=>'asc','update_time'=>'desc'));
        foreach ($db_response['data'] as $k=>$v){
            $db_response['data'][$k]['type'] = $type_name;
        }
        $this->output2json($db_response);
    }
    public function updateProductAction()
    {
        $params = $this->getParams(array('document_id'),array('img','title','note','info','sort_ord'));
        $params['id']=$params['document_id'];
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productAdapter());
        $db_response = $doDao->update($params);
        $this->output2json($db_response);
    }
    public function addProductAction(){
        $params = $this->getParams(array('type_id','img','title','note','info','sort_ord'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productAdapter());
        $db_response = $doDao->create($params);
        $this->output2json($db_response);
    }
    public function delProductAction()
    {
        $params = $this->getParams(array('document_id'));
        $params['id'] = $params['document_id'];
        $params['if_delete'] = 1;
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Sjtt\productAdapter());
        $db_response = $doDao->update($params);
        $this->output2json($db_response);
    }
}
