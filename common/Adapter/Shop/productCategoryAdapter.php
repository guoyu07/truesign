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


class productInfoAdapter extends DbLibraryAdapter
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
        return 'product_info';

    }

    public function tableDesc()
    {
        return '商品信息';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->bigint()->desc('id')
            ->def('product_name')->map('product_name')->varchar(64)->desc('商品名称')
            ->def('product_price')->map('product_price')->double()->desc('单价')
            ->def('product_stock')->map('product_stock')->int()->desc('库存')
            ->def('product_description')->map('product_description')->varchar(64)->desc('描述')
            ->def('product_icon')->map('product_icon')->varchar(512)->desc('小图')
            ->def('category_type')->map('category_type')->int()->desc('类目编号')
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
