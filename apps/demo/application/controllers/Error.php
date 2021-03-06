<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author ql_os
 */
class ErrorController extends AppBaseController {

	//从2.1开始, errorAction支持直接通过参数获取异常
	public function errorAction($exception) {
        $code = $exception->getCode();
        if (!$code) {
            $code = -100;
        }
        //throw $exception;
        $this->inputError($exception->getCode(), $exception->getMessage());

        //       var_dump($exception);
        ////        $this->_view->display('index/demo.phtml');
        //        return false;
	}
    public function indexAction()
    {
        $this->render('error');
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }
}
