<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/13/17
 * Time: 10:21 AM
 */
namespace Truesign\Adapter\api;
use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;
class paylogAdapter extends DbLibraryAdapter
{
    public function belongApp()
    {
        return 'Techies';
    }
    public function database()
    {
        return 'db_techies';
    }
    public function dbConfig(){
        return 'techies';
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
        return 'pay_logs';

    }
    public function tableDesc()
    {
        return '支付表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('uid')->map('uid')->int()->desc('用户ID')
            ->def('username')->map('username')->varchar(100)->desc('用户名')
            ->def('imgid')->map('imgid')->int()->desc('图片ID')
            ->def('image_name')->map('image_name')->varchar(100)->desc('图片名称')
            ->def('image_price')->map('image_price')->double()->desc('图片价格')
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