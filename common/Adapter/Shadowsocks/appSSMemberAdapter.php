<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/10/15
 * Time: 下午4:50
 */

namespace Truesign\Adapter\Shadowsocks;
use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class appSSMemberAdapter extends DbLibraryAdapter
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
        return 'app_ss_';
    }
    public function tableAccess()
    {
        return 1;
    }

    public function table()
    {
        return 'ssmember';

    }

    public function tableDesc()
    {
        return 'ss用户';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('userid')->map('userid')->int()->desc('用户id')
            ->def('username')->map('username')->varchar(100)->desc('用户名')
            ->def('email')->map('email')->varchar(200)->desc('邮箱')
            ->def('conn_pass')->map('conn_pass')->varchar(100)->desc('连接密码')
            ->def('port')->map('port')->varchar(10)->desc('端口')->unique()
            ->def('flow')->map('flow')->varchar(10)->desc('流量(M)')
            ->def('status')->map('status')->int()->desc('状态')
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
