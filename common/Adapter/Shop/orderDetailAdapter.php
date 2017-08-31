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


class orderDetailAdapter extends DbLibraryAdapter
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
        return 'tb_';
    }
    public function tableAccess()
    {
        return 1;
    }

    public function table()
    {
        return 'order_detail';

    }

    public function tableDesc()
    {
        return '订单详情';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->bigint()->desc('id')
            ->def('order_id')->map('order_id')->bigint()->desc('订单id')->key()
            ->def('product_id')->map('product_id')->bigint()->desc('商品id')
            ->def('product_name')->map('product_name')->varchar(64)->desc('商品名称')
            ->def('product_price')->map('product_price')->double()->desc('商品价格')
            ->def('product_quantity')->map('product_quantity')->int()->desc('商品数量')
            ->def('product_icon')->map('product_icon')->varchar(512)->desc('商品小图')
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
