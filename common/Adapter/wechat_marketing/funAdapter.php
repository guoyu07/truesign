<?php

namespace Truesign\Adapter\wechat_marketing;


use Truesign\Adapter\Base\DbLibraryAdapter;
use Royal\Data\Field;


class weimobContentAdapter extends DbLibraryAdapter
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
        return 'weimob_content';

    }

    public function tableDesc()
    {
        return '微信公众号发布内容存储库';
    }

    public function tableInit()
    {
        return Field::start()
            ->def('document_id')->map('id')->int()->desc('内容id')
            ->def('b_id')->map('b_id')->int()->desc('发布人id')
            ->def('busername')->map('busername')->varchar(300)->desc('发布人名称')
            ->def('weimo_id')->map('weimo_id')->int()->desc('公众号id')
            ->def('weimo_name')->map('weimo_name')->varchar(300)->desc('公众号名称')
            ->def('content_keyword')->map('content_keyword')->varchar(200)->desc('内容关键词')
            ->def('content_type')->map('content_type')->varchar(200)->desc('内容类型')
            ->def('cover_img')->map('cover_img')->text()->desc('封面图片')
            ->def('content')->map('content')->text()->desc('文章内容')
            ->def('is_awards')->map('is_awards')->int()->desc('是否允许打赏') //0->不可以，1->可以
            ->def('awards_count')->map('awards_count')->varchar(10)->desc('打赏数')->isMoney()
            ->def('is_comment')->map('is_comment')->int()->desc('是否允许评论') //0->不可以，1->可以
            ->def('comment_count')->map('comment_count')->int()->desc('评论数')
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