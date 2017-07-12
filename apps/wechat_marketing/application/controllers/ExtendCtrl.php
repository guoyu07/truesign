<?php
use Royal\Data\DAO;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Truesign\Service\Wechat_marketing_service\FunService;
use Royal\Prof\TrueSignConst;
use \Truesign\Service\Wechat_marketing_service\FingerPrintsService;

class ExtendCtrlController extends AppBaseController {

    public function indexAction()
    {
        echo '综合统计接口';
    }

    public function getFingerPrintsAction()
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
        $doService = new FingerPrintsService();
        $db_response = $doService->get($params,$search_params,$page_params,$sorter_params);


        if(empty($db_response['statistic']['count']) && !empty($params['fun_keyword'])){
            $this->DescFunAction(1);
        }
        else{
            $response = TrueSignConst::SUCCESS('获取指纹信息成功');
            $response['response'] = $db_response;
            $this->output2json($response);
        }



    }


    public function UpdateNoteAction(){
        $params = $this->getParams(array('document_id','note'));
        $doService = new FingerPrintsService();
        $service_reponse = $doService->UpdateNote($params);
        if($service_reponse>0){
            $response = TrueSignConst::SUCCESS('更新备注信息成功');
        }
        else{
            $response = TrueSignConst::OPERATION_lOGIC_ERR('更新备注信息失败');
        }
        $response['response'] = $service_reponse;
        $this->output2json($response);
    }



}
