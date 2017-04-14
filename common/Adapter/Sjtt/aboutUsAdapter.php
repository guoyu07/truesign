<?php

namespace Truesign\Adapter\Sjtt;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class aboutUsAdapter extends DbLibraryAdapter
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
        return 'aboutus';

    }

    public function tableDesc()
    {
        return '关于我们';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('company')->map('company')->varchar(500)->desc('公司名称')
            ->def('address')->map('address')->varchar(500)->desc('公司地址')
            ->def('tel')->map('tel')->varchar(50)->desc('联系电话')
            ->def('site')->map('site')->varchar(1000)->desc('公司网站')
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