<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class WebSiteController extends  OAppBaseController
{
    public function indexAction(){

        echo 'website';
    }
    public function regOrLoginAction()
    {
        $params = $this->getParams(array('username', 'pass', 'email'), array('look_for', 'ip'));
        $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appWebSiteAdapter());
        $db_checkUsername_response = $doDao->readSpecified(array('username' => $params['username']),array());
        $db_checkEmail_response = $doDao->readSpecified(array('email' => $params['email']),array());
        $db_Login_response = $doDao->readSpecified(array('username' => $params['username'], 'pass' => $params['pass']),array());
        $db_reg_response = $doDao->create($params);
        $this->setResponseBody($db_checkUsername_response);
    }

}
