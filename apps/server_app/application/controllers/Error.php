<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author ql_os
 */
class ErrorController extends ServerAppBaseController {

	//从2.1开始, errorAction支持直接通过参数获取异常
	public function errorAction($exception) {
        $code = $exception->getCode();
        if (empty($code) && $code != 0) {
            $code = -100;
        }

        $this->inputError($code, $exception->getMessage());
        Yaf_Dispatcher::getInstance()->disableView();
        /* error occurs */
//        switch ($exception->getCode()) {
//            case YAF_ERR_NOTFOUND_MODULE:
//            case YAF_ERR_NOTFOUND_CONTROLLER:
//            case YAF_ERR_NOTFOUND_ACTION:
//            case YAF_ERR_NOTFOUND_VIEW:
//                echo 404, ":", $exception->getMessage();
//                break;
//            default :
//                $message = $exception->getMessage();
//                echo $exception->getCode()." : ". $exception->getMessage();
//                break;
//        }


    }
    public function indexAction()
    {
        $this->render('error');
        Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }
}
