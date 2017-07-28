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


class appRuleAdapter extends DbLibraryAdapter
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
        return null;
    }
    public function tableAccess()
    {
        return 2;
    }

    public function table()
    {
        return 'app_rule';

    }

    public function tableDesc()
    {
        return 'app规则';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('app id')
            ->def('apptype')->map('apptype')->varchar(100)->desc('app类型')->key()
            ->def('appimg')->map('appimg')->varchar(100)->desc('app图标LOGO')->key()
            ->def('appname')->map('appname')->varchar(100)->desc('app名称')->issearch(true)
            ->def('apptitle')->map('apptitle')->text()->desc('app标题')
            ->def('applevel')->map('applevel')->varchar(20)->desc('app级别')
            ->def('apptable')->map('apptable')->varchar(100)->desc('app主表名')->noTrace()->key()
            ->def('appusername')->map('appusername')->varchar(100)->desc('app主管用户')
                ->widgetType('radio',array(new appCtrlLevelAdapter(),array('nickname')))
                ->widgetStyle(
                    array(
                        'backgroundColor'=>'gray',
                        'color'=>'#fff',
                    )
                )
                ->tag(true)
            ->def('apppassword')->map('apppassword')->varchar(100)->desc('app主管密码')
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
