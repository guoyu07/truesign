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
class userAdapter extends DbLibraryAdapter
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
        return 'user';

    }
    public function tableDesc()
    {
        return '用户表';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('username')->map('username')->varchar(100)->desc('用户名')->unique()->issearch(true)
            ->def('password')->map('password')->varchar(100)->desc('密码')
            ->def('email')->map('email')->varchar(100)->desc('邮件')
            ->def('email_auth_code')->map('email_auth_code')->varchar(100)->desc('邮件认证秘钥')
            ->def('phone')->map('phone')->varchar(100)->desc('手机号')
            ->def('phone_auth_code')->map('phone_auth_code')->varchar(100)->desc('手机号认证秘钥')
            ->def('openid')->map('openid')->varchar(100)->desc('微信绑定ID')
            ->def('openid_addinfo')->map('openid_addinfo')->text()->desc('微信绑定ID附加信息')
            ->def('alipayid')->map('alipayid')->varchar(100)->desc('支付宝绑定ID')
            ->def('alipayid_addinfo')->map('alipayid_addinfo')->text()->desc('支付宝绑定ID附加信息')
            ->def('bankid')->map('bankid')->varchar(100)->desc('银行卡绑定ID')
            ->def('bankid_addinfo')->map('bankid_addinfo')->text()->desc('银行卡绑定ID附加信息')
            ->def('headpic')->map('headpic')->varchar(1000)->desc('头像')
            ->def('recharge_amount')->map('recharge_amount')->double()->desc('充值总额')
            ->def('balance')->map('balance')->double()->desc('余额')
            ->def('income')->map('income')->double()->desc('收入')
            ->def('payout')->map('payout')->double()->desc('支出')

            ->def('ip')->map('ip')->varchar(100)->desc('ip信息')
            ->def('device')->map('device')->varchar(100)->desc('设备信息')
            ->def('lat_lon')->map('lat_lon')->varchar(100)->desc('经纬信息')

            ->def('last_login')->map('last_login')->datetime('')->desc('最新登录时间')
            ->def('last_ip')->map('last_ip')->varchar(100)->desc('最新登录IP')

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