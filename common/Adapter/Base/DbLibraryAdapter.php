<?php
namespace Truesign\Adapter\Base;

use \Royal\Data\DAOAdapter;
use \Royal\Data\Field;

abstract class DbLibraryAdapter extends DAOAdapter
{

    protected $_id = '';

    protected $_isPrimissionEnabled = true;

    protected $_fixCompany = true;

    protected $_ignoreDeletedCondition = false;

    protected $_fieldConstruct = null;

    public function db_Prefix()
    {
        return null;
    }
    public function dbAccess(){
        return null;
    }
    public function database()
    {
        return 'db_library';
    }
    public function dbConfig(){
        return 'db_library';
    }

    public function dbDesc()
    {
        return '数据服务器信息库';
    }

    public function dbInit()
    {
        return null;
    }

    public function dbAdd()
    {
        return null;
    }

    public function dbModify()
    {
        return null;
    }

    public function dbDel()
    {
        return null;
    }

    public function tableInit()
    {
        return Field::start();
    }

//-------------
    public function paramRules()
    {
        $rules = $this->getStaticFieldConstruct()->getRule();
        return $rules;
    }
    public function getStaticFieldConstruct()
    {
        if (!$this->_fieldConstruct) {
            $this->_fieldConstruct = $this->tableInit();
        }
        return $this->_fieldConstruct;
    }
    public function paramsMapping()
    {
        return $this->getStaticFieldConstruct()->getMap();
    }
    public function keyConf()
    {
        return $this->getStaticFieldConstruct()->getKey();
    }




    

}
