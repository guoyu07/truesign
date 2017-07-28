<?php
use  \Truesign\Service\Socket_server\UserService;
class SocketcommonController extends ServerAppBaseController {

    public function indexAction()
    {
        $this->output2json(\Royal\Prof\TrueSignConst::SUCCESS('index'));
    }
	public function getAccessClientListAction() {

	    $this->setResponseBody(\Royal\Prof\TrueSignConst::SUCCESS(json_encode(array(1,2,3,4))));
	}




}
