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


class appMsgLogAdapter extends DbLibraryAdapter
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
        return 'msg_log';

    }

    public function tableDesc()
    {
        return '消息日志';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('sender')->map('sender')->varchar(100)->desc('发送人')->issearch(true)
            ->def('sendee')->map('sendee')->varchar(100)->desc('接收人')->issearch(true)
            ->def('apps')->map('apps')->varchar(100)->desc('绑定app')->issearch(true)
            ->def('type')->map('type')->varchar(100)->desc('类型')->issearch(true)
            ->def('request')->map('request')->text()->desc('请求')
            ->def('response')->map('response')->text()->desc('响应')
            ->def('note')->map('note')->text()->desc('备注')
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
