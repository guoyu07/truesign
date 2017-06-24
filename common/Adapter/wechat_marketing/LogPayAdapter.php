<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class LogPayAdapter extends DbLibraryAdapter
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
        return 'log_pay';

    }

    public function tableDesc()
    {
        return '支付日志';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('b_id')->map('b_id')->int()->desc('商户账户ID')
            ->def('b_username')->map('b_username')->varchar(300)->desc('商户名称')
            ->def('local_pay_numbered')->map('local_pay_numbered')->varchar(300)->desc('本地支付编号')
            ->def('remote_pay_numbered')->map('remote_pay_numbered')->varchar(300)->desc('支付平台编号')
            ->def('pay_price')->map('pay_price')->varchar(300)->desc('支付价格')
            ->def('pay_status')->map('pay_secret')->varchar(300)->desc('直接对账状态')
            ->def('lazy_pay_status')->map('signature_key')->varchar(300)->desc('延时对账状态')
            ->def('remote_payer_info')->map('remote_payer_info')->varchar(300)->desc('平台支付人信息')
            ->def('remote_payer_note')->map('remote_payer_note')->varchar(300)->desc('平台支付备注')
            ->def('pay_note')->map('pay_note')->varchar(300)->desc('支付备注')
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