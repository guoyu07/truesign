<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/13/17
 * Time: 10:21 AM
 */
namespace Techies\Adapter\api;
use Techies\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;
class sysconfigAdapter extends DbLibraryAdapter
{
    public function belongApp()
    {
        return 'Techies';
    }
    public function database()
    {
        return 'Techies';
    }
    public function dbConfig(){
        return 'Techies';
    }
    public function table_Prefix()
    {
        return 'tb_';
    }
    public function tableAccess()
    {
        return 2;
    }

    public function table()
    {
        return 'sysconfig';

    }
    public function tableDesc()
    {
        return '系统/配置表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('instructions')->map('instructions')->text()->desc('说明')->issearch(true)
            ->def('version')->map('version')->varchar(10)->desc('版本号')->issearch(true)->unique()
            ->def('upgrade_note')->map('upgrade_note')->text()->desc('升级备注')->issearch(true)
            ->def('sysinfo')->map('sysinfo')->text()->desc('服务器信息')->issearch(true)
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

}