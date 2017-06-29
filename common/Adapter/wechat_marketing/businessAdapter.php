<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;
use Truesign\Service\Wechat_marketing_service\PayInterfaceService;


class businessAdapter extends DbLibraryAdapter
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
        return 'business';

    }

    public function tableDesc()
    {
        return '商户/公司/客户表';
    }


    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')->able_modify(false)->issorter('asc')
            ->def('username')->map('username')->varchar(100)->desc('用户名')->issearch('asc')
            ->def('password')->map('password')->varchar(100)->desc('密码')->widgetType('password')
            ->def('headpic')->map('headpic')->varchar(200)->desc('头像')->widgetType('headpic')
            ->def('phone_num')->map('phone_num')->varchar(15)->desc('手机号')->issearch(true)->isPhone()->issorter('asc')
            ->def('email')->map('email')->varchar(50)->desc('邮箱')->issearch(true)->isEmail()
            ->def('weimob_num')->map('weimob_num')->int()->desc('公众号数量')->regex('/^\d{0,2}$/')->issorter('asc')
            ->def('recharge')->map('recharge')->varchar(50)->desc('充值总额')->isMoney()->issorter('asc')
            ->def('remainder')->map('remainder')->varchar(50)->desc('余额')->able_modify(false)->isMoney()
            ->def('access_hide_call_num')->map('access_hide_call_num')->int()->desc('允许隐号通话数量')->regex('/^\d{0,6}$/')
            ->def('hide_called_num')->map('hide_called_num')->int()->desc('已拨打隐号通话数量')->able_modify(false)->regex('/^\d{0,6}$/')->issorter('asc')
            ->def('access_sms_msg_num')->map('access_sms_msg_num')->int()->desc('允许发送短信数')->regex('/^\d{0,6}$/')
            ->def('sms_send_num')->map('sms_send_num')->int()->desc('已发送短信数')->able_modify(false)->regex('/^\d{0,6}$/')->issorter(true)
            ->def('enable_time')->map('enable_time')->int()->desc('启用时间')->regex('/^.+$/')->issorter('asc')->widgetType('time')
            ->def('expire_time')->map('expire_time')->int()->desc('到期时间')->regex('/^.+$/')->issorter('asc')->widgetType('time')
            ->def('level_tag')->map('level_tag')->varchar(500)->desc('等级/套餐')->able_modify(false)
                        ->widgetType('radio',array(new businessLevelAdapter(),array('level_name')))
                        ->widgetStyle(
                            array(
                                'backgroundColor'=>array(new businessLevelAdapter(),'level_color'),
                                'color'=>'#FFFFFF',
                                )
                        )
                        ->tag(true)
            ->def('pay_interface_tag')->map('pay_interface_tag')->varchar(500)->desc('支付方式')
                        ->widgetType('checkbox',array(new PayInterfaceAdapter(),array('pay_interface_name')))
                        ->widgetStyle(
                            array(
                                'backgroundColor'=>array(new PayInterfaceAdapter(),'pay_interface_color'),
                            )
                        )
                        ->tag(true)
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