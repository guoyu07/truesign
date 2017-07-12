<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class funAdapter extends DbLibraryAdapter
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
        return 'fun';

    }

    public function tableDesc()
    {
        return '公众号功能表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('内容id')
            ->def('fun_keyword')->map('fun_keyword')->varchar(100)->desc('功能关键词')->regex('/^.{0,20}$/')->issearch(true)
            ->def('fun_title')->map('fun_title')->varchar(100)->desc('功能标题')->regex('/^.{0,20}$/')->issearch(true)
            ->def('fun_uri')->map('fun_uri')->varchar(1000)->desc('功能链接')->regex('/^.{0,500}$/')
            ->def('fun_checked')->map('fun_checked')->int()->desc('处理/查看标志')->able_modify(false)->able_show(false)
            ->def('fun_desc')->map('fun_desc')->text()->desc('功能描述')->issearch(true)
            ->def('fun_enable')->map('fun_enable')->int()->desc('功能启用量')->able_modify(false)
            ->def('fun_usage')->map('fun_usage')->int()->desc('功能使用量')->able_modify(false)
            ->def('fun_adapter')->map('fun_adapter')->int()->desc('功能对应表')->regex('/^.{0,50}$/')
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