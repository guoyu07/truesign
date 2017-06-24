<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class HandleAppsController extends  OAppBaseController
{
    public function getAppRuleAction(){
        $apprule = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appRuleAdapter());
        $rules = $apprule->readSpecified(array(),array('id','appname','apptype','applevel','appimg','apptitle','apptable'));
        $this->output2json($rules);
        $this->setResponseBody($rules);
    }

    public function updateAppRuleAction()
    {
        $params = $this->getParams(array('document_id'),array('appname','applevel','appimg','apptitle'));
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