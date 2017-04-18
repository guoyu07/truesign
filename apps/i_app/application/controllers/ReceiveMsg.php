<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class ReceiveMsgController extends  iAppBaseController
{

    public function ReceiveMsgAction(){
        $params = $this->getParams(array('test'));
        echo '<pre>';
        print_r($params);
        $params = $_GET;
        echo '<pre>';

        print_r($params);
        $params = $_POST;
        echo '<pre>';

        print_r($params);
        $params = $_SERVER;
        echo '<pre>';

        print_r($params);
    }

}
