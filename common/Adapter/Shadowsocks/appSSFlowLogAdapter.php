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


class appSSFlowLogAdapter extends DbLibraryAdapter
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
        return 'ssflow_log';

    }

    public function tableDesc()
    {
        return 'ss流量日志';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('port')->map('port')->varchar(10)->desc('端口')
            ->def('flow')->map('flow')->bigint()->desc('流量(M)')
            ->def('time')->map('time')->int()->desc('更新时间戳')
            ->def('date_time')->map('date_time')->varchar(50)->desc('更新时间')
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
