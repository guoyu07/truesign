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


class officeAdapter extends DbLibraryAdapter
{
    public function database()
    {
        return 'jktruesign_app';
    }
    public function dbConfig(){
        return 'jktruesign_app';
    }
    public function table_Prefix()
    {
        return null;
    }
    public function tableAccess()
    {
        return 2;
    }

    public function table()
    {
        return 'office';

    }

    public function tableDesc()
    {
        return '文档处理表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('文档ID')
            ->def('office_type')->map('office_type')->varchar(100)->desc('文档类型')
                ->widgetType('radio',array(new officeTypeAdapter(),array('doc_type_name')))
                ->widgetStyle(
                    array(
                        'backgroundColor'=>array(new businessLevelAdapter(),'level_color'),
                        'color'=>'#FFFFFF',
                    )
                )
                ->tag(true)
            ->def('source_file_name')->map('source_file_name')->varchar(200)->desc('源文件名称')
            ->def('source_file')->map('source_file')->varchar(200)->desc('源文件地址')
            ->def('source_file_hash_md5')->map('source_file_hash_md5')->varchar(32)->desc('源文件32位md5哈希')->issearch(true)->key()
            ->def('source_file_hash_sha1')->map('source_file_hash_sha1')->varchar(40)->desc('源文件40位sha1哈希')->issearch(true)->key()
            ->def('source_file_hash_sha256')->map('source_file_hash_sha256')->varchar(64)->desc('源文件64位sha256哈希')->issearch(true)->key()
            ->def('parse_file')->map('parse_file')->text()->desc('解析后的文件地址')->issearch()
            ->def('parse_status')->map('parse_status')->varchar(20)->desc('解析状态')

            ->def('request_times')->map('request_times')->int()->desc('原始文档请求次数')
            ->def('response_times')->map('response_times')->int()->desc('解析文档下载次数')
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
