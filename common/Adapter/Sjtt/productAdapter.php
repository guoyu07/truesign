<?php

namespace Truesign\Adapter\Sjtt;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class productAdapter extends DbLibraryAdapter
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
        return 'product';

    }

    public function tableDesc()
    {
        return '产品详情';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('type_id')->map('type_id')->int()->desc('绑定类别id')
            ->def('img')->map('img')->varchar(500)->desc('展示图')
            ->def('title')->map('title')->varchar(50)->desc('检索标题')
            ->def('note')->map('note')->varchar(50)->desc('简介')
            ->def('info')->map('info')->varchar(1000)->desc('详细介绍')
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