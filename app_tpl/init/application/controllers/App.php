<?php
use  \Truesign\Service\Socket_server\AppService;
class AppController extends ServerAppBaseController {


	public function indexAction() {

	    $this->throwException(\Royal\Prof\TrueSignConst::SUCCESS('User'));
	}

    public function descAction($rules=0)
    {
        $params = $this->getParams(array(),array('rules'));
        if(empty($params['rules'])){
            $params['rules'] = $rules;
        }
        $doService = new AppService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('初始化客户信息成功');
        $response['response'] = $doService->desc($params);
        $this->output2json($response);
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

        $doService = new AppService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('获取客户信息成功');
        $response['response'] = $doService->get($params,$search_params,$page_params,$sorter_params);

        $this->output2json($response);



    }
    /*
         * @for 客户信息更新、软删除接口
         *
         */
    public function UpdateAction(){
        $params = $_POST;
        $condition['id'] = $params['document_id'];
        $doService = new AppService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('更新客户信息成功');
        $response['response'] = $doService->Update($params,$condition);
        $this->output2json($response);
    }


}
