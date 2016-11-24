<?php
/**
 * Created by PhpStorm.
 * User: ql_os
 * Date: 2016/11/15
 * Time: 上午11:44
 */

namespace Royal;


class offline
{
    public function __construct()
    {
        print('__construct'.'<pre>');
        \Yaf_Registry::set('useSession', true);
        \Yaf_Registry::set('disableSession',true);
        if (\Yaf_Registry::get('disableSession')) {
            $this->redo();
        }
    }

    public function redo(){
        print('redo'.'<pre>');
        \Yaf_Registry::set('useSession', false);
    }

    public function indo(){
        $L = new offline();
    }

}