<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class envAdapter extends DbLibraryAdapter
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
        return 'env';

    }

    public function tableDesc()
    {
        return '项目系统运行环境';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')->able_modify(false)
            ->def('server_env')->map('server_env')->varchar(300)->desc('服务器运行环境')->able_modify(false)
            ->def('server_ip')->map('server_ip')->varchar(100)->desc('服务器IP')->able_modify(false) //1->非认证订阅号;2->非认证服务号;3->认证订阅号;4->认证服务号
            ->def('platform_version')->map('platform_version')->varchar(100)->desc('平台版本')->able_modify(false) //1->非认证订阅号;2->非认证服务号;3->认证订阅号;4->认证服务号
            ->def('server_help')->map('server_help')->text()->desc('服务器配置与项目安装说明')->able_modify(true)->widgetType('text') //1->非认证订阅号;2->非认证服务号;3->认证订阅号;4->认证服务号
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
        prin('showme');
    }
}