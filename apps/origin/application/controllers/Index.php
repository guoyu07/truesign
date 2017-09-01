<?php
use Royal\Data\Remote;
use Royal\Util\helper;
use Truesign\Adapter\Shop\productCategoryAdapter;

/**
 * @name IndexController
 * @author ql_os
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends AppBaseController {


	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/i_app/dex/index/index/name/ql_os 的时候, 你就会发现不同
     */
	public function indexAction() {
        echo '<a href="https://iamsee.com">请跳转主页 <相关第三方></a>';

	}

	public function mysqlAction(){
        $doDao = new \Royal\Data\DAO(new productCategoryAdapter());
        $db=Remote::getDb('shop');
        $sql = "select count(*) from tb_product_category";
        $result = $db->manualSqlAll($sql);
        echo (json_encode($result));


    }
    public function mysql2Action(){
        $doDao = new \Royal\Data\DAO(new productCategoryAdapter());
        $result = $doDao->read();
        echo json_encode($result);


    }
    public function ipAction()
    {
        print_r(helper::getClientIP());
	}
    public function infoAction(){
	    echo phpinfo();
    }
    public function testAction()
    {
        throw new Exception('111',-199);
        $this->setResponseBody(array('333'));

	}


}
