<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class fingerprintsAdapter extends DbLibraryAdapter
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
        return 'fingerprints';

    }

    public function tableDesc()
    {
        return '指纹识别记录';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->bigint()->desc('id')
            ->def('aimed_adapter')->map('aimed_adapter')->varchar(100)->desc('对应adapter')->able_modify(false)
            ->def('aimed_id')->map('aimed_id')->int()->desc('对应id')->able_modify(false)
            ->def('aimed_name')->map('aimed_name')->varchar(200)->desc('对应名称')->able_modify(false)
            ->def('aimed_type')->map('aimed_type')->varchar(200)->desc('针对类型')->able_modify(false)
                ->widgetType('radio',array('',array('管理登录','商户登录','员工登录','代理登录','其它')))
                ->able_modify(false)
            ->def('iptype')->map('iptype')->int(100)->desc('ip类型')->able_modify(false)
                ->widgetType('radio',array('',array('ipv4','ipv6')))->able_modify(false)
            ->def('ip')->map('ip')->varchar(100)->desc('ip')->able_modify(false)
            ->def('platform')->map('platform')->varchar(100)->desc('平台')->able_modify(false)
            ->def('fingerprints')->map('fingerprints')->varchar(1000)->desc('指纹')->able_modify(false)
            ->def('note')->map('note')->text()->desc('备注')
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