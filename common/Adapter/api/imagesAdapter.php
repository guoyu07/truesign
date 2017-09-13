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
class imagesAdapter extends DbLibraryAdapter
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
        return 'images';

    }
    public function tableDesc()
    {
        return '图片表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('uid')->map('uid')->int()->desc('用户ID')
            ->def('username')->map('username')->varchar(100)->desc('用户名')
            ->def('image_name')->map('image_name')->varchar(100)->desc('图片名称')->issearch(true)
            ->def('image_path')->map('image_path')->varchar(100)->desc('图片路径')->issearch(true)
            ->def('image_param')->map('image_param')->varchar(1000)->desc('图片信息')->issearch(true)
            ->def('image_price')->map('image_price')->double()->desc('图片价格')
            ->def('image_score')->map('image_score')->double()->desc('图片评分')
            ->def('view_count')->map('view_count')->bigint()->desc('图片访问量')
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