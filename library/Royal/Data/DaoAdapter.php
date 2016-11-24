<?php


namespace Royal\Data;

abstract class DAOAdapter {

    abstract public function db_Prefix();
    abstract public function dbAccess();
    abstract public function database();
    abstract public function dbConfig();
    abstract public function dbDesc();
    abstract public function dbInit();
    abstract public function dbAdd();
    abstract public function dbModify();
    abstract public function dbDel();

    abstract public function table_Prefix();
    abstract public function tableAccess();
    abstract public function table();
    abstract public function tableDesc();
    abstract public function tableInit();
    abstract public function paramRules();
    abstract public function keyConf();

    abstract public function tableAdd();
    abstract public function tableModify();
    abstract public function tableDel();

    public function redis(){
        return null;
    }
    public function fieldPrefix() {
        return null;
    }
    public function primaryIdParamName() {
        return 'id';
    }

    public function defaultSort() {
        return array();
    }

    public function autoCreateTimestamp() {
        return 'create_time';
    }

    public function autoUpdateTimestamp() {
        return 'update_time';
    }


}
