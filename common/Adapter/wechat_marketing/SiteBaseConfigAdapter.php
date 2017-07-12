<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class siteBaseConfigAdapter extends DbLibraryAdapter
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
        return 'site_base_config';

    }

    public function tableDesc()
    {
        return '网站基本信息配置';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('id')->able_modify(false)
            ->def('site_name')->map('site_name')->varchar(30)->desc('网站名称')->regex('/^.+$/')
            ->def('site_title')->map('site_title')->varchar(30)->desc('网站标题')->regex('/^.+$/')
            ->def('site_keywords')->map('site_keywords')->varchar(30)->desc('网站关键词')
            ->def('site_desc')->map('site_desc')->varchar(100)->desc('网站描述')->widgetType('file')
            ->def('site_logo_img')->map('site_logo_img')->varchar(200)->desc('网站logo')->regex('/^.+$/')->widgetType('headpic')
            ->def('site_domain')->map('site_domain')->varchar(120)->desc('网站域名')->regex('/^.+$/')
            ->def('site_record')->map('site_record')->varchar(120)->desc('网站备案信息')
            ->def('master_phone_num')->map('master_phone_num')->varchar(120)->desc('站长电话')->isPhone()
            ->def('master_qq_num')->map('master_qq_num')->varchar(120)->desc('站长qq')->regex('/^\d{5,12}$/')
            ->def('master_email')->map('master_email')->varchar(120)->desc('站长EMAIL')->isEmail()
            ->def('custom_service_phone_num')->map('custom_service_phone_num')->varchar(120)->desc('客服电话')->isPhone()
            ->def('custom_service_qq_num')->map('custom_service_qq_num')->varchar(120)->desc('客服QQ')
            ->def('site_copyright')->map('site_copyright')->varchar(120)->desc('网站版权')
            ->def('app_id')->map('app_id')->varchar(100)->desc('微信开发者应用ID')->regex('/^.+$/')
            ->def('app_secret')->map('app_secret')->varchar(200)->desc('微信开发者应用密匙')->regex('/^.+$/')

            ->def('mail_account_mail')->map('mail_account_mail')->varchar(200)->desc('网站邮箱账户')->isEmail()
            ->def('mail_server')->map('mail_server')->varchar(200)->desc('网站邮件服务器')
            ->def('mail_server_port_num')->map('mail_server_port_num')->varchar(200)->desc('网站邮件服务器端口')
            ->def('mail_username')->map('mail_username')->varchar(200)->desc('网站邮件账户用户名')
            ->def('mail_userpass')->map('mail_userpass')->varchar(200)->desc('网站邮件账户密码')

            ->def('upload_method')->map('upload_method')->varchar(20)->desc('文件上传方式')
                ->widgetType('radio',array('',array('本地','OSS')))->regex('/^.+$/')
            ->def('oss_accesskeyid')->map('oss_accesskeyid')->varchar(100)->desc('oss_accesskeyid')
            ->def('oss_accesskeysecret')->map('oss_accesskeysecret')->varchar(100)->desc('oss_accesskeysecret')
            ->def('oss_endpoint')->map('oss_endpoint')->varchar(100)->desc('oss_endpoint')
            ->def('oss_bucket')->map('oss_bucket')->varchar(100)->desc('oss_bucket')
            ->def('oss_buckethost')->map('oss_buckethost')->varchar(100)->desc('oss_buckethost')
            ->def('oss_callbackurl')->map('oss_callbackurl')->varchar(100)->desc('oss_callbackurl')
            ->def('oss_cdnurl')->map('oss_cdnurl')->varchar(100)->desc('oss_cdnurl')

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