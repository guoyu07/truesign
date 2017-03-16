<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/10/15
 * Time: 下午4:50
 */

namespace Truesign\Adapter\DbServer;
use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class dbsAdapter extends DbLibraryAdapter
{
    public function table_Prefix()
    {
        return null;
    }
    public function tableAccess()
    {
        return null;
    }

    public function table()
    {
        return 'db_server';

    }

    public function tableDesc()
    {
        return '数据服务器链接表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('数据服务器id')
            ->def('db_group')->map('db_group')->varchar(100)->desc('数据库所属组')
            ->def('db_desc')->map('db_desc')->varchar(100)->desc('数据库描述')
            ->def('db_type')->map('db_type')->varchar(20)->desc('数据类型')
            ->def('db_host')->map('db_host')->varchar(100)->desc('数据库ip')->noTrace()->key()
            ->def('db_username')->map('db_username')->varchar(30)->desc('数据库用户名')
            ->def('db_password')->map('db_password')->varchar(60)->desc('数据库密码')
            ->def('db_add_auth')->map('db_add_auth')->varchar(255)->desc('数据库附加认证方式')
            ->def('tunnel_host')->map('tunnel_host')->varchar(100)->desc('隧道ip')
            ->def('tunnel_host')->map('tunnel_username')->varchar(30)->desc('隧道用户名')
            ->def('tunnel_host')->map('tunnel_password')->varchar(60)->desc('隧道密码')
            ->def('tunnel_host')->map('tunnel_port')->varchar(10)->desc('隧道服务器端口')
            ->def('tunnel_host')->map('tunnel_add_auth')->varchar(255)->desc('隧道附加认证方式')
            ->def('online_status')->map('online_status')->varchar(100)->desc('数据库服务器在线状态')
            ->def('auth_time')->map('auth_time')->int()->desc('数据库状态检测时间')
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
