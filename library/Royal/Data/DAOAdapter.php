<?php


namespace Royal\Data;

abstract class DAOAdapter {

    abstract public function db_Prefix();
    abstract public function dbAccess();
    abstract public function belongApp();
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

    public function getTableAccess()
    {
        return $this->tableAccess();
    }
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

    public function autoIfDelete()
    {
        return 'if_delete';
    }
    public function autoCreateTimestamp() {
        return 'create_time';
    }

    public function autoUpdateTimestamp() {
        return 'update_time';
    }
    public function defaultReturnParamNames() {
        return array();
    }

    public function fuzzySearcher() {
        return false;
    }

    public function searcher() {
        return false;
    }

    public function searchRequestParams() {
        $tableName = $this->table();
        $loginInfo = new LoginInfo();
        $company_id = $loginInfo->getCompanyId();
        if($this->companyIdField()) {
            $mysql_user = $loginInfo->getMysqlUser();
            $index = $mysql_user.':'.strtolower(sprintf('%s', $tableName)).'_'.$company_id;
        }
        else if(!$this->companyIdField()) {
            $index = strtolower(sprintf('%s', $tableName));
        }
        return array(
            'index' => $index,
            'type' => 'data',
        );
    }

    public function fuzzySearchRequestParams() {
        $params = $this->searchRequestParams();
        $params['index'] .= $this->fuzzyIndexerSuffix();
        return $params;
    }

    public function searchableParamNames() {
        return array();
    }

    public function isFuzzySearchEnabled() {
        $keywordFields = $this->keywordFields();
        return (!empty($keywordFields));
    }

    public function keywordFields() {
        return array();
    }

    /**
     * @return mixed , query params mapping to table fields, both query params and returned result fields.
     */
    public function paramsMapping() {
        return array();
    }

    public function pagerEnabled() {
        return true;
    }

    public function wrapReadParams(&$params) {
        return true;
    }

    public function wrapCreateParams(&$params) {
        return true;
    }

    public function wrapWriteParams(&$params) {
        return true;
    }

    public function wrapListResult(&$result) {
        return $result;
    }

    public function defaultParamValues() {
        return array();
    }

    public function wrapItemResult(&$result) {

        return $result;
    }
}
