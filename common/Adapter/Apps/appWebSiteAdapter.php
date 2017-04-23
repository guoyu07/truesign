<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/10/15
 * Time: 下午4:50
 */

namespace Truesign\Adapter\Apps;
use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class appWebSiteAdapter extends DbLibraryAdapter
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
        return 3;
    }

    public function table()
    {
        return 'website';

    }

    public function tableDesc()
    {
        return '网站数据';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('app id')
            ->def('username')->map('username')->varchar(100)->desc('用户名')
            ->def('pass')->map('pass')->varchar(100)->desc('密码')
            ->def('email')->map('email')->varchar(100)->desc('邮箱')
            ->def('look_for')->map('look_for')->varchar(1000)->desc('目的')
            ->def('reg_ip')->map('reg_ip')->varchar(100)->desc('注册ip')
            ->def('ip')->map('ip')->varchar(100)->desc('最新登录ip')
            ->def('website_level')->map('website_level')->int()->desc('网站权限等级')
            ->def('mark')->map('mark')->int()->desc('备注')
            ->def('unique_auth_code')->map('unique_auth_code')->varchar(100)->desc('唯一识别码')
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
