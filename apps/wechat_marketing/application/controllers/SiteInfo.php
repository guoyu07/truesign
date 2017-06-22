<?php
use \Truesign\Adapter\wechat_marketing\siteBaseConfigAdapter;
use Truesign\Adapter\wechat_marketing\envAdapter;
use Royal\Data\DAO;
use \Truesign\Service\Wechat_marketing_service\EnvService;
use \Truesign\Service\Wechat_marketing_service\SiteBaseConfigService;
class SiteInfoController extends AppBaseController {


    public function indexAction()
    {
        echo '1231';
	}

    public function getOSSCfgAction()
    {
        $config = Yaf_Registry::get('config');
        $oss =   $config->get('cdn')->toArray();
        echo json_encode($oss);
    }
    public function getEnvAction()
    {
        $params = $this->getParams(array(),array('rules','server_help'));
        $doService = new EnvService();
        $this->output2json($doService->Get($params));
	}

    public function descSiteBaseConfigInfoAction($rules = 0)
    {

        $params = $this->getParams(array(),array('rules'));
        if(empty($params['rules'])){
            $params['rules'] = $rules;
        }
        $doService = new SiteBaseConfigService();
        $this->output2json($doService->Desc());
	}
	public function getSiteBaseConfigAction(){

        $params = $this->getParams(array(),array('rules'));

        $doService = new SiteBaseConfigService();
        $db_resposne = $doService->Get($params,array(),array('page_size'=>1));
        if(empty($db_resposne['statistic']['count'])){
            $this->descSiteBaseConfigInfoAction(1);
        }
        else{
            $this->output2json($db_resposne);
        }

    }

    /*
     * sitebaseconfig adapter 数据更新接口
     */
    public function updateSiteBaseConfigAction()
    {
        $params = $_POST;
        $condition['id'] = $params['document_id'];
        unset($params['document_id']);
        $doService = new SiteBaseConfigService();
        $this->output2json($doService->Update($params,$condition));
    }


}
