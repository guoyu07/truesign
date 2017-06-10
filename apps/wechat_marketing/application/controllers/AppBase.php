<?php

use Royal\Validator\ParamValidator;
use Royal\Validator\ParamRule;

class AppBaseController extends \ReInit\YafBase\Controller
{
    public function init()
    {
        $this->doInit();
    }

    public function tableaccess_ctrl()
    {
        $config = Yaf_Registry::get('accessconfig');
        return $config['wechat_marketing']['tableaccess'];
    }

    public function AuthTableAccess($access,$tableaccess,$way='both')
    {
        if('both' == $way){
            if(intval($tableaccess['read'])>intval($access) || intval($tableaccess['write'])>intval($access)){
                throw new Exception('无权限操作此表');
            }
        }
        elseif ('read' == $way){
            if(intval($tableaccess['read'])>intval($access)){
                throw new Exception('无权限读取此表');
            }
        }
        elseif ('write' == $way){
            if(intval($tableaccess['write'])>intval($access)){
                throw new Exception('无权更改此表');
            }
        }
    }

    public function filterRules(&$rules,$db_data_row,$rules_show=0)
    {
        $request_db_param = array();
        if($db_data_row && !empty($rules_show)){
            foreach ($db_data_row as $k=>$v){
                $request_db_param[] = $k;
            }
            foreach ($rules as $k=>$v){
                if(!in_array($k,$request_db_param)){
                    unset($rules[$k]);
                }
            }
        }
        if(empty($db_data_row) && !empty($rules_show)){
            return;
        }
        if(empty($rules_show)){
            $rules = '';
        }



    }
    public function getAccess(){
        $params = $this->getParams(array('token'));
        $token = $params['token'];
        return $token;

    }
    public function doInit()
    {

    }
    public function getParams(array $required, array $optional = array(), \Royal\Data\DAOAdapter &$adapter = null)
    {

        $paramRules = $adapter ? $adapter->paramRules() : array();
        $rules = $this->getRules($required, $optional, $paramRules);

        $request = $this->getRequest();
//
        $params = ParamValidator::paramsFromRequestAndRules($request, $rules);
//
        return $params;
    }

    public function getParamsAll()
    {
        $request = $this->getRequest();
//        $params = $request->getParams(); //文档这么写，但是测试并拿不到参数 php7.1.2 环境下
        $getparams =  $_GET;
        $postparams = $_POST;
        $params = array_merge($getparams,$postparams);
        return $params;
    }
    private function getRules(array $required, array $optional, array $paramRules)
    {
        $rules = array();
        $paramNames = array_merge($required, $optional);
        foreach ($paramNames as $r) {
            $rule = ParamRule::rule($r);
            if (isset($paramRules[$r])) {
                $rule = $paramRules[$r];
            }
            $rule->required(in_array($r, $required));
            $param = $this->getRequest()->get($rule->name);
            if (in_array($r, $optional)) {

                if ($param !== null) {
                    $rules[$r] = $rule;
                }
            } else {
                $rules[$r] = $rule;
            }
        }
        return $rules;
    }
    protected function inputErrorResult($code)
    {
        $desc = ErrorCode::errorMsgByCode($code);
//        $desc = $model->getErrorText($code);
        $this->inputError($code, $desc);
    }
    protected  function output2json($result){
        echo json_encode($result,JSON_ERROR_DEPTH );
    }

    protected function inputError($code, $msg)
    {
        echo json_encode(array('code' => $code, 'desc' => $msg));
        \Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }




    public function setResponseBody($data, string $info='', int $code=200){
        $helper = new \Royal\Util\helper();
        $rev=$helper::getBody($data, $info, $code);

        if(IS_CLI) {
//            $response = $this->getResponse();
//            $response->contentBody = $rev;
            $response = $this->getResponse();

//            $request = $this->getRequest();
            $response_data['datatype']=gettype($rev);
            $response_data['response_data']=$rev;
            $response->contentBody=$response_data;
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


}
