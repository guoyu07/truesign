<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/10/15
 * Time: 下午4:50
 */

namespace Truesign\Adapter\WebsocketServer;

use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class appCtrlLevelAdapter extends DbLibraryAdapter
{
    public function database()
    {
        return 'socketserver';
    }
    public function dbConfig(){
        return 'app_socketserver';
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
        return 'ctrl_level';

    }

    public function tableDesc()
    {
        return '权限级别';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('nickname')->map('nickname')->varchar(100)->desc('昵称')->issearch(true)
            ->def('username')->map('username')->varchar(100)->desc('用户名')
            ->def('password')->map('password')->varchar(100)->desc('密码')
            ->def('level')->map('level')->varchar(100)->desc('权限等级')
            ->def('img')->map('img')->varchar(1000)->desc('头像')
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
