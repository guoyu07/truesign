<?php

use Royal\Validator\ParamValidator_CLI;
use Royal\Validator\ParamRule;

class AppBaseController extends \ReInit\YafBase\Controller
{
    public function init()
    {
        $this->doInit();
    }

    public function doInit()
    {

    }
    public function getParams(array $required, array $optional = array(), \Royal\Data\DAOAdapter &$adapter = null)
    {

        $paramRules = $adapter ? $adapter->paramRules() : array();
        $rules = $this->getRules($required, $optional, $paramRules);

        $request = $this->getRequest();
//
//        $params = ParamValidator_CLI::paramsFromRequestAndRules($request, $rules);
        $params = \Royal\Validator\ParamValidator_ALL::paramsFromRequestAndRules($request, $rules);
//
        return $params;
    }
    private function getRules(array $required, array $optional, array $paramRules)
    {
        $rules = array();
        $paramNames = array_merge($required, $optional);
        foreach ($paramNames as $r) {
            $rule = ParamRule::rule($r);
            if (isset($paramRules[$r])) {
                $rule = $paramRules[$r];
            }
            $rule->required(in_array($r, $required));
            $param = $this->getRequest()->get($rule->name);
            if (in_array($r, $optional)) {

                if ($param !== null) {
                    $rules[$r] = $rule;
                }
            } else {
                $rules[$r] = $rule;
            }
        }
        return $rules;
    }
    protected function inputErrorResult($code)
    {
        $desc = ErrorCode::errorMsgByCode($code);
//        $desc = $model->getErrorText($code);
        $this->inputError($code, $desc);
    }
    protected  function output2json($result){
        echo json_encode($result);
    }

    protected function inputError($code, $msg)
    {
        $start_exec_time = \Yaf_Registry::get('START_EXEC_TIME');
        $start_exec_memory = \Yaf_Registry::get('START_EXEC_MEMORY');


        $exec=[
            'execTime'=>\Royal\Util\helper::exeTime($start_exec_time),
            'runMem'=>\Royal\Util\helper::run_mem($start_exec_memory)
        ];
        echo json_encode(array('code' => $code, 'desc' => $msg,'exec'=>$exec));
        \Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }




    public function setResponseBody($data){
        $helper = new \Royal\Util\helper();
//        $rev=$helper::getBody($data, $info, $code);

        if(IS_CLI) {
//            $response = $this->getResponse();
//            $response->contentBody = $rev;
            $response = $this->getResponse();

//            $request = $this->getRequest();
//            $response_data['datatype']=gettype($rev);
//            $response_data['response_data']=$rev;
            $response->contentBody=$data;
        }else{
            $start_exec_time = \Yaf_Registry::get('START_EXEC_TIME');
            $start_exec_memory = \Yaf_Registry::get('START_EXEC_MEMORY');
            $rev['argv']=$this->getData();
            $rev['serv']=[
                'execTime'=>\exeTime($start_exec_time),
                'runMem'=>\run_mem($start_exec_memory)
            ];
//            r($rev);
        }
        return $rev;
    }
    public function setErr($e,$method,$code=500){
        $this->setBody($e->getMessage(), $method, $code);
    }
    public function getData(){
        try {
            $req = $this->getRequest();
            $raw = $req->getParams();
            if (IS_CLI) {
                return $raw;
            }

            $m = $req->getModuleName();
            $c = $req->getControllerName();
            $a = $req->getActionName();
            $f = APPLICATION_PATH . '/_data/' . $m . '/' . $c . '.php';
            $n = $raw['data'] ?? 'def';
            require_once($f);
            $data = \IndexData::getData($a, $n);
            return $data;
        }catch (\Exception $e){
            throw $e;
        }
    }
    public function getTaskId(){
        return $this->getRequest()->task_id;
    }

    public function getRedis($node){
        return \publics::getRedis($node);
    }
    public function getDB($node){
        return \publics::getDB($node);
    }

    protected function getPageParams() {
        $pageRules = array(
            'page'=>ParamRule::rule('page')->type('int')->defaultValue(1),
            'page_size'=>ParamRule::rule('page_size')->type('int')->defaultValue(10),
        );

        $request = $this->getRequest();
        $params = ParamValidator_CLI::paramsFromRequestAndRules($request, $pageRules);
        if ($params['page'] <= 0) {
            $params['page'] = 1;
        }

        return $params;
    }

//    protected function inputIdResult($id) {
//        $this->inputResult(array('id' => $id));
//    }

    protected function inputParamErrorResult() {
        echo json_encode(array('code' => -100, 'desc' => '缺少参数或者参数非法！'));

        \Yaf_Dispatcher::getInstance()->autoRender(FALSE);
    }

//    protected function inputResult($data = array()) {
//        echo json_encode(array('data' => $data, 'code' => 0));
//        \Yaf_Dispatcher::getInstance()->autoRender(FALSE);
//    }

    public function setParam($key, $type, $value, &$param) {
        if($type == 'in') {
            if(empty($value)) {
                $value = array('0');
            }
        }
        //            $suffixMap = array('le'=>'以下', 'lt'=>'以下', 'ge'=>'以上', 'gt'=>'以上');
        $param[$key] = array(
            'operation' => $type,
            'value' => $value
        );
    }

    protected function _forward($action, $controller = '', $parameters = array()) {
        $this->forward('Index', $controller, $action, $parameters);
    }

    protected function render($tpl, array $parameters = null) {
        $this->display($tpl, $parameters);
    }

    public function sendMail($toemail='137847127@qq.com',$toname='137847127@qq.com',$title='测试',$content='hi')
    {

//        $toemail = 'xxx@qq.com';//定义收件人的邮箱

        $mail = new PHPMailer();

        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.iamsee.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "root@iamsee.com";// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱</span><span style="color:#333333;">
        $mail->Password = "Zhuotong.936";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！</span><span style="color:#333333;">
//        $mail->SMTPSecure = "ssl";// 使用ssl协议方式</span><span style="color:#333333;">
//        $mail->Port = 994;// 163邮箱的ssl协议方式端口号是465/994

        $mail->setFrom("root@iamsee.com","iamsee.com");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->addAddress($toemail,$toname);// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
//        $mail->addReplyTo("xxx@163.com","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addAttachment("bug0.jpg");// 添加附件


        $mail->Subject = $title;// 邮件标题
        $mail->Body = $content;// 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

        if(!$mail->send()){// 发送邮件
//            echo "Message could not be sent.";
//            echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
            return 0;
        }else{
//            echo '发送成功';
            return 1;
        }

    }

//    public function getParams(array $required, array $optional, DAOAdapter $adapter = null) {
//        $paramRules = $adapter ? $adapter->paramRules() : array();
//        $rules = $this->getRules($required, $optional, $paramRules);
//        $request = $this->getRequest();
//        return ParamValidator_CLI::paramsFromRequestAndRules($request, $rules);
//    }

//    public function getRequiredParams(array $required, DAOAdapter $adapter = null) {
//        return $this->getParams($required, array(), $adapter);
//    }
//
//    public function getOptionalParams(array $optional, DAOAdapter $adapter = null) {
//        return $this->getParams(array(), $optional, $adapter);
//    }

//    public function getPairParams(array $paramNames, DAOAdapter $adapter = null) {
//        $paramRules = $adapter ? $adapter->paramRules() : array();
//        $rules = $this->getRules(array(), $paramNames, $paramRules);
//        $request = $this->getRequest();
//        return ParamValidator_CLI::pairParamsFromRequestAndRules($request, $rules);
//    }


}
