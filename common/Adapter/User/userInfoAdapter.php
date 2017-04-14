<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/25
 * Time: 1:56
 */

namespace Truesign\Adapter\User;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class userInfoAdapter extends DbLibraryAdapter
{
    public function database()
    {
        return 'truesign_app';
    }
    public function dbConfig(){
        return 'truesign_app';
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
        return 'userinfo';

    }

    public function tableDesc()
    {
        return '人员信息';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('人员 id')
            ->def('unique_id')->map('unique_id')->varchar(100)->desc('来源唯一id')->key()
            ->def('username')->map('username')->varchar(100)->desc('名称')->key()
            ->def('coins')->map('coins')->int()->desc('财富、硬币数')
            ->def('source')->map('source')->varchar(300)->desc('来源')
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