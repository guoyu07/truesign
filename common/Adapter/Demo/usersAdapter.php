<?php

namespace Truesign\Adapter\Demo;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class usersAdapter extends DbLibraryAdapter
{
    public function belongApp()
    {
        return 'demo';
    }

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
        return 'users';

    }

    public function tableDesc()
    {
        return '用户信息';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('username')->map('username')->varchar(500)->desc('用户名')
            ->def('pass')->map('pass')->varchar(500)->desc('密码')
            ->def('tel')->map('tel')->varchar(50)->desc('联系电话')
            ->def('email')->map('email')->varchar(50)->desc('联系邮箱')
            ->def('ip')->map('ip')->varchar(50)->desc('ip')
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
    }
}