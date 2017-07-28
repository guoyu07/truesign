<?php
use  \Truesign\Service\Socket_server\UserService;
class LogicController extends ServerAppBaseController {

    public function indexAction()
    {

        $this->output2json(\Royal\Prof\TrueSignConst::SUCCESS('login_index'));
    }

    public function chatAction()
    {
        $params = $this->getParams('unique_auth_code');
        $doService = new \Truesign\Service\Socket_server\AuthlogService();
        $service_response = $doService->GetFdByUAC($params['unique_auth_code']);
        $this->setResponseBody(\Royal\Prof\TrueSignConst::DEBUG($service_response));
    }




}
