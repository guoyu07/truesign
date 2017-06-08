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
        return 1;
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
            ->def('document_id')->map('id')->int()->desc('条目id')
            ->def('site_name')->map('site_title')->varchar(30)->desc('网站名称')
            ->def('site_title')->map('site_title')->varchar(30)->desc('网站标题')
            ->def('site_keywords')->map('site_keywords')->varchar(30)->desc('网站关键词')
            ->def('site_desc')->map('site_desc')->varchar(100)->desc('网站描述')
            ->def('site_logo')->map('site_logo')->varchar(200)->desc('网站logo')
            ->def('site_domain')->map('site_domain')->varchar(120)->desc('网站域名')
            ->def('site_record')->map('site_record')->varchar(120)->desc('网站备案信息')
            ->def('master_phone')->map('master_phone')->varchar(120)->desc('站长电话')
            ->def('master_qq')->map('master_qq')->varchar(120)->desc('站长qq')
            ->def('master_email')->map('master_email')->varchar(120)->desc('站长EMAIL')
            ->def('custom_service_phone')->map('custom_service_phone')->varchar(120)->desc('客服电话')
            ->def('custom_service_qq')->map('custom_service_qq')->varchar(120)->desc('客服QQ')
            ->def('site_copyright')->map('site_copyright')->varchar(120)->desc('网站版权')
            ->def('app_id')->map('app_id')->varchar(100)->desc('应用ID,微信开发者平台应用ID') //
            ->def('app_secret')->map('app_secret')->varchar(200)->desc('微信开发者平台应用密匙')

            ->def('mail_account')->map('mail_account')->varchar(200)->desc('邮箱账户')
            ->def('mail_server')->map('mail_server')->varchar(200)->desc('邮件服务器')
            ->def('mail_server_port')->map('mail_server_port')->varchar(200)->desc('邮件服务器端口')
            ->def('mail_username')->map('mail_username')->varchar(200)->desc('邮件账户用户名')
            ->def('mail_userpass')->map('mail_userpass')->varchar(200)->desc('邮件账户密码')
            ->def('mail_server')->map('mail_server')->varchar(200)->desc('邮件服务器')

            ->def('upload_method')->map('upload_method')->int()->desc('文件上传方式')  //0->本地;1->阿里云
            ->def('oss_accesskeyid')->map('oss_accesskeyid')->varchar(100)->desc('阿里云 oss_accesskeyid')
            ->def('oss_accesskeysecret')->map('oss_accesskeysecret')->varchar(100)->desc('阿里云  oss_accesskeysecret')
            ->def('oss_endpoint')->map('oss_endpoint')->varchar(100)->desc('阿里云  oss_endpoint')
            ->def('oss_bucket')->map('oss_bucket')->varchar(100)->desc('阿里云  oss_bucket')
            ->def('oss_buckethost')->map('oss_buckethost')->varchar(100)->desc('阿里云  oss_buckethost')
            ->def('oss_callbackurl')->map('oss_callbackurl')->varchar(100)->desc('阿里云  oss_callbackurl')
            ->def('oss_cdnurl')->map('oss_cdnurl')->varchar(100)->desc('阿里云  oss_cdnurl')

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