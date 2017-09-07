<?php

/**
 * Create by Smartisan
 * @name NoteController
 * @author placeless
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
use Truesign\Service\Note\NoteService;
class NoteController extends AppBaseController
{
    /**
     * 控制器初始化
     * 该函数会在控制器对象实例化之后被调用。
     * 进行初始化操作如打开session
     */
    public function init()
    {
    }
    /**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/Sample/index/index/index/name/placeless 的时候, 你就会发现不同
     */
    public function indexAction($name = "Stranger")
    {
        echo "Note";
        echo '<hr>';
        echo 'hello world';
        return false;
    }
    
    
	/*
	 * 获取表规则
	 */
    public function descNoteAction()
    {
        $params = $this->getParams(array(),array('rules'));
        $doService = new NoteService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('初始文字的故事化信息成功');
        $response['response'] = $doService->desc($params);
        $this->output2json($response);
	}
    /*
     * 查询信息
     *
     */
    public function getNoteAction()
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

        $doService = new NoteService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('获取文字的故事信息成功');
        $response['response'] = $doService->get($params,$search_params,$page_params,$sorter_params);
        $this->output2json($response);


    }
    /*
         * 更新信息
         *
         */
    public function UpdateNoteAction(){
        $params = $_POST;
        $condition['id'] = $params['document_id'];
        unset($params['create_time'],$params['update_time']);
        $doService = new NoteService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('更新文字的故事信息成功');
        $response['response'] = $doService->Update($params,$condition);
        $this->output2json($response);
    }

    /*
     * 批量删除
     */
    public function GroupDelNoteAction()
    {
        $params = $this->getParams(array('ids'));

        if(!empty($params['ids'])){
            $params_ids = explode(',',$params['ids']);
        }
        else{
            $params_ids = array();
        }
        $updatedata = [];
        foreach ($params_ids as $k=>$v){
            $updatedata_item['id'] = $v;
            $updatedata_item['if_delete'] = 1;
            $updatedata[] = $updatedata_item;
        }
        $doService = new NoteService();
        $response = \Royal\Prof\TrueSignConst::SUCCESS('批量删除文字的故事成功');
        $response['response'] = $doService->GroupDel($params);
        $this->output2json($response);

    }


  
    
}

