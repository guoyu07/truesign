<?php
use  \Truesign\Service\Socket_server\AppService;
class AppController extends ServerAppBaseController {


	public function indexAction() {

	    $this->throwException(\Royal\Prof\TrueSignConst::SUCCESS('User'));
	}


}
