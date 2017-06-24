<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class funUseageAdapter extends DbLibraryAdapter
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
        return 'fun_useage';

    }

    public function tableDesc()
    {
        return '公众号功能使用记录表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('内容id')
            ->def('fun_id')->map('fun_id')->varchar(100)->desc('功能关键词')
            ->def('fun_title')->map('fun_title')->varchar(100)->desc('功能关键词')
            ->def('wechat_id')->map('wechat_id')->varchar(100)->desc('微信号')->able_modify(false)->able_show(false)
            ->def('interaction_content_json')->map('interaction_content_json')->text()->desc('交互内容')
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