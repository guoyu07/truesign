<?php
use  \Truesign\Service\Socket_server\AuthlogService;
class AuthlogController extends ServerAppBaseController {


	public function indexAction() {

	    $this->throwException(\Royal\Prof\TrueSignConst::SUCCESS('User'));
	}


    /*
     * @for 获取客户信息接口
     *
     */
    public function getAction()
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

        $doService = new AuthlogService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('获取认证日志信息成功');
        $response['response'] = $doService->get($params,$search_params,$page_params,$sorter_params);

        $this->output2json($response);



    }
    /*
         * @for 客户信息更新、软删除接口
         *
         */
    public function UpdateAction(){
        $response = \Royal\Prof\TrueSignConst::ACCESS_DENIED('禁止操作日志记录');
        $this->output2json($response);
    }


}
