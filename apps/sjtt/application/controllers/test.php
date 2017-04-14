<?php
class TestController extends \Yaf_Controller_Abstract {
   public function indexAction(){
       $params = $this->getRequest()->getParam();
       var_dump($params);
   }
}