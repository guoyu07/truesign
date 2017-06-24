<?php
use Royal\Data\DAO;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Truesign\Service\Wechat_marketing_service\WeimobService;
use \Truesign\Service\Wechat_marketing_service\WeimobContentService;

class WeimobCtrlController extends AppBaseController {

    public function indexAction()
    {
        echo '微信公众号数据';
    }


    /*
     * @for
     */
    public function descWeimobAction()
    {

        $params = $this->getParams(array(),array('rules'));
        $doService = new WeimobService();
        $this->output2json($doService->desc($params));
    }
    /*
     * @for
     *
     */
    public function getWeimobAction()
    {
        $params = $this->getParams(array(),array('rules','document_id','search_sort_by'));
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
        $doService = new WeimobService();
        $this->output2json($doService->get($params,$search_param,$page_param));


    }
    /*
         * @for 客户信息更新、软删除接口
         *
         */
    public function UpdateWeimobAction(){
        $params = $_POST;
        $doAdapter = new businessAdapter();
        $doDao = new DAO($doAdapter);
        $condition['id'] = $params['document_id'];
        unset($params['document_id']);
        $doService = new WeimobService();
        $this->output2json($doService->Update($params,$condition));
    }

    /*
     * @for 客户信息批量软删除接口
     */
    public function GroupDelBusinessInfoAction()
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


    /*
     * @for 客户级别信息 字段获取 新增接口
     *
     */
    public function descWeimobContentAction()
    {
        die(print_r('未开放接口'));
        $params = $this->getParams(array('document_id'),array('rules'));

        $doService = new WeimobContentService();
        $this->output2json($doService->Desc($params));
    }
    /*
     * @for 客户级别信息获取接口
     *
     */
    public function getWeimobContentAction()
    {
        $params = $this->getParams(array(),array('rules','document_id','search_sort_by'));

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
            if(empty($v)){
                unset($search_sort_by);
            }
            else{
                self::setParam($k,'prefix',$v,$search_param);

            }

        }
        $doService = new WeimobContentService();
        $this->output2json($doService->Get($params,$search_param,$page_param));


    }
    /*
     * @for
     *
     */
    public function UpdateWeimobContentAction(){
        die(print_r('未开放接口'));
        $params = $_POST;
        $doAdapter = new businessLevelAdapter();
        $doDao = new DAO($doAdapter);
        $condition['id'] = $params['document_id'];
        unset($params['document_id']);
        $doService = new WeimobContentService();
        $this->output2json($doService->Update($params,$condition));
    }
}
