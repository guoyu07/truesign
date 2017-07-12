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


class appSSNodesAdapter extends DbLibraryAdapter
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
        return 'ssnodes';

    }

    public function tableDesc()
    {
        return 'shadowsocks代理服务器';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('server_ip')->map('server_ip')->varchar(100)->desc('服务器地址')
            ->def('location')->map('location')->varchar(100)->desc('区域')
            ->def('encryption_way')->map('encryption_way')->varchar(100)->desc('加密方式')
            ->def('load_number')->map('load_number')->int()->desc('负载人数')
            ->def('server_status')->map('server_status')->varchar(100)->desc('状态')
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
