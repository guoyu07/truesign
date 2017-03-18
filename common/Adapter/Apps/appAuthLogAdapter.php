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


class appAuthLogAdapter extends DbLibraryAdapter
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
        return 'auth_log';

    }

    public function tableDesc()
    {
        return '鉴权日志';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('auth id')
            ->def('apps')->map('apps')->varchar(100)->desc('绑定app')
            ->def('ctrlid')->map('ctrlid')->varchar(100)->desc('控制级别id')
            ->def('ctrlname')->map('ctrlname')->varchar(100)->desc('控制人员名称')
            ->def('authway')->map('authway')->varchar(255)->desc('认证方式') //web 、客户端
            ->def('note')->map('note')->varchar(255)->desc('备注')
            ->def('sysinfo')->map('sysinfo')->varchar(255)->desc('系统信息')
            ->def('ip')->map('ip')->varchar(255)->desc('认证ip')
            ->def('topkeystamp')->map('topkeystamp')->varchar(255)->desc('时间序列码')
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
