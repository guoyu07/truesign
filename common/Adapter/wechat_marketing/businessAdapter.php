<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;
use Truesign\Service\Wechat_marketing_service\PayInterfaceService;

/*
 * 暂时不使用数据库，以日志方式写入文件
 * */
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
            ->def('identifier_code')->map('identifier_code')->varchar(15)->desc('识别码')->able_modify(false)->key()
            ->def('username')->map('username')->varchar(100)->desc('用户名')->issearch('asc')
                ->regex('/^[a-zA-Z](\w){5,20}$/')
            ->def('password')->map('password')->varchar(100)->desc('密码')->widgetType('password')
//                ->regex('/^((?![0-9]+$)(?![a-zA-Z]+$)[a-zA-Z][\.\w]{7,20}|(\w){64})$/')
                ->regex('/^([a-zA-Z][\\.\w]{7,20}|(\w){64})$/')
            ->def('headpic')->map('headpic')->varchar(200)->desc('头像')->widgetType('headpic')

            ->def('phone_num')->map('phone_num')->varchar(15)->desc('手机号')->issearch(true)->issorter('asc')->isPhone()
            ->def('phone_num_auth_code')->map('phone_num_auth_code')->int()->desc('手机号认证识别码')->regex('/^\d{6}$/')->able_modify(false)
            ->def('phone_num_auth_code_updatetime')->map('phone_num_auth_code_updatetime')->int()->desc('手机号认证识别码更新时间')->able_modify(false)->widgetType('time')
            ->def('phone_num_auth_status')->map('phone_num_auth_status')->int()->desc('手机号认证状态')->regex('/^\d+$/')->able_modify(false)
                ->widgetType('status')

            ->def('email')->map('email')->varchar(50)->desc('邮箱')->issearch(true)->isEmail()
            ->def('email_auth_code')->map('email_auth_code')->int()->desc('邮箱认证识别码')->regex('/\d{6}/')->able_modify(false)
            ->def('email_auth_status')->map('email_auth_status')->int()->desc('邮箱认证识状态')->regex('/^\d+$/')->able_modify(false)
                ->widgetType('status')

            ->def('weimob_num')->map('weimob_num')->int()->desc('允许公众号数量(0-99)')->regex('/^\d{0,2}$/')->issorter('asc')
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