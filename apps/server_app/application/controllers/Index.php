<?php
use  \Truesign\Service\Socket_server\AuthlogService;
class indexController extends ServerAppBaseController {


	public function indexAction() {

	    $params = $this->getParams(array('token'));
	    $token = $params['token'];
	    $decode =  \Royal\Crypt\Decrypt::encryption($token,'','',1);
	    echo json_encode($decode);
	}




}
