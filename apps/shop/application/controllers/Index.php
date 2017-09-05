<?php

use Truesign\Adapter\Shop\productCategoryAdapter;

/**
 * @name IndexController
 * @author ql_os
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends AppBaseController
{
    /**
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/i_app/dex/index/index/name/ql_os 的时候, 你就会发现不同
     */
    public function indexAction()
    {
        $productDao = new \Royal\Data\DAO(new \Truesign\Adapter\Shop\productInfoAdapter());
        $categoryDao = new \Royal\Data\DAO(new productCategoryAdapter());

        $categoryList = $categoryDao->readSpecified(array(), array('category_type', 'category_name'));
        $productList = $productDao->readSpecified(array('product_status' => 0), array());
        foreach ($categoryList['data'] as $k => &$type_obj) {
            $type_obj['type'] = $type_obj['category_type'];
            $type_obj['name'] = $type_obj['category_name'];
            unset($type_obj['category_type'],$type_obj['category_name']);
            foreach ($productList['data'] as $kk => &$product_obj) {
                if ($type_obj['type'] == $product_obj['category_type']) {
                    $product_obj['id'] = $product_obj['document_id'];
                    $product_obj['name'] = $product_obj['product_name'];
                    $product_obj['description'] = $product_obj['product_description'];
                    $product_obj['icon'] = $product_obj['product_icon'];
                    unset(
                        $product_obj['document_id'],
                        $product_obj['product_name'],
                        $product_obj['product_description'],
                        $product_obj['product_icon'],
                        $product_obj['product_stock'],
                        $product_obj['product_status'],
                        $product_obj['category_type']
                    );
                }
                else{
                    unset($product_obj);
                }
                if(!empty($product_obj)){
                    $type_obj['foods'][] = $product_obj;

                }
            }
        }
        $this->output2json(\Royal\Prof\TrueSignConst::SUCCESS($categoryList['data']));
    }
    public function testAction(){
        echo "test";
    }


}
