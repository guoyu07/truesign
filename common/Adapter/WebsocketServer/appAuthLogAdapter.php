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


class appAuthLogAdapter extends DbLibraryAdapter
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
        return 'auth_log';

    }

    public function tableDesc()
    {
        return '鉴权日志';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('app')->map('app')->varchar(100)->desc('绑定app')->issearch(true)
            ->def('ctrlname')->map('ctrlname')->varchar(100)->desc('控制人员名称')->issearch(true)
            ->def('authway')->map('authway')->varchar(255)->desc('认证方式') //web 、客户端
            ->def('note')->map('note')->varchar(255)->desc('备注')
            ->def('user_agent')->map('user_agent')->varchar(1000)->desc('系统信息')->issearch(true)
            ->def('ip')->map('ip')->varchar(255)->desc('认证ip')->issearch(true)
            ->def('unique_auth_code')->map('unique_auth_code')->varchar(100)->desc('唯一序列码')->unique()->issearch(true)
            ->def('fd')->map('fd')->varchar(100)->desc('socket唯一id')
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
