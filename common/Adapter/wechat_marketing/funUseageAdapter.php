<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class funAdapter extends DbLibraryAdapter
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
        return 1;
    }

    public function table()
    {
        return 'fun';

    }

    public function tableDesc()
    {
        return '公众号功能表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('内容id')
            ->def('fun_keyword')->map('fun_keyword')->varchar(20)->desc('功能关键词')
            ->def('fun_uri')->map('fun_uri')->varchar(1000)->desc('功能链接')->modifiable(false)
            ->def('fun_desc')->map('fun_desc')->varchar(1000)->desc('功能描述')
            ->def('fun_usage')->map('fun_usage')->int()->desc('功能使用量')
            ->def('fun_adapter')->map('fun_adapter')->int()->desc('功能对应表')
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