<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;
use Truesign\Service\Wechat_marketing_service\PayInterfaceService;


class codeLogicAdapter extends DbLibraryAdapter
{
    public function database()
    {
        return 'wechat_marketing';
    }
    public function dbConfig(){
        return 'wechat_marketing';
    }
    public function table_Prefix()
    {
        return 'app_';
    }
    public function tableAccess()
    {
        return array('read'=>9,'write'=>9);
    }

    public function table()
    {
        return 'code_logic';

    }

    public function tableDesc()
    {
        return '代码逻辑日志表';
    }


    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('logic_level')->map('logic_level')->int()->desc('逻辑级别')->widgetType('radio',array('',array('debug','warning','error','kernel_error')))
            ->def('class')->map('class')->varchar(100)->desc('类名')
            ->def('method')->map('method')->varchar(100)->desc('方法名')
            ->def('function')->map('function')->varchar(100)->desc('函数名')
            ->def('file')->map('file')->varchar(1000)->desc('所在文件路径')
            ->def('line')->map('line')->int()->desc('所在行号')
            ->def('debug_backtrace')->map('debug_backtrace')->text()->desc('请求日志')
            ->def('logic_desc')->map('logic_desc')->varchar(1000)->desc('逻辑描述')
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