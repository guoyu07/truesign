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

}
