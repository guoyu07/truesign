<?php
use \Truesign\Adapter\wechat_marketing\siteBaseConfigAdapter;
use Truesign\Adapter\wechat_marketing\envAdapter;
use Royal\Data\DAO;
use \Truesign\Service\Wechat_marketing_service\EnvService;
use \Truesign\Service\Wechat_marketing_service\MasterService;
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
        $service_reponse = $doService->Get($params);
        $response = \Royal\Prof\TrueSignConst::SUCCESS('数据更新成功');
        $response['response']=$service_reponse;
        $this->output2json($response);
	}

    public function descSiteBaseConfigInfoAction($rules = 0)
    {


        $params = $this->getParams(array(),array('rules'));
        if(empty($params['rules'])){
            $params['rules'] = $rules;
        }
        $doService = new SiteBaseConfigService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('初始化配置信息项成功');
        $response['response'] = $doService->Desc($params);
        $this->output2json($response);
	}
	public function getSiteBaseConfigAction(){

        $params = $this->getParams(array(),array('rules'));

        $doService = new SiteBaseConfigService();
        $db_resposne = $doService->Get($params,array(),array('page_size'=>1));
        if(empty($db_resposne['statistic']['count'])){
            $this->descSiteBaseConfigInfoAction(1);

        }
        else{
            $response = \Royal\Prof\TrueSignConst::SUCCESS('数据获取成功');
            $response['response'] = $db_resposne;
            $this->output2json($response);
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
        $response = \Royal\Prof\TrueSignConst::SUCCESS('管理员账户信息获取成功');
        $response['response'] = $doService->Update($params,$condition);
        $this->output2json($response);
    }


    /*
     *
     * */
    public function descMasterAction($rules = 0)
    {


        $params = $this->getParams(array(),array('rules'));
        if(empty($params['rules'])){
            $params['rules'] = $rules;
        }
        $doService = new MasterService();
        $this->output2json($doService->Desc($params));
    }
    public function getMasterAction(){

        $params = $this->getParams(array(),array('document_id','rules'));

        $doService = new MasterService();
        $db_resposne = $doService->Get($params,array(),array('page_size'=>1));
        $response = \Royal\Prof\TrueSignConst::SUCCESS('管理员账户信息获取成功');
        $response['response'] = $db_resposne;
        $this->output2json($response);

    }
    /*
     *
     */
    public function updateMasterAction()
    {
        $params = $_POST;
        $doService = new MasterService();
        $this->output2json($doService->Update($params,$condition));
    }

}
