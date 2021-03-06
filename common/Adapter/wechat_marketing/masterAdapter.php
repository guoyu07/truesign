<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class masterAdapter extends DbLibraryAdapter
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
        return 'master';

    }

    public function tableDesc()
    {
        return '平台项目人员表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('username')->map('username')->varchar(100)->desc('用户名')
            ->def('password')->map('password')->varchar(100)->desc('密码')->regex('/^([a-zA-Z][\\.\w]{7,20}|(\w){64})$/')
                ->widgetType('password')
            ->def('headpic')->map('headpic')->varchar(200)->desc('头像')
                ->widgetType('headpic')
            ->def('level')->map('level')->varchar(500)->desc('管理员等级')
                ->widgetType('radio',array('',array(1,2,3,4,5,6,7,8,9)))
                ->widgetStyle(array('backgroundColor','gray'))
            ->def('note')->map('note')->varchar(500)->desc('备注')
                ->widgetType('text')
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