<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class businessLevelAdapter extends DbLibraryAdapter
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
        return 'business_level';

    }

    public function tableDesc()
    {
        return '商户/公司/客户 级别表';
    }


    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')->able_modify(false)
            ->def('level_name')->map('level_name')->varchar(100)->desc('级别名称')->issearch(true)
            ->def('level_color')->map('level_color')->varchar(100)->desc('级别颜色')->widgetType('color')
            ->def('hide_call_num')->map('hide_call_num')->int()->desc('隐号通话数量')->issorter(true)->regex('/^\d+$/')
            ->def('sms_msg_num')->map('sms_msg_num')->int()->desc('短信数量')->issorter(true)->regex('/^\d+$/')
            ->def('theme_ids')->map('theme_ids')->varchar(1000)->desc('模板设定')
            ->def('level_node_text')->map('level_node_text')->text()->desc('级别描述')->widgetType('text')
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