<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/10/15
 * Time: 下午4:50
 */

namespace Truesign\Adapter\Volume;
use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class danmuLogAdapter extends DbLibraryAdapter
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
        return 'danmu_log';

    }

    public function tableDesc()
    {
        return '海量弹幕信息保存';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('人员 id')
            ->def('unique_id')->map('unique_id')->varchar(100)->desc('来源唯一id')->key()
            ->def('username')->map('username')->varchar(100)->desc('名称')->key()
            ->def('danmu')->map('danmu')->varchar(1000)->desc('弹幕')
            ->def('source')->map('source')->varchar(300)->desc('来源')
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
