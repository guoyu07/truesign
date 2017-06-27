<?php
use Royal\Data\DAO;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Truesign\Adapter\wechat_marketing\businessLevelAdapter;
use \Truesign\Service\Wechat_marketing_service\BusinessService;
use \Truesign\Service\Wechat_marketing_service\BusinessLevelService;

class BusinessCtrlController extends AppBaseController {


    public function indexAction()
    {
        echo '客户数据';
	}


	/*
	 * @for客户信息新增字段获取接口
	 */
    public function descBusinessinfoAction()
    {
        $params = $this->getParams(array(),array('rules'));
        $doService = new BusinessService();
        $this->output2json($doService->desc($params));
	}
    /*
     * @for 获取客户信息接口
     *
     */
    public function getBusinessInfoAction()
    {
        $params = $this->getParams(array(),array('rules','document_id','search_sort_by'));
        $search_sort_by = $this->analysis_search_sort_by($params['search_sort_by']);
        if(!empty($search_sort_by)){
            $page_params = $search_sort_by['page_params'];
            $search_params = $search_sort_by['search_params'];
            $sorter_params = $search_sort_by['sorter_params'];
        }


        if($params['document_id']){
            $search_params['document_id'] = $params['document_id'];

        }

        $doService = new BusinessService();
        $this->output2json($doService->get($params,$search_params,$page_params,$sorter_params));


    }
    /*
         * @for 客户信息更新、软删除接口
         *
         */
    public function UpdateBusinessInfoAction(){
        $params = $_POST;
        $doAdapter = new businessAdapter();
        $doDao = new DAO($doAdapter);
        $condition['id'] = $params['document_id'];
        $doService = new BusinessService();
        $this->output2json($doService->Update($params,$condition));
    }

    /*
     * @for 客户信息批量软删除接口
     */
    public function GroupDelBusinessInfoAction()
    {
        $params = $this->getParams(array('ids'));

        if(!empty($params['ids'])){
            $params_ids = explode(',',$params['ids']);
        }
        else{
            $params_ids = array();
        }
        $doAdapter = new businessAdapter();
        $doDao = new DAO($doAdapter);
        $updatedata = [];
        foreach ($params_ids as $k=>$v){
            $updatedata_item['id'] = $v;
            $updatedata_item['if_delete'] = 1;
            $updatedata[] = $updatedata_item;
        }
        $doService = new BusinessService();

        $this->output2json($doService->GroupDel($params));

    }


    /*
     * @for 客户级别信息 字段获取 新增接口
     *
     */
    public function descBusinessinfoLevelAction()
    {
        $params = $this->getParams(array(),array('rules'));

        $doService = new BusinessLevelService();
        $this->output2json($doService->Desc($params));
    }
    /*
     * @for 客户级别信息获取接口
     *
     */
    public function getBusinessInfoLevelAction()
    {
        $params = $this->getParams(array(),array('rules','document_id','search_sort_by'));

        $search_sort_by = $this->analysis_search_sort_by($params['search_sort_by']);
        if(!empty($search_sort_by)){
            $page_params = $search_sort_by['page_params'];
            $search_params = $search_sort_by['search_params'];
            $sorter_params = $search_sort_by['sorter_params'];
        }
        if($params['document_id']){
            $search_params = array('document_id'=>$params['document_id']);
        }

        $doService = new BusinessLevelService();
        $this->output2json($doService->Get($params,$search_params,$page_params,$sorter_params));


    }
    /*
     * @for 客户级别信息 更新 软删除接口
     *
     */
    public function UpdateBusinessInfoLevelAction(){
        $params = $_POST;
        $doAdapter = new businessLevelAdapter();
        $doDao = new DAO($doAdapter);
        $condition['id'] = $params['document_id'];
        $doService = new BusinessLevelService();
        $this->output2json($doService->Update($params,$condition));


    }
}
