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


class appRuleAdapter extends DbLibraryAdapter
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
        return null;
    }
    public function tableAccess()
    {
        return 2;
    }

    public function table()
    {
        return 'app_rule';

    }

    public function tableDesc()
    {
        return 'app规则';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('app id')
            ->def('apptype')->map('apptype')->varchar(100)->desc('app类型')->key()
            ->def('appname')->map('appname')->varchar(100)->desc('app名称')
            ->def('applevel')->map('applevel')->varchar(20)->desc('app级别')
            ->def('apptable')->map('apptable')->varchar(100)->desc('app主表名')->noTrace()->key()
            ->def('appusername')->map('appusername')->varchar(100)->desc('app主管用户')
            ->def('apppassword')->map('apppassword')->varchar(100)->desc('app主管密码')
            ->def('socketid')->map('socketid')->varchar(100)->desc('socketid')
            ->def('connstatus')->map('connstatus')->varchar(100)->desc('连接状态')
            ->def('ip')->map('ip')->varchar(100)->desc('ip')
            ->def('last_ip')->map('last_ip')->varchar(100)->desc('上次ip')
            ->def('terminal_info')->map('terminal_info')->varchar(255)->desc('终端信息')
            ->def('last_terminal_info')->map('last_terminal_info')->varchar(255)->desc('上次终端信息')
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
