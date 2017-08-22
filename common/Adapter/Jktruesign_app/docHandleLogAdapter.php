<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/10/15
 * Time: 下午4:50
 */

namespace Truesign\Adapter\Jktruesign_app;

use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class docHandleLogAdapter extends DbLibraryAdapter
{
    public function belongApp()
    {
        return 'jktruesign_app';
    }
    public function database()
    {
        return 'jktruesign_app';
    }
    public function dbConfig(){
        return 'jktruesign_app';
    }
    public function table_Prefix()
    {
        return null;
    }
    public function tableAccess()
    {
        return 2;
    }

    public function table()
    {
        return 'doc_handle_log';

    }

    public function tableDesc()
    {
        return '文档类型';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('uid')->map('uid')->varchar(100)->desc('用户id')
            ->def('doc_id')->map('doc_id')->varchar(100)->desc('文档id')
            ->def('doc_name')->map('doc_name')->varchar(100)->desc('文档名称')
            ->def('handletype')->map('handletype')->varchar(100)->desc('操作类型')
            ->widgetType('radio',array('',array('请求解析','请求下载')))

            ->end();
    }

    public function tableAdd()
    {
        
    }
    public function tableModify()
    {
        // TODO: Implement tableModify() method.
    }

    public function tableDel()
    {

    }
    static function show(){
        print('showme');
    }
}
