<?php

namespace Truesign\Adapter\Sjtt;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class siteIndexAdapter extends DbLibraryAdapter
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
        return 'siteindex';

    }

    public function tableDesc()
    {
        return '网站首页展示';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('人员 id')
            ->def('pageindex')->map('pageindex')->int()->desc('第几屏')
            ->def('bg')->map('bg')->varchar(500)->desc('背景图片')
            ->def('info')->map('info')->varchar(1000)->desc('相关介绍')
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