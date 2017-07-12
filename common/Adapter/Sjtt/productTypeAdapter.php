<?php

namespace Truesign\Adapter\Sjtt;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class productTypeAdapter extends DbLibraryAdapter
{
    public function database()
    {
        return 'sjtt_app';
    }
    public function dbConfig(){
        return 'sjtt_app';
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
        return 'productType';

    }

    public function tableDesc()
    {
        return '展示产品类型';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('type')->map('type')->varchar(100)->desc('产品类型')->key()
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