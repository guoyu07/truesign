<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/19
 * Time: 15:52
 */

namespace Truesign\Service\Wechat_marketing_service;


use EasyWeChat\Core\Exception;
use Royal\Crypt\Decrypt;
use Royal\Crypt\SHA256;
use Royal\Data\DAO;
use Royal\Logger\Logger;
use Royal\Prof\TrueSignConst;
use function Sodium\crypto_pwhash_scryptsalsa208sha256;
use Truesign\Adapter\wechat_marketing\businessAdapter;
use Truesign\Adapter\wechat_marketing\testAdapter;

class testService extends BaseService
{
    private $Adapter;
    private $Dao;
    private $tableAccess;
    private $rules;

    public function __construct()
    {

        $this->Adapter = new testAdapter();
        $this->Dao = new DAO($this->Adapter);
        $this->tableAccess = $this->Adapter->getTableAccess();
        $this->rules = $this->Adapter->paramRules();
        if ($this->tableaccess_ctrl()) {
            $access = $this->getAccess();
            $this->AuthTableAccess($access, $this->tableAccess);
        }


    }

    public function add($params)
    {
        return $this->Dao->create($params);
    }




}