<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/10/15
 * Time: 下午4:50
 */

namespace Truesign\Adapter\Note;
use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class noteAdapter extends DbLibraryAdapter
{
    public function belongApp()
    {
        return 'note';
    }
    public function database()
    {
        return 'app_note';
    }
    public function dbConfig(){
        return 'note';
    }
    public function table_Prefix()
    {
        return 'tb_';
    }
    public function tableAccess()
    {
        return 1;
    }

    public function table()
    {
        return 'note';

    }

    public function tableDesc()
    {
        return '文字的故事';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->bigint()->desc('id')
            ->def('note')->map('note')->text()->desc('记录')->issearch(true)
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
        // TODO: Implement show() method.

    }
}
