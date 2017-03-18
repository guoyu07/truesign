<?php

use Halo\Base\Controller;
use Halo\Data\DAOAdapter;
use Halo\Data\MYSQL;
use Halo\Validator\ParamRule;
use Halo\Validator\ParamValidator;
use Eagle\Service\RedisHelper;

class oAppBaseController extends \ReInit\YafBase\Controller
{
    public function init()
    {
        $this->doInit();
    }

    public function doInit()
    {

    }
    public function getParams(array $required, array $optional = array(), \Royal\Data\DAOAdapter &$adapter = null,$check_must=false)
    {

        $paramRules = $adapter ? $adapter->paramRules() : array();
        $rules = $this->getRules($required, $optional, $paramRules);


//print_r($rules);
        $request = $this->getRequest();

        $params = ParamValidator::paramsFromRequestAndRules($request, $rules);

        /*
         * 检查自定义必填项中的相关参数
         * @time ： 2016-12-01 16:12:34
         * @auth : ql_os
         */

        if($check_must){

            $AppRequestSource = \Yaf_Registry::get("AppRequestSource");

            $sApiName = php_sapi_name();
            if($AppRequestSource=='[app]' && $sApiName != 'cli'){


                if(!empty($params['type'])){

                    if( Yaf_Registry::get('judge_must_write')=='') {
                        $judge_must_write = $params['type'].';';
                        Yaf_Registry::set('judge_must_write',$judge_must_write);
                    }
                    else{
                        $judge_must_write = Yaf_Registry::get('judge_must_write').$params['type'].';';
                        Yaf_Registry::set('judge_must_write',$judge_must_write);
                    }

                }

                if(!empty($params['purpose'])){

                    if(Yaf_Registry::get('judge_must_write')=='') {
                        $judge_must_write = $params['purpose'].';';
                        Yaf_Registry::set('judge_must_write',$judge_must_write);
                    }
                    else{
                        $judge_must_write = Yaf_Registry::get('judge_must_write').$params['purpose'].';';
                        Yaf_Registry::set('judge_must_write',$judge_must_write);
                    }


                }
                $judge_must_write = Yaf_Registry::get('judge_must_write');
                $judge_must_write_status = substr_count($judge_must_write,';');

                if($judge_must_write_status===2){
                    $judge_must_write_arr = explode(';',$judge_must_write);
                    foreach ($judge_must_write_arr as $k=>$v){
                        if(in_array($v,array('求购','求租'))){
                            $judge_key = $v;
                        }
                        //                    elseif(in_array($v,array('住宅','别墅','写字楼','商铺','仓库','厂房','车位','土地'))){
                        elseif(in_array($v,array('出售','出租','租售'))){
                            $judge_key = $judge_must_write_arr[1-$k];
                        }
                    }

                    $judge_must_param = self::checkmastwrite($judge_key,$required);

                    if(!empty($judge_must_param)){
                        $judge_must_param = json_decode($judge_must_param,1);
//                        throw new Exception(json_encode($judge_must_param,256),-199);
                        foreach ($judge_must_param as $k=>$v){
                            /*
                             * 屏蔽2.0没有项目
                             */

                            if(in_array($v,array('kitchen','usable_area','business','air_type','level','room','floor'))){
                                unset($judge_must_param[$k]);
                            }
                            /*
                             * 屏蔽自定义必填项
                             */
                            if(is_numeric($v)){
                                unset($judge_must_param[$k]);
                            }
                        }
                        $required = array_merge($required,$judge_must_param);

                        foreach ($optional as $k=>$v){
                            if(in_array($v,$judge_must_param)){

                                unset($optional[$k]);

                            }
                        }
                        $this->getParams($required,$optional,$adapter);
                    }


                }
                else{
                    Yaf_Registry::set('judge_must_write','');
                }

            }
        }

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
        echo json_encode($result);
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
//            $response_data['request']=$request;
            $response_data['response']=$rev;
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
