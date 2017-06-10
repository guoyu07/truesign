<?php
use \Truesign\Adapter\wechat_marketing\siteBaseConfigAdapter;
use Truesign\Adapter\wechat_marketing\envAdapter;
use Royal\Data\DAO;

class SiteInfoController extends AppBaseController {


    public function indexAction()
    {
        echo '1231';
	}

    public function getEnvAction()
    {
        $params = $this->getParams(array(),array('rules'));
        $helper = new \Royal\Util\helper();

        $doAdapter = new envAdapter();
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();
        $doDao = new DAO($doAdapter);
        $env = $helper::sysinfo();
        $data = [];
        foreach ($env as $k=>$v){
            if($k != 'IP'){
                $data['server_env'] = $data['server_env'].' '.$k.':'.$v;
            }
        }
        $data['server_ip'] = $env['IP'];

        $doDao->insertOrupdate($data,array('server_ip'=>$env['IP']));
        $db_resposne = $doDao->read();
        $this->filterRules($rules,$db_resposne['data'][0],$params['rules']);

        $access_rules = array('tableaccess'=>$table_access,'rules'=>$rules);
        $db_resposne['access_rules'] = $access_rules;
        $this->output2json($db_resposne);
	}
	public function getBaseSiteConfigAction(){

        $params = $this->getParams(array(),array('rules'));
        $doAdapter = new siteBaseConfigAdapter();
        $doDao = new DAO($doAdapter);
        $table_access = $doAdapter->getTableAccess();
        $rules = $doAdapter->paramRules();

        if($this->tableaccess_ctrl()){
            $access = $this->getAccess();
            $this->AuthTableAccess($access,$table_access);
        }
        $db_resposne = $doDao->readSpecified(array(),array());
        $this->filterRules($rules,$db_resposne['data'][0],$params['rules']);

        $access_rules = array('tableaccess'=>$table_access,'rules'=>$rules);
        $db_resposne['access_rules'] = $access_rules;
        $this->output2json($db_resposne);
    }


}
