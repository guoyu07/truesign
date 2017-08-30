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


class productCategoryAdapter extends DbLibraryAdapter
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
        return 'product_category';

    }

    public function tableDesc()
    {
        return '商品类目';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->bigint()->desc('id')
            ->def('category_name')->map('category_name')->varchar(64)->desc('类目名称')
            ->def('category_type')->map('category_type')->int()->desc('类目编号')->key()
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
