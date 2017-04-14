<?php

namespace Truesign\Adapter\Sjtt;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class accountAdapter extends DbLibraryAdapter
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
        return 'account';

    }

    public function tableDesc()
    {
        return '管理员信息';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('username')->map('username')->varchar(500)->desc('用户名')
            ->def('img')->map('img')->varchar(500)->desc('头像')
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
        print('showme');
    }
}