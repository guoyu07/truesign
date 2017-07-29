<?php
use  \Truesign\Service\Socket_server\UserService;
class LogicController extends ServerAppBaseController {

    public function indexAction()
    {

        $this->output2json(\Royal\Prof\TrueSignConst::SUCCESS('login_index'));
    }

    public function chatAction()
    {
        $params = $this->getParams(array('unique_auth_code'));
        $doService = new \Truesign\Service\Socket_server\AuthlogService();
        $service_response = $doService->GetFdByUAC($params['unique_auth_code']);
        $this->setResponseBody(\Royal\Prof\TrueSignConst::SUCCESS(json_encode(array('to_id'=>$service_response))));
    }




}
