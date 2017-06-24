<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class PayInterfaceAdapter extends DbLibraryAdapter
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
        return 'pay_interface';

    }

    public function tableDesc()
    {
        return '支付接口';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('条目id')
            ->def('b_id')->map('b_id')->int()->desc('商户账户ID')
            ->def('pay_way_code')->map('pay_way_code')->varchar(300)->desc('支付方式代号')
            ->def('pay_way_name')->map('pay_way_name')->varchar(300)->desc('支付方式名称')
            ->def('pay_acount')->map('pay_acount')->varchar(300)->desc('支付权限账户账号')
            ->def('pay_id')->map('pay_id')->varchar(300)->desc('支付权限id')
            ->def('pay_secret')->map('pay_secret')->varchar(300)->desc('支付权限密匙')
            ->def('signature_key')->map('signature_key')->varchar(300)->desc('签名密匙')
            ->def('public_key_file')->map('public_key_file')->varchar(300)->desc('公匙文件')
            ->def('private_key_file')->map('private_key_file')->varchar(300)->desc('私匙文件')
            ->def('root_certificate_file')->map('root_certificate_file')->varchar(300)->desc('根证书文件')
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