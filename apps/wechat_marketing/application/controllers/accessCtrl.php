<?php

use Truesign\Service\Wechat_marketing_service\BusinessService;
use Royal\Prof\TrueSignConst;
use \Truesign\Service\Wechat_marketing_service\FingerPrintsService;
use \Truesign\Service\Wechat_marketing_service\MasterService;

class accessCtrlController extends AppBaseController
{
    public function indexAction()
    {
        $this->output2json(TrueSignConst::SUCCESS('鉴权正常'));
    }
}
