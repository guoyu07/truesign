<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 15:52
 */

namespace Truesign\Service\Wechat_marketing_service;


use function EasyWeChat\Payment\get_client_ip;
use Royal\Crypt\Decrypt;
use Royal\Crypt\SHA256;
use Royal\Data\DAO;
use Royal\Logger\Logger;
use Royal\Prof\TrueSignConst;
use Royal\Util\helper;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Truesign\Adapter\wechat_marketing\fingerprintsAdapter;

class FingerPrintsService extends BaseService
{
    private $Adapter;
    private $Dao;
    private $tableAccess;
    private $rules;

    public function __construct()
    {

        $this->Adapter = new fingerprintsAdapter();
        $this->Dao = new DAO($this->Adapter);
        $this->tableAccess = $this->Adapter->getTableAccess();
        $this->rules = $this->Adapter->paramRules();
        if ($this->tableaccess_ctrl()) {
            $access = $this->getAccess();
            $this->AuthTableAccess($access, $this->tableAccess);
        }

    }

    public function setFingerPrints(
        $adapter_name='', $aimed_id='', $aimed_name='', $aimed_type='',$note='', $platform = 'pc',
        $iptype = 'ipv4')
    {
        $create_params = [];
        $create_params['aimed_adapter'] = $adapter_name;
        $create_params['aimed_id'] = $aimed_id;
        $create_params['aimed_name'] = $aimed_name;
        $create_params['aimed_type'] = $aimed_type;
        $create_params['iptype'] =$iptype;
        $create_params['ip'] =helper::getClientIP();
        $create_params['platform'] = $platform;
        $create_params['fingerprints'] = json_encode($_SERVER);
        $create_params['note'] = $note;

        return $this->Dao->create($create_params);
    }

    public function getLastFingerPrints($adapter_name, $id,$aimed_type)
    {
        $db_response =  $this->Dao->get(array('aimed_adapter' => $adapter_name, 'aimed_id' => $id,'aimed_type'=>$aimed_type),
            array('ip','platform','create_time'), array('update_time' => 'desc'),true);
        if(!empty($db_response['create_time'])){
            $db_response['create_time'] = date('Y-m-d H:i:s',$db_response['create_time']);
        }
        return $db_response;
    }

}