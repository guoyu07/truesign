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
        return array('read'=>9,'write'=>9);
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
            ->def('document_id')->map('id')->int()->desc('id')
            ->def('b_id')->map('b_id')->int()->desc('发布人id')
            ->def('busername')->map('busername')->varchar(300)->desc('发布人名称')
            ->def('weimo_id')->map('weimo_id')->int()->desc('公众号id')
            ->def('weimo_name')->map('weimo_name')->varchar(300)->desc('公众号名称')
            ->def('content_title')->map('content_title')->varchar(200)->desc('内容标题')
            ->def('content_keyword')->map('content_keyword')->varchar(200)->desc('内容关键词')
            ->def('content_type')->map('content_type')->varchar(200)->desc('内容类型')
            ->def('cover_img')->map('cover_img')->text()->desc('封面图片')
            ->def('content')->map('content')->text()->desc('文章内容')
            ->def('is_awards')->map('is_awards')->int()->desc('是否允许打赏')->isRadio(array('不可以','可以')) //0->不可以，1->可以
            ->def('awards_num')->map('awards_num')->int()->desc('打赏人数')->able_modify(false)
            ->def('awards_money_count')->map('awards_count')->varchar(10)->desc('打赏总金额')->isMoney()->able_modify(false)
            ->def('is_comment')->map('is_comment')->int()->desc('是否允许评论')->isRadio(array('不可以','可以')) //0->不可以，1->可以
            ->def('comment_num')->map('comment_num')->int()->desc('评论数')->able_modify(false)
            ->def('browse_num')->map('browse_num')->int()->desc('浏览数')->able_modify(false)
            ->def('verified')->map('verified')->int()->desc('审核标志')->isRadio(array('驳回','通过'))
            ->def('m_id')->map('m_id')->int()->desc('审核人id')->able_show(false)
            ->def('m_username')->map('m_username')->int()->desc('审核人昵称)')->able_modify(false)

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