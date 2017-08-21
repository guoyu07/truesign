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


class officeTypeAdapter extends DbLibraryAdapter
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
        return 'office_type';

    }

    public function tableDesc()
    {
        return '文档类型';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('文档ID')
            ->def('doc_type_name')->map('office_type_name')->varchar(100)->desc('文档类型名')
            ->def('doc_suffix')->map('doc_suffix')->varchar(100)->desc('文档后缀')
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
