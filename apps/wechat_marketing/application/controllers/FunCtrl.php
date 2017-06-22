<?php
use Royal\Data\DAO;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Truesign\Service\Wechat_marketing_service\FunService;


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
        $this->output2json($doService->desc($params));
    }
    /*
     * @for
     *
     */
    public function getFunAction()
    {
        $params = $this->getParams(array(),array('rules','document_id','fun_keyword','search_sort_by'));
        if(!empty($params['search_sort_by'])){
            $search_sort_by = json_decode($params['search_sort_by'],true);
            $page_param['page_size'] = $search_sort_by['page_size'];
            $page_param['page'] = $search_sort_by['page'];
            unset($search_sort_by['page_size']);
            unset($search_sort_by['page']);
        }
        else{
            $page_param = array();
        }
        if($params['document_id']){
            $search_param = array('document_id'=>$params['document_id']);

        }
        else{
            $search_param = array();
        }

        foreach ($search_sort_by as $k=>$v){

            if(empty($v) || $k=='vue_search_way'){
                unset($search_sort_by[$k]);
            }
            else{
                self::setParam($k,'prefix',$v,$search_param);

            }

        }
        $search_param['fun_keyword'] = $params['fun_keyword'];
        $page_param['page_size']=1;

        $doService = new FunService();
        $db_response = $doService->get($params,$search_param,$page_param);

        if(empty($db_response['statistic']['count'])){
            $this->DescFunAction(1);
        }
        else{
            $this->output2json($db_response);
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
        $this->output2json($doService->Update($params,$condition));
    }

    /*
     * @for 客户信息批量软删除接口
     */
    public function GroupDelFunAction()
    {
        die(print_r('未开放接口'));
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
