<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class updateVersionAdapter extends DbLibraryAdapter
{
    public function database()
    {
        return 'wechat_marketing';
    }
    public function dbConfig(){
        return 'wechat_marketing';
    }
    public function table_Prefix()
    {
        return 'app_';
    }
    public function tableAccess()
    {
        return array('read'=>9,'write'=>9);
    }

    public function table()
    {
        return 'update_version';

    }

    public function tableDesc()
    {
        return '项目版本升级日志库';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('条目id')
            ->def('version')->map('version')->varchar(10)->desc('版本号')
            ->def('version_code')->map('version_code')->varchar(50)->desc('版本代号')
            ->def('file_path')->map('file_path')->varchar(1000)->desc('文件相对项目根所在路径/')
            ->def('update_note')->map('update_note')->text()->desc('项目更新说明')
            ->def('charge_name')->map('charge_name')->varchar(100)->desc('负责人')
            ->def('charge_conn_info')->map('charge_conn_info')->varchar(200)->desc('负责人联系方式')
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