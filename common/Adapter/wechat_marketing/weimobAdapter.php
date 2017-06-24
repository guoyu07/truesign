<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class weimobAdapter extends DbLibraryAdapter
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
        return 'weimob';

    }

    public function tableDesc()
    {
        return '微信公众号对接接口信息';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('公众号id')
            ->def('b_id')->map('b_id')->int()->desc('客户id')->able_modify(false)
            ->def('weimo_type')->map('weimo_type')->int()->desc('公众号类型') //1->非认证订阅号;2->非认证服务号;3->认证订阅号;4->认证服务号
            ->def('weimo_name')->map('weimo_name')->varchar(300)->desc('公众号名称')->issearch(true)
            ->def('appid')->map('appid')->varchar(100)->desc('appid')
            ->def('appsecret')->map('appsecret')->varchar(100)->desc('appsecret')
            ->def('qr_img')->map('qr_img')->varchar(200)->desc('二维码图片地址')
            ->def('aeskey')->map('aeskey')->varchar(100)->desc('aeskey')
            ->def('encode')->map('encode')->varchar(300)->desc('加密')
            ->def('base_id')->map('base_id')->varchar(100)->desc('原始id')
            ->def('wechat')->map('wechat')->varchar(100)->desc('微信号')
            ->def('weimo_name')->map('weimo_name')->varchar(300)->desc('公众号名称')
            ->def('weimo_focus_num')->map('weimo_focus_num')->int()->desc('公众号关注人数')->able_modify(false)
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