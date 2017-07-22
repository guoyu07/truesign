<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class appsController extends  OAppBaseController
{
    public function getAppRuleAction(){
        $apprule = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appRuleAdapter());
        $rules = $apprule->readSpecified(array(),array('id','appname','apptype','applevel','appimg','apptitle','apptable'),array(),array('applevel'=>'asc'));
//        $this->output2json($rules);
        $this->setResponseBody($rules);
    }
    public function checkLoginAction(){
        $params = $this->getParams(array('unique_auth_code','encryption_key'),array());
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());
        $needData = $doDao->readSpecified(array(
            'unique_auth_code'=>$params['unique_auth_code']
        ),array());
        if($needData['data'][0]['document_id']){
            $decode_encryption_key = \Royal\Crypt\Decrypt::encryption($params['encryption_key'],$needData['data'][0]['document_id'],1);
            if(sizeof($decode_encryption_key) == 2){
                [0=>$tmp_unique_auth_code,1=>$limit_time]=$decode_encryption_key;
                if($limit_time>time()){
                    if($tmp_unique_auth_code == $params['unique_auth_code'] && !empty($needData['data'][0]['apps'])){
                        $re_check['status'] = 1;
                        $re_check['note'] = '验证成功';
                    }
                    elseif(empty($needData['data'][0]['apps'])){
                        $re_check['status'] = 0;
                        $re_check['note'] = '上次连接已经断开';
                    }
                    else{
                        $re_check['status'] = 0;
                        $re_check['note'] = '唯一验证key不一致';
                    }
                }
                else{
                    $re_check['status'] = 0;
                    $re_check['note'] = '加密key已经过期';
                }
            }
            else{
                $re_check['status'] = 0;
                $re_check['note'] = '加密key违法';
            }
        }
//
//        ['0'=>$encryption_key,'1'=>$limit_time] = \Royal\Util\Decrypt::en
        $this->setResponseBody($re_check);
    }
    public function bindappsAction(){
        $params = $this->getParams(array('apps','key','pass','unique_auth_code'),array('note'));

        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appCtrlLevelAdapter());
        $db_response = $doDao->readSpecified(
            array('`key`'=>$params['key'],'`pass`'=>$params['pass']),
            array('document_id','nickname','level','img')
        );
        if($db_response['data']){
            $update_param = [];
            if(!empty($params['note'])){
                $update_param['note']=$params['note'];

            }
            $update_param['apps']=implode(';',$params['apps']);
            $update_param['ctrlid']=$db_response['data'][0]['document_id'];
            $update_param['ctrlname']=$db_response['data'][0]['nickname'];
            $update_param['ctrllevel']=$db_response['data'][0]['level'];
            $condition_param['unique_auth_code'] = $params['unique_auth_code'];
            if(self::appsLevelCheck($db_response['data'][0]['level'],$params['apps'])){
                $auth_dao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());
                $auth_log_db_response = $auth_dao->readSpecified(array('unique_auth_code'=>$params['unique_auth_code']),array('document_id'));
                $db_auth_response = $auth_dao->updateByQuery($update_param,$condition_param);
                if($db_auth_response){
                    $db_auth_response = \Royal\Crypt\Decrypt::encryption($params['unique_auth_code'],$auth_log_db_response['data'][0]['document_id'],0);
//                    $db_auth_response = \Royal\Util\Decrypt::encryption('CQ9UVVwHCEIHVlhFDlsLR0BWQAJaEEVdClAYUgIJV1VWAlYBBw==',$db_response['data'][0]['document_id'],1);
                }
                unset($db_response['data'][0]['pass']);
                $this->setResponseBody(array(
                    'bind_status'=>$db_auth_response,
                    'note'=>'权限通过',
                    'access_user'=>$db_response['data'][0],
                    'isbindapps'=>$params['apps']
                ));
            }
            else{
                $this->setResponseBody(array(
                    'bind_status'=>0,
                    'note'=>'权限级别低'

                ));

            }

        }
        else{
            $this->setResponseBody(array(
                'bind_status'=>0,
                'note'=>'无法验证账户'

            ));
        }


    }
    public static function appsLevelCheck($accessLevel,$apps){
        $regex_rule = '/@.+/';
        $app_level_arr = [];
        foreach ($apps as $item){
            preg_match($regex_rule,$item,$res);
            $app_level_arr[] = str_replace('@','',$res[0]);
        }
        if($accessLevel >= (int)max($app_level_arr)){
            return true;
        }
        else{
            return false;
        }
    }

    public function getFdByAppAction(){
        $params =  $this->getParams(array('app'),array());
        $searchParam['apps'] = array('operation'=>'prefix','value'=>$params['app']);
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());
        $db_response = $doDao->readSpecified($searchParam,array('fd'));
        if(!$db_response['data'][0]['fd']){
            $db_response['data'] = 'none';
        }
        $this->setResponseBody($db_response['data']);
    }

    public function disconnAppAction()
    {
        $params = $this->getParams(array(),array('fd'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appAuthLogAdapter());
        $db_resposne = $doDao->updateByQuery(array('fd'=>'','apps'=>'','ctrlid'=>'','ctrlname'=>'','ctrllevel'=>''),$params);
        $this->setResponseBody($db_resposne);
    }

    public function updateAppRuleAction()
    {
        $params = $this->getParams(array('document_id'),array('appname','applevel','appimg','apptitle','apptable'));
        $params['id'] = $params['document_id'];
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appRuleAdapter());
        $db_reponse = $doDao->update($params);
        if(!empty($db_reponse)){
            $res_reponse['status']=1;
            $res_reponse['msg']='app更新成功';
        }
        else{
            $res_reponse['status']=0;
            $res_reponse['msg']='app更新失败';
        }
        $this->setResponseBody($res_reponse);
    }

    public function addAppRuleAction()
    {
        $params = $this->getParams(array(),array('appname','applevel','appimg','apptitle','apptype','apptable'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appRuleAdapter());
        $db_reponse = $doDao->create($params);
        if(!empty($db_reponse)){
            $res_reponse['status']=1;
            $res_reponse['msg']='app增加成功';
        }
        else{
            $res_reponse['status']=0;
            $res_reponse['msg']='app增加失败';
        }
        $this->setResponseBody($res_reponse);
    }

    public function delAppRuleAction()
    {
        $params = $this->getParams(array('document_id'),array('appname','applevel','appimg','apptitle','apptype','apptable'));
        $params['id'] = $params['document_id'];
        $params['if_delete'] = 1;
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appRuleAdapter());

        $db_reponse = $doDao->update($params);
        if(!empty($db_reponse)){
            $res_reponse['status']=1;
            $res_reponse['msg']='app删除成功';
        }
        else{
            $res_reponse['status']=0;
            $res_reponse['msg']='app删除失败';
        }
        $this->setResponseBody($res_reponse);
    }



}