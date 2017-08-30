<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/10/15
 * Time: 下午4:50
 */

namespace Truesign\Adapter\Shop;
use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class orderMasterAdapter extends DbLibraryAdapter
{
    public function database()
    {
        return 'app_shop';
    }
    public function dbConfig(){
        return 'shop';
    }
    public function table_Prefix()
    {
        return 'app_';
    }
    public function tableAccess()
    {
        return 1;
    }

    public function table()
    {
        return 'order_master';

    }

    public function tableDesc()
    {
        return '订单主表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->bigint()->desc('id')
            ->def('buyer_name')->map('buyer_name')->varchar(32)->desc('买家名称')
            ->def('buyer_phone')->map('buyer_phone')->varchar(32)->desc('买家电话')
            ->def('buyer_address')->map('buyer_address')->varchar(32)->desc('买家地址')
            ->def('buyer_openid')->map('buyer_openid')->varchar(32)->desc('买家微信openid')->key()
            ->def('order_amount')->map('order_amount')->double()->desc('订单总金额')
            ->def('order_status')->map('order_status')->int()->desc('订单状态')->widgetType('radio',array('',array('0'=>'新下单','1'=>'旧订单')))
            ->def('pay_status')->map('pay_status')->int()->desc('支付状态')->widgetType('radio',array('',array('0'=>'未支付','1'=>'已支付')))
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
        // TODO: Implement show() method.

    }
}
