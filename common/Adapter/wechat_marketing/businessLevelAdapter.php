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
            ->def('hide_call_num')->map('hide_call_num')->varchar(20)->desc('隐号通话数量增加')->issorter(true)
                ->widgetType('radio',array('',array('0',1000,3000,10000,20000,100000)))
            ->def('sms_msg_num')->map('sms_msg_num')->varchar(20)->desc('短信数量增加')->issorter(true)
                ->widgetType('radio',array('',array('0',1000,3000,10000,20000,100000)))
            ->def('service_time')->map('service_time')->varchar(20)->desc('服务时长增加(年)')->issorter(true)
                ->widgetType('radio',array('',array('0',1,3,6,10,100)))
            ->def('theme_ids')->map('theme_ids')->varchar(1000)->desc('模板允许设定')
                ->widgetType('checkbox',array('',array(1,2,3,4,5)))
            ->def('level_node_text')->map('level_node_text')->text()->desc('级别描述')->widgetType('text')
            ->def('price')->map('price')->double()->desc('价格/元')->isMoney()
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