<?php
use  \Truesign\Service\Socket_server\MsglogService;
class MsglogController extends ServerAppBaseController {


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

        $doService = new MsglogService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('获取消息日志信息成功');
        $response['response'] = $doService->get($params,$search_params,$page_params,$sorter_params);

        $this->output2json($response);



    }
    public function addAction()
    {
        $params = $this->getParams(array('sender','sendee','apps','type','request','response'));
        $doService = new MsglogService();
        $serverResponse =  $doService->add($params);
        $this->setResponseBody($serverResponse);

    }



}
