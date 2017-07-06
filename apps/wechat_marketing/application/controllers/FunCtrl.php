<?php
use Royal\Data\DAO;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Truesign\Service\Wechat_marketing_service\FunService;
use Royal\Prof\TrueSignConst;


class FunCtrlController extends AppBaseController {

    public function indexAction()
    {
        echo '功能管理接口';
    }
    /*
     * @for
     */
    public function DescFunAction($rules = 0)
    {

        $params = $this->getParams(array(),array('rules'));
        if(empty($params['rules'])){
            $params['rules'] = $rules;
        }
        $doService = new FunService();
        $response = TrueSignConst::SUCCESS('初始化功能字段成功');
        $response['response'] = $doService->desc($params);
        $this->output2json($response);
    }
    /*
     * @for
     *
     */
    public function getFunAction()
    {
        $params = $this->getParams(array(),array('rules','document_id','search_sort_by','fun_keyword'));
        $search_sort_by = $this->analysis_search_sort_by($params['search_sort_by']);
        if(!empty($search_sort_by)){
            $page_params = $search_sort_by['page_params'];
            $search_params = $search_sort_by['search_params'];
            $sorter_params = $search_sort_by['sorter_params'];
        }
        if($params['document_id']){
            $search_params['document_id'] = $params['document_id'];

        }
        $doService = new FunService();
        $db_response = $doService->get($params,$search_params,$page_params,$sorter_params);


        if(empty($db_response['statistic']['count']) && !empty($params['fun_keyword'])){
            $this->DescFunAction(1);
        }
        else{
            $response = TrueSignConst::SUCCESS('获取功能信息成功');
            $response['response'] = $db_response;
            $this->output2json($response);
        }



    }
    /*
         * @for
         *
         */
    public function UpdateFunAction(){
        $params = $_POST;
        $doAdapter = new businessAdapter();
        $doDao = new DAO($doAdapter);


        $condition['id'] = $params['document_id'];
        unset($params['document_id']);

        $doService = new FunService();
        $response = TrueSignConst::SUCCESS('更新功能信息成功');
        $response['response'] = $doService->Update($params,$condition);
        $this->output2json($response);
    }

    /*
     * @for 客户信息批量软删除接口
     */
    public function GroupDelFunAction()
    {
        TrueSignConst::OPERATION_lOGIC_ERR('未开放接口');
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



}
