<?php

namespace ReInit\YafBase;

use Royal\Validator\ParamValidator;
use Royal\Validator\ParamRule;
use Royal\Data\DAOAdapter;

class Controller extends \Yaf_Controller_Abstract {
    public function setBody($data, string $info='', int $code=200){
        $helper = new \Royal\Util\helper();
        $rev=$helper::getBody($data, $info, $code);
        if(IS_CLI) {
            $response = $this->getResponse();
            $response->contentBody = $rev;
        }else{
            $rev['argv']=$this->getData();
            $rev['serv']=[
                'execTime'=>\exeTime(SYS_START_TIME),
                'runMem'=>\run_mem(SYS_MEMORY_USE)
            ];
            r($rev);
        }
        return $rev;
    }
    public function setErr($e,$method,$code=500){
        $this->setBody($e->getMessage(), $method, $code);
    }
    public function getData(){
        try {
            $req = $this->getRequest();
            $raw = $req->getParams();
            if (IS_CLI) {
                return $raw;
            }

            $m = $req->getModuleName();
            $c = $req->getControllerName();
            $a = $req->getActionName();
            $f = APPLICATION_PATH . '/_data/' . $m . '/' . $c . '.php';
            $n = $raw['data'] ?? 'def';
            require_once($f);
            $data = \IndexData::getData($a, $n);
            return $data;
        }catch (\Exception $e){
            throw $e;
        }
    }
    public function getTaskId(){
        return $this->getRequest()->task_id;
    }

    public function getRedis($node){
        return \publics::getRedis($node);
    }
    public function getDB($node){
        return \publics::getDB($node);
    }

    protected function getPageParams() {
        $pageRules = array(
            'page'=>ParamRule::rule('page')->type('int')->defaultValue(1),
            'page_size'=>ParamRule::rule('page_size')->type('int')->defaultValue(10),
        );

        $request = $this->getRequest();
        $params = ParamValidator::paramsFromRequestAndRules($request, $pageRules);
        if ($params['page'] <= 0) {
            $params['page'] = 1;
        }

        return $params;
    }

//    protected function inputIdResult($id) {
//        $this->inputResult(array('id' => $id));
//    }

    protected function inputParamErrorResult() {
        echo json_encode(array('code' => -100, 'desc' => '缺少参数或者参数非法！'));

        \Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

//    protected function inputResult($data = array()) {
//        echo json_encode(array('data' => $data, 'code' => 0));
//        \Yaf_Dispatcher::getInstance()->autoRender(FALSE);
//    }


    protected function inputError($code, $desc) {
        echo json_encode(array('desc' => $desc, 'code' => $code));
        \Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

    protected function _forward($action, $controller = '', $parameters = array()) {
        $this->forward('Index', $controller, $action, $parameters);
    }

    protected function render($tpl, array $parameters = null) {
        $this->display($tpl, $parameters);
    }

//    public function getParams(array $required, array $optional, DAOAdapter $adapter = null) {
//        $paramRules = $adapter ? $adapter->paramRules() : array();
//        $rules = $this->getRules($required, $optional, $paramRules);
//        $request = $this->getRequest();
//        return ParamValidator::paramsFromRequestAndRules($request, $rules);
//    }

//    public function getRequiredParams(array $required, DAOAdapter $adapter = null) {
//        return $this->getParams($required, array(), $adapter);
//    }
//
//    public function getOptionalParams(array $optional, DAOAdapter $adapter = null) {
//        return $this->getParams(array(), $optional, $adapter);
//    }

//    public function getPairParams(array $paramNames, DAOAdapter $adapter = null) {
//        $paramRules = $adapter ? $adapter->paramRules() : array();
//        $rules = $this->getRules(array(), $paramNames, $paramRules);
//        $request = $this->getRequest();
//        return ParamValidator::pairParamsFromRequestAndRules($request, $rules);
//    }

    private function getRules(array $required, array $optional, array $paramRules) {
        $rules = array();
        $paramNames = array_merge($required, $optional);
        foreach ($paramNames as $r) {
            $rule = ParamRule::rule($r);
            if (isset($paramRules[$r])) {
                $rule = $paramRules[$r];
            }

            $rule->required(in_array($r, $required));
            $rules[$r] = $rule;
        }
        return $rules;
    }
}

