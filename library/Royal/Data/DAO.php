<?php

namespace Royal\Data;


use \Exception;
use Royal\Logger\Logger;
use Royal\Util\ArrayUtil;
use Royal\Validator\PairMap;

class DAO
{
    /**
     * @var DAOAdapter
     */
    private $adapter;

    private $_useFuzzySearch = false;


    static private $daoInstances = array();

    public static function daoByName($name, $module = null)
    {
        $config = \Yaf_Registry::get('config');
        if (!$module) {
            $module = $config->application->default->module;
        }

        $DAOKey = sprintf('%s_%s', $module, $name);
        if (static::$daoInstances[$DAOKey]) {
            return static::$daoInstances[$DAOKey];
        }

        $app = ucfirst($config->application->name);
        $adapterName = sprintf('%s\Adapter\%s\%sAdapter', $app, $module, $name);
        $adapter = new $adapterName();

        $dao = new DAO($adapter);
        return static::$daoInstances[$DAOKey] = $dao;
    }

    public function __construct($adapter)
    {
        $this->adapter = $adapter;
    }

    public static function checkDemo() {
        return true;

        $crm_user = $_COOKIE['crm_user'];//CRM访问
        $wx_demo = \Yaf_Registry:: get('WX_DEMO');//微店访问
        if($crm_user=='1' || $wx_demo =='1') {
            return;
        }

        $loginInfo = new LoginInfo();
        $company_id = $loginInfo->getCompanyId();
        if($company_id =='1' &&!$loginInfo->getRegistryFiled('crm_user')) {
            throw new Exception('演示版不能调整数据!', -4008);
        }
    }

    public static function instance($adapter,$mysql_user=null)
    {
        if (!($adapter instanceof DAOAdapter)) {
            $loginInfo = new LoginInfo();
            if(empty($mysql_user)){
                $mysql_user = $loginInfo->getMysqlUser();
            }
            $name = $mysql_user.':'.$adapter;
            if (static::$daoInstances[$name]) {
                return static::$daoInstances[$name];
            }

            $adapterName = sprintf('%sAdapter', $name);
            $adapter = new $adapterName();
            $dao = new DAO($adapter);
            static::$daoInstances[$name] = $dao;
        } else {
            return new DAO($adapter);
        }
    }

    public static function getDAO($adapter,$mysql_user)
    {
        $name = $mysql_user.':'.$adapter;
        if (static::$daoInstances[$name]) {
            return static::$daoInstances[$name];
        }
        else{
            throw new Exception('找不到数据库连接', -4009);
        }
    }


    public function enableFuzzySearch()
    {
        $this->_useFuzzySearch = true;
        return $this;
    }

    public function disableFuzzySearch()
    {
        $this->_useFuzzySearch = false;
        return $this;
    }

    public function close()
    {
        if ($esName = $this->adapter->searcher()) {
            Remote::close('es', $esName);
        }
        if ($esName = $this->adapter->fuzzySearcher()) {
            Remote::close('es', $esName);
        }
        if ($db = $this->adapter->database()) {
            Remote::close('db', $db);
        }
        if ($redis = $this->adapter->redis()) {
            Remote::close('redis', $redis);
        }
    }

    /**
     * @return \Eagle\Adapter\E\EagleBaseAdapter
     */

    public function getAdapter()
    {
        return $this->adapter;
    }

    public function create($params,$ignore_error=false)
    {
        self::checkDemo();
        $fields = $this->prepareForCreate($params);

        $id = $this->getDb()->insertTable($this->getTable($this->adapter->table()), $fields,$ignore_error);
        $idField = $this->paramNameToField($this->adapter->primaryIdParamName());

        if ($this->isEsEnabled()) {
            if (isset($fields[$idField]) && !empty($fields[$idField])) {
                $id = $fields[$idField];
            }

            if ($id) {
                if ($es = $this->getEs()) {
                    $fields[$idField] = $id;
                    $es->index($fields, $id);
                }
            }
        }
        return $id;
    }

    private function prepareForCreate(&$params)
    {
        $this->adapter->wrapCreateParams($params);

        if (!$this->adapter->wrapWriteParams($params)) {
            return false;
        }
        $defaultValues = $this->adapter->defaultParamValues();
        if (!empty($defaultValues)) {
            foreach ($defaultValues as $k => $v) {
                if (!isset($params[$k])) {
                    $params[$k] = $v;
                }
            }
        }
        if ($createField = $this->adapter->autoIfDelete()) {
            if(!isset($params[$createField]))
                $params[$createField] = 0;
        }
        $time = time();
        if ($createField = $this->adapter->autoCreateTimestamp()) {
            if(!isset($params[$createField]))
                $params[$createField] = $time;
        }
        if ($createField = $this->adapter->autoCreateTimestamp()) {
            if(!isset($params[$createField]))
                $params[$createField] = $time;
        }
        if ($updateField = $this->adapter->autoUpdateTimestamp()) {
            $params[$updateField] = $time;
        }

        $fields = $this->paramPairsToFieldPairs($params);
        return $fields;
    }

    public function batchCreate(array $paramArray)
    {
        self::checkDemo();
        $es = $this->getEs();
        $primaryId = $this->adapter->primaryIdParamName();
        $idField = $this->paramNameToField($primaryId);

        $fieldsArray = array();
        foreach ($paramArray as $params) {
//                var_dump($params);
            $fields = $this->prepareForCreate($params);
            /*
            if ($es && empty($fields[$idField])) {
                throw new Exception('id field must not be empty when batch create with es !', -4008);
            }
            */

            $fieldsArray[] = $fields;
        }

        $ids = $this->getDb()->batchInsert($this->getTable($this->adapter->table()), $fieldsArray);
        $i = 0;

        if ($this->isEsEnabled()) {
            foreach ($fieldsArray as $k => $param) {
                $fieldsArray[$k][$idField] = $ids[$i];
                $i++;
            }
            if ($ids && $es) {
                $es->batchIndex($fieldsArray, $idField);
            }
        }
    }

    public function read($params, $pager = array(), $sorter = array(), $ignoreEs = false)
    {
        return $this->readSpecified($params, $this->adapter->defaultReturnParamNames(),
            $pager, $sorter, $ignoreEs);
    }

    public function getGroup($params, $group) {
        if (!$this->adapter->wrapReadParams($params)) {
            return array(
                'statistic' => array('count' => 0),
                'data' => array(),
            );
        }
        $es = $this->getEs();
        return $this->getGroupFromEs($es, $params, $group);
    }

    public function getTable($table) {
        return $table;
    }

    public function readSpecified($params, $specified, $pager = array(), $sorter = array(), $ignoreEs = false)
    {
        $eagle_or = '';
        $eagle_or_filed ='';
        if(isset($params['eagle_or'])) {
            $eagle_or = $params['eagle_or'];
            $eagle_or_filed = $params['eagle_or_filed'];
            unset($params['eagle_or']);
            unset($params['eagle_or_filed']);
        }

        $config = \Yaf_Registry::get('config');
        if (isset($config->es->disabled) && $config->es->disabled) {
            $ignoreEs = true;
        }

        // todo: cache.
        if (!$this->adapter->wrapReadParams($params)) {
            return array(
                'statistic' => array('count' => 0),
                'data' => array(),
            );
        }
        empty($sorter) && ($sorter = $this->adapter->defaultSort());

        if (!$ignoreEs && $es = $this->getEs()) {
            list($stat, $result) = $this->searchFromEs($es, $params, $specified, $pager, $sorter);
        } else {
            $condition = $this->paramsToCondition($params);



            if(is_array($eagle_or)) {
                $or_str ='';
                if(!empty($eagle_or_filed)) {
                    $or_str ='';
                    foreach($eagle_or as $vv) {
                        if(empty($vv)) {
                            continue;
                        }
                        $or_str.='or '.$eagle_or_filed.'  like "%'.$vv.'%"';
                    }

                }
                else {
                    foreach($eagle_or as $filed=>$value) {

                        $v = '';
                        $v = $value;
                        $param_eagle = array('operation' => '=');
                        if (preg_match('/(.*):(.*)/', $value, $matched) && PairMap::exists($matched[1])) {
                            $operation = $matched[1];
                            $value = $matched[2];
                            if($operation=='prefix') {
                                $param_eagle['operation'] = 'like ';
                                $v ='\'%'.$v.'%\'';
                            }
                            else if($operation=='prefix_left') {
                                $param_eagle['operation'] = 'like ';
                                $v ='\''.$v.'%\'';
                            }
                            else if($operation=='prefix_right') {
                                $param_eagle['operation'] = 'like ';
                                $v ='\'%'.$v.'\'';
                            }
                        } else {
                            $param_eagle['operation'] = '=';
                            $v ='"'.$v.'"';
                        }
                        if (strpos($value, ';')) {
                            $value = explode(';', $value);
                            $value = array_values(array_filter($value, function($e) { return '' !== $e; }));
                            if (count($value) && $param_eagle['operation'] == '=') {
                                $param_eagle['operation'] = 'in';
                                $v ='';
                                foreach($value as $vv) {
                                    $v.=',"'.$vv.'"';
                                }
                                if(strlen($v)>1) {
                                    $v = '('.substr($v,1).')';
                                }
                            }
                        }

                        $or_str.=' or '.$filed.' '.$param_eagle['operation'].$v;
                    }
                }



                if(strlen($or_str)>3) {
                    $condition[0].=' and ('.substr($or_str,3).')';
                }
//                    var_dump($condition);

            }
//print_r($condition);
            // db query.
            $db = $this->getDb();

            $count = $db->getCountByCondition($this->getTable($this->adapter->table()), $condition);
            $stat = array('count' => $count);
            $result = array();

            if ($count > 0) {
                $whereClause = $condition[0];
                if (!empty($sorter)) {
                    $sorters = array();
                    foreach ($sorter as $s => $o) {
                        $sorters[] = sprintf('%s %s', $this->paramNameToField($s), $o);
                    }
                    $whereClause .= sprintf(' ORDER BY %s', implode(',', $sorters));
                }
                $offset = 0;

                if ($this->adapter->pagerEnabled()) {
                    if (!empty($pager)) {
                        if (!isset($pager['page']) || $pager['page'] < 1) {
                            $pager['page'] = 1;
                        }
                        $offset = ($pager['page'] - 1) * $pager['page_size'];
                        $limit = $pager['page_size'];
                        $whereClause .= sprintf(' LIMIT %d,%d', $offset, $limit);
                    }
                }

                $condition[0] = $whereClause;
                if ($count > $offset) {
                    $fields = '';
                    if (!empty($specified)) {
                        $fields = $this->paramsToFields($specified);
                        $fields = implode(',', $fields);
                    }
                    $result = $db->getResultsByCondition($this->getTable($this->adapter->table()), $condition, $fields);
                }
            }
        }

        foreach ($result as $k => $v) {
            $result[$k] = $this->fieldPairsToParamPairs($v);
        }

        $retResult = array(
            'statistic' => $stat,
            'data' => $result
        );

        return $this->adapter->wrapListResult($retResult);
    }

    private function eagleOrToCondition($eagle_or, $eagle_or_filed, &$condition){


        if(is_array($eagle_or)) {
            $or_str ='';
            if(!empty($eagle_or_filed)) {
                $or_str ='';
                foreach($eagle_or as $vv) {
                    if(empty($vv)) {
                        continue;
                    }
                    $or_str.='or '.$eagle_or_filed.'  like "%'.$vv.'%"';
                }

            }
            else {
                foreach($eagle_or as $filed=>$value) {

                    $v = '';
                    $v = $value;
                    $param_eagle = array('operation' => '=');
                    if (preg_match('/(.*):(.*)/', $value, $matched) && PairMap::exists($matched[1])) {
                        $operation = $matched[1];
                        $value = $matched[2];
                        if($operation=='prefix') {
                            $param_eagle['operation'] = 'like ';
                            $v ='\'%'.$v.'%\'';
                        }
                        else if($operation=='prefix_left') {
                            $param_eagle['operation'] = 'like ';
                            $v ='\''.$v.'%\'';
                        }
                        else if($operation=='prefix_right') {
                            $param_eagle['operation'] = 'like ';
                            $v ='\'%'.$v.'\'';
                        }
                    } else {
                        $param_eagle['operation'] = '=';
                        $v ='"'.$v.'"';
                    }
                    if (strpos($value, ';')) {
                        $value = explode(';', $value);
                        $value = array_values(array_filter($value, function($e) { return '' !== $e; }));
                        if (count($value) && $param_eagle['operation'] == '=') {
                            $param_eagle['operation'] = 'in';
                            $v ='';
                            foreach($value as $vv) {
                                $v.=',"'.$vv.'"';
                            }
                            if(strlen($v)>1) {
                                $v = '('.substr($v,1).')';
                            }
                        }
                    }

                    $or_str.=' or '.$filed.' '.$param_eagle['operation'].$v;
                }
            }



            if(strlen($or_str)>3) {
                $condition[0].=' and ('.substr($or_str,3).')';
            }
//                    var_dump($condition);

        }
    }

    public function count($params = array(), $ignoreEs = false) {
        $this->adapter->wrapReadParams($params);

        $pager = array('page_size' => 0, 'page' => 1);
        if (!$ignoreEs && $es = $this->getEs()) {
            list($stat, $result) = $this->searchFromEs($es, $params, array(), $pager);
            return $stat['count'];
        }
        $condition = $this->paramsToCondition($params);

        $db = $this->getDb();
        return $db->getCountByCondition($this->getTable($this->adapter->table()), $condition);
    }
    // yjy
    // group by 并且只返回 count
    public function countGroupBy($params = array(), $ignoreEs = false,$groupBy) {
        $this->adapter->wrapReadParams($params);

        $pager = array('page_size' => 0, 'page' => 1);
        if (!$ignoreEs && $es = $this->getEs()) {
            list($stat, $result) = $this->searchFromEs($es, $params, array(), $pager);
            return $stat['count'];
        }
        $condition = $this->paramsToCondition($params);

        $db = $this->getDb();
        return  $db->getManualGroupCondition($this->getTable($this->adapter->table()), $condition,$groupBy);
    }

    public function readIn($inParamName, $inValues, $specified = array(), $params = array())
    {

        if (empty($inValues)) {
            return array();
        }
        $params[$inParamName] = array('operation' => 'in', 'value' => $inValues);
        if (!$this->adapter->wrapReadParams($params)) {
            return array();
        }

        if ($inParamName != $this->adapter->primaryIdParamName() && $es = $this->getEs()) {
            list($stat, $result) = $this->searchFromEs($es, $params, $specified, array('page' => 1, 'page_size' => count($inValues)));
        } else {
            $condition = $this->paramsToCondition($params);

            $fields = '';
            if (!empty($specified)) {
                $fields = $this->paramsToFields($specified);
                $fields = implode(',', $fields);
            }
            $result = $this->getDb()->getResultsByCondition($this->getTable($this->adapter->table()), $condition, $fields);
            $stat = array('count' => count($result));
        }

        foreach ($result as $k => $v) {
            $result[$k] = $this->fieldPairsToParamPairs($v);
        }

        $retResult = array(
            'statistic' => $stat,
            'data' => $result
        );
        $retResult = $this->adapter->wrapListResult($retResult);

        return $retResult['data'];
    }

    private function bindQueryParamsToEs(ESWrapper &$es, $params)
    {
        $queryFields = $this->paramPairsToFieldPairs($params);
        $analysisFields = array();
        foreach ($this->adapter->analysisFields() as $field => $mapping) {
            $field = $this->paramNameToField($field);
            $analysisFields[$field] = 1;
        }
        foreach ($queryFields as $field => $param) {
            $op = 'eq';
            $orLevel = 0;
            if (is_array($param)) {
                if (isset($param['operation'])) {
                    $op = $param['operation'];
                }
                $value = $param['value'];
                if (isset($param['or_level'])) {
                    $orLevel = $param['or_level'];
                }
            } else {
                $value = $param;
            }
            if ($op == 'eq' && isset($analysisFields[$field])) {
                $op = 'match';
            }
            $es->$op($field, $value);
            $es->setLastOrLevel($orLevel);
        }
    }

    private function getGroupFromEs(ESWrapper &$es, $params, $group) {
        if (isset($params['keyword'])) {
            $keyword = $params['keyword'];
            if (isset($keyword['value'])) {
                $keyword = $keyword['value'];
            }
            unset($params['keyword']);
            $searchFields = $this->adapter->keywordFields();
            if (!empty($searchFields)) {
                $wordFields = $this->paramsToFields($searchFields);
                $es->eq($wordFields, $keyword);
            }
        }
        $this->bindQueryParamsToEs($es, $params);
        $group = $this->paramNameToField($group);
        return $es->getGroup($group);
    }

    private function searchFromEs(ESWrapper &$es, $params, $specified, $pager = array(), $sorter = array())
    {
        if (!empty($specified)) {
            $fields = $this->paramsToFields($specified);
            $es->select($fields);
        }

        if (isset($params['keyword'])) {
            $keyword = $params['keyword'];
            if (isset($keyword['value'])) {
                $keyword = $keyword['value'];
            }
            unset($params['keyword']);
            $searchFields = $this->adapter->keywordFields();
            if (!empty($searchFields)) {
                $wordFields = $this->paramsToFields($searchFields);
                $es->eq($wordFields, $keyword);
            }
        }
        $this->bindQueryParamsToEs($es, $params);
        if (!empty($sorter)) {
            $es->cleanOrder();
            foreach ($sorter as $s => $o)
                $es->order($this->paramNameToField($s), $o);
        }
        $limit = 100000;
        if (!empty($pager)) {
            $pageSize = $pager['page_size'];
            if (!isset($pager['page_size']) || $pager['page_size'] < 1) {
                $pageSize = $limit;
            }
            if (!isset($pager['page']) || $pager['page'] < 1) {
                $pager['page'] = 1;
            }
            $es->setOffset($pageSize * ($pager['page'] - 1))->setLimit($pageSize);
        } else {
            $es->setOffset(0)->setLimit($limit);
        }
        //        var_dump($es);

        $result = $es->search();
        $statistics = $es->getLastStatistics();
        if ($pager['page_size'] > $limit) {
            if ($statistics['count'] > $limit) {
                $new_page_count = ceil($statistics['count'] / $limit);
                for ($i = 1; $i <= $new_page_count; $i++) {
                    $es->setOffset($limit * $i)->setLimit($limit);
                    $new_result = $es->search();
                    $result = array_merge($result, $new_result);
                }
            }
        }
        return array($statistics, $result);
    }

    /**
     * @param $params , read only one row
     * @return array|bool
     */
    public function get($params, $specified = array(), $sorter = array()) {
        $db = $this->getDb();

        $this->adapter->wrapReadParams($params);
        $condition = $this->paramsToCondition($params);

        empty($sorter) && ($sorter = $this->adapter->defaultSort());

        $whereClause = $condition[0];
        if (!empty($sorter)) {
            $sorters = array();
            foreach ($sorter as $s => $o) {
                $sorters[] = sprintf('%s %s', $this->paramNameToField($s), $o);
            }
            $whereClause .= sprintf(' ORDER BY %s', implode(',', $sorters));
            $condition[0] = $whereClause;
        }

        $fields = '';
        if (empty($specified)) {
            $specified = $this->adapter->defaultReturnParamNames();
        }
        if (!empty($specified)) {
            $fields = $this->paramsToFields($specified);
            $fields = implode(',', $fields);
        }
        $result = $db->getRowByCondition($this->getTable($this->adapter->table()), $condition, $fields);
        if (!empty($result)) {
            $result = $this->fieldPairsToParamPairs($result);
            return $this->adapter->wrapItemResult($result);
        } else {
            return false;
        }
    }


    public function ManualRelation($main_params,$tow_param,$tow_fields,$flag,DAO $dao2,$col,$col2,$pager = array(), $sorter = array(),$group =array())
    {

        empty($sorter) && ($sorter = $this->adapter->defaultSort());

        $db = $this->getDb();

        if (!$this->adapter->wrapReadParams($main_params)) {
            return false;
        }


        $fields = $this->paramsToFields(array($col));
        $fields2 = $dao2->paramsToFields(array($col2));

        if (!empty($tow_fields)) {
            $tow_fields = $dao2->paramsToFields($tow_fields,$flag.'.');
            $tow_fields = implode(',', $tow_fields);
        }

        $col=$fields[0];
        $col2=$fields2[0];

        $main_condition = $this->paramsToCondition($main_params,'a.');
        $two_condition = $dao2->paramsToCondition($tow_param,$flag.'.');

        if(empty($group)) {
            $count = $db->getManualRelationCount($this->getTable($this->adapter->table()),$this->getTable($dao2->adapter->table()),$col,$col2,$main_condition,$two_condition,$tow_fields,$flag);
            $stat = array('count' => $count);
        }
        else {
            $stat = $db->getManualRelationStaticsCount($this->getTable($this->adapter->table()),$this->getTable($dao2->adapter->table()),$col,$col2,$main_condition,$two_condition,$tow_fields,$flag,$group);
            $count = intval($stat['count_num']);
            $stat['count'] = $count;
        }
        $result = array();

        if ($count > 0) {
            $other='';
            if (!empty($sorter)) {
                $sorters = array();
                foreach ($sorter as $s => $o) {
                    $sorters[] = sprintf('%s %s', $this->paramNameToField($s), $o);
                }
                $other .= sprintf(' ORDER BY %s', 'a.'.implode(',', $sorters));
            }
            $offset = 0;

            if ($this->adapter->pagerEnabled()) {
                if (!empty($pager)) {
                    if (!isset($pager['page']) || $pager['page'] < 1) {
                        $pager['page'] = 1;
                    }
                    $offset = ($pager['page'] - 1) * $pager['page_size'];
                    $limit = $pager['page_size'];
                    $other .= sprintf(' LIMIT %d,%d', $offset, $limit);
                }
            }

            if ($count > $offset) {
                $result = $db->getManualRelationCondition($this->getTable($this->adapter->table()),$this->getTable($dao2->adapter->table()),$col,$col2,$main_condition,$two_condition,$other,$tow_fields,$flag);
            }
        }
        foreach ($result as $k => $v) {
            $result[$k] = $this->fieldPairsToParamPairsRelation($v,$dao2,$flag);
        }
        $retResult = array(
            'statistic' => $stat,
            'data' => $result
        );


        return $this->adapter->wrapListResult($retResult);
    }

    public function ManualRelationGroup($main_params,$tow_param,$group,$tow_fields,$flag,DAO $dao2,$col,$col2,$pager = array(), $sorter = array())
    {

        empty($sorter) && ($sorter = $this->adapter->defaultSort());

        $db = $this->getDb();

        if (!$this->adapter->wrapReadParams($main_params)) {
            return false;
        }


        $fields = $this->paramsToFields(array($col));
        $fields2 = $dao2->paramsToFields(array($col2));

        if (!empty($tow_fields)) {
            $tow_fields = $dao2->paramsToFields($tow_fields,$flag.'.');
            $tow_fields = implode(',', $tow_fields);
        }

        $col=$fields[0];
        $col2=$fields2[0];

        $main_condition = $this->paramsToCondition($main_params,'a.');
        $two_condition = $dao2->paramsToCondition($tow_param,$flag.'.');



//            $count = $db->getManualRelationCount($table,$table2,$col,$col2,$main_condition,$two_condition,$tow_fields,$flag);
        $count = $db->getManualRelationGroupStatics($this->getTable($this->adapter->table()),$this->getTable($dao2->adapter->table()),$col,$col2,$main_condition,$two_condition,$tow_fields,$flag,$group);
        $count_num=intval($count[0]['count_num']);
//            var_dump($count);
        $stat=$count[0];
        $stat['count'] = $count_num;
        $result = array();

        if ($count > 0) {
            $other='';
            if (!empty($sorter)) {
                $sorters = array();
                foreach ($sorter as $s => $o) {
                    $sorters[] = sprintf('%s %s', $this->paramNameToField($s), $o);
                }
                $other .= sprintf(' ORDER BY %s', 'a.'.implode(',', $sorters));
            }
            $offset = 0;

            if ($this->adapter->pagerEnabled()) {
                if (!empty($pager)) {
                    if (!isset($pager['page']) || $pager['page'] < 1) {
                        $pager['page'] = 1;
                    }
                    $offset = ($pager['page'] - 1) * $pager['page_size'];
                    $limit = $pager['page_size'];
                    $other .= sprintf(' LIMIT %d,%d', $offset, $limit);
                }
            }

            if ($count > $offset) {
                $result = $db->getManualRelationCondition($this->getTable($this->adapter->table()),$this->getTable($dao2->adapter->table()),$col,$col2,$main_condition,$two_condition,$other,$tow_fields,$flag);
            }
        }
        foreach ($result as $k => $v) {
            $result[$k] = $this->fieldPairsToParamPairsRelation($v,$dao2,$flag);
        }
        $retResult = array(
            'statistic' => $stat,
            'data' => $result
        );


        return $this->adapter->wrapListResult($retResult);
    }

    public function manualGroup($params,$fields)
    {
        $db = $this->getDb();

        if (!$this->adapter->wrapReadParams($params)) {
            return false;
        }
        $condition = $this->paramsToCondition($params);


        $fields = $this->paramsToFields($fields);
        $fields = implode(',', $fields);

        $result = $db->getManualGroupCondition($this->getTable($this->adapter->table()), $condition, $fields);

        foreach ($result as $k => $v) {
            $result[$k] = $this->fieldPairsToParamPairs($v);
        }
        return $this->adapter->wrapListResult($result);
    }

    public function manualGroupFlag($params,$fields,$col,$flag='sum')
    {
        $db = $this->getDb();

        if (!$this->adapter->wrapReadParams($params)) {
            return false;
        }
        $condition = $this->paramsToCondition($params);


        $fields = $this->paramsToFields($fields);
        $fields = implode(',', $fields);

        $col = $this->paramsToFields(array($col));


        $col = $col[0];


        $result = $db->getManualGroupFromFlag($this->getTable($this->adapter->table()), $condition, $fields,$col,$flag);
        foreach ($result as $k => $v) {
            $result[$k] = $this->fieldPairsToParamPairs($v);
        }

        return $this->adapter->wrapListResult($result);
    }

    //  两表关联查询
    public function relationRead($params,$fields,$col)
    {
        $db = $this->getDb();

        if (!$this->adapter->wrapReadParams($params)) {
            return false;
        }
        $condition = $this->paramsToCondition($params);


        $fields = $this->paramsToFields($fields);
        $fields = implode(',', $fields);

        $mapping = $this->adapter->paramsMapping();
        $col = $mapping[$col];


        $result = $db->getManualGroupSum($this->getTable($this->adapter->table()),$col, $condition, $fields);
        foreach ($result as $k => $v) {
            $result[$k] = $this->fieldPairsToParamPairs($v);
        }

        return $this->adapter->wrapListResult($result);
    }

    /**
     * @param $params
     * @return bool|int
     */
    public function update($params)
    {
        self::checkDemo();
        if($this->isEsEnabled()) {
            if (!$this->adapter->wrapWriteParams($params)) {
                return false;
            }
        }

        $idParam = $this->adapter->primaryIdParamName();
        $id = $params[$idParam];

        unset($params[$idParam]);

        if (!$id) {
            throw new Exception('DAO update 缺少 primary id', -4009);
        }

        $idParam = $this->paramNameToField($idParam);
        if ($updateField = $this->adapter->autoUpdateTimestamp()) {
            $params[$updateField] = time();
        }
        $fields = $this->paramPairsToFieldPairs($params);


        $rowsAffected = $this->getDb()->updateTable($this->getTable($this->adapter->table()), $fields,
            MySQL::condition(sprintf('%s=?', $idParam), $id));

        if ($rowsAffected) {
            if ($this->isEsEnabled()&& $es = $this->getEs()) {
                $es->update($fields, $id);
            }
        }

        return $rowsAffected;
    }

    public function deleteByMark($id, $field = 'if_deleted')
    {
        self::checkDemo();
        $idParam = $this->adapter->primaryIdParamName();
        $fakeParams = array($idParam => $id);
        if (!$this->adapter->wrapWriteParams($fakeParams)) {
            return false;
        }
        $id = $fakeParams[$idParam];
        $idParam = $this->paramNameToField($idParam);
        $params[$field] = 1;
        if ($updateField = $this->adapter->autoUpdateTimestamp()) {
            $params[$updateField] = time();
        }
        $fields = $this->paramPairsToFieldPairs($params);


        $rowsAffected = $this->getDb()->updateTable($this->getTable($this->adapter->table()), $fields,
            MySQL::condition(sprintf('%s=?', $idParam), $id));

        if ($rowsAffected) {
            if ($es = $this->getEs()) {
                $es->delete($id);
            }
        }

        return $rowsAffected;
    }

    public function updateByQuery($toUpdate, $query)
    {
        self::checkDemo();
        if (!$this->adapter->wrapWriteParams($toUpdate)) {
            return false;
        }
        if ($updateField = $this->adapter->autoUpdateTimestamp()) {
            $toUpdate[$updateField] = time();
        }

        $toUpdateFieldValues = $this->paramPairsToFieldPairs($toUpdate);

        if ($this->getDb()->updateTable($this->getTable($this->adapter->table()), $toUpdateFieldValues,
            $this->paramsToCondition($query))
        ) {
            if ($es = $this->getEs()) {
                $primaryIdParamName = $this->adapter->primaryIdParamName();
                $list = $this->readSpecified($query, array($primaryIdParamName))['data'];
                if (!empty($list)) {
                    $ids = ArrayUtil::getUniqueCols($list, $primaryIdParamName);
                    $es->bulkUpdate($ids, array('doc' => $toUpdateFieldValues, 'doc_as_upsert' => true));
                }
            }
        }
    }

    public function dbCount($params,$field='1')
    {
        return $this->getDb()->getCountByCondition($this->getTable($this->adapter->table()),
            $this->paramsToCondition($params),$field);
    }

    public function increaseField($field, $query, $inc = 1)
    {
        $affectedCount = $this->getDb()->incField($this->getTable($this->adapter->table()), $this->paramNameToField($field),
            $this->paramsToCondition($query), $inc);

        if ($affectedCount > 0) {
            if ($es = $this->getEs()) {
                $this->changeFieldCountInEs($es, $field, $query, $inc);
            }
        }
    }

    public function decreaseField($field, $query, $dec = 1)
    {
        $affectedCount = $this->getDb()->decField($this->getTable($this->adapter->table()), $this->paramNameToField($field),
            $this->paramsToCondition($query), $dec);

        if ($affectedCount > 0) {
            if ($es = $this->getEs()) {
                $this->changeFieldCountInEs($es, $field, $query, -$dec);
            }
        }
    }

    public function changeFieldCountInEs(ESWrapper &$es, $field, $query, $diff)
    {
        $primaryIdParamName = $this->getAdapter()->primaryIdParamName();
        $field = $this->paramNameToField($field);
        if ($docId = $query[$primaryIdParamName]) {
            $es->update(array('script' => sprintf('ctx._source.%s += count', $field),
                'upsert' => array($field => $diff),
                'params' => array('count' => $diff)), $docId, true);
        } else {
            $list = $this->readSpecified($query, array($primaryIdParamName))['data'];
            if (!empty($list)) {
                $ids = ArrayUtil::getUniqueCols($list, $primaryIdParamName);
                $es->bulkUpdate($ids,
                    array('script' => sprintf('ctx._source.%s += count', $field),
                        'upsert' => array($field => $diff),
                        'params' => array('count' => $diff)));
            }
        }
    }

    //    public function increaseField($field, $params, $inc=1) {
    //        return $this->getDb()->incField($this->adapter->table(), $this->paramNameToField($field),
    //            $this->paramsToCondition($params), $inc);
    //    }
    //
    //    public function decreaseField($field, $params, $dec=1) {
    //        return $this->getDb()->decField($this->adapter->table(), $this->paramNameToField($field),
    //            $this->paramsToCondition($params), $dec);
    //    }

    /**
     * @param $params
     * @return PDOStatement
     */
    public function delete($params)
    {
        self::checkDemo();
        $idParam = $this->adapter->primaryIdParamName();
        $id = $params[$idParam];
        unset($params[$idParam]);
        $idParam = sprintf('%s%s', $this->adapter->fieldPrefix(), $idParam);


        $this->getDb()->delRowByCondition2($this->getTable($this->adapter->table()),
            MySQL::condition(sprintf('%s=?', $idParam), $id));

        if ($es = $this->getEs()) {
            $es->delete($id);
        }
    }

    public function deleteByQuery($params)
    {
        self::checkDemo();
        if (!$this->adapter->wrapReadParams($params)) {
            return false;
        }


        $this->getDb()->delRowByCondition2($this->getTable($this->adapter->table()), $this->paramsToCondition($params));
        if ($this->isEsEnabled() && $es = $this->getEs()) {
            $this->bindQueryParamsToEs($es, $params);
            $es->deleteByQuery();
        }
    }

    public function getDb($mysql_user = null)
    {
        $adapter = $this->adapter;
        $db = ($adapter->dbConfig()) ? $adapter->dbConfig() : $adapter->database();

        return Remote::getDb($db,$mysql_user);
    }

    public function getDbOld($mysql_user = null)
    {
        $adapter = $this->adapter;
        $db = ($adapter->dbConfig()) ? $adapter->dbConfig() : $adapter->database();
        return Remote::getDbOld($db,$mysql_user);
    }

    /**
     * @return ESWrapper
     */
    public function getEs($isFuzzy = false)
    {
        $adapter = $this->adapter;
        $esName = ($isFuzzy || $this->_useFuzzySearch) ? $adapter->fuzzySearcher() : $adapter->searcher();
        if ($esName) {
            $es = Remote::getEs($esName);
            $es->reset();
            $requestParams = ($isFuzzy || $this->_useFuzzySearch) ? $adapter->fuzzySearchRequestParams() : $adapter->searchRequestParams();
            $es->setRequestParams($requestParams);
            return $es;
        } else {
            return false;
        }
    }

    public function getFuzzyEs()
    {
        return $this->getEs(true);
    }

    /**
     * @return Redis
     */
    public function getRedis()
    {
        return Remote::getRedis($this->adapter->redis());
    }

    public function paramNameToField($paramName)
    {
        $fieldPrefix = $this->adapter->fieldPrefix();
        $mapping = $this->adapter->paramsMapping();
        $field = isset($mapping[$paramName]) ? $mapping[$paramName] : $paramName;
        return sprintf('%s%s', $fieldPrefix, $field);
    }

    public function paramsToFields($params,$flag='')
    {
        $fields = array();
        $fieldPrefix = $this->adapter->fieldPrefix();
        $mapping = $this->adapter->paramsMapping();
        foreach ($params as $k) {
            $field = isset($mapping[$k]) ? $mapping[$k] : $k;
            $field = sprintf('%s%s', $fieldPrefix, $field);
            $fields[] = $flag.$field;
        }
        return $fields;
    }

    public function paramPairsToFieldPairs($paramPairs,$flag='')
    {
        $fieldPairs = array();
        $fieldPrefix = $this->adapter->fieldPrefix();
        $mapping = $this->adapter->paramsMapping();
        foreach ($paramPairs as $k => $v) {
            $field = isset($mapping[$k]) ? $mapping[$k] : $k;
            $field = sprintf('%s%s', $fieldPrefix, $field);
            $fieldPairs[$flag.$field] = $v;
        }

        return $fieldPairs;
    }

    public function fieldPairsToParamPairs($filedPairs)
    {
        $paramPairs = array();
        $fieldPrefix = $this->adapter->fieldPrefix();
        $mapping = $this->adapter->paramsMapping();
        $mapping = array_flip($mapping);
        $prefixLength = strlen($fieldPrefix);
        foreach ($filedPairs as $k => $v) {
            if(is_null($v)) {
                $v ='';
            }
            $field = substr($k, $prefixLength);
            if(isset($mapping[$field])) {
                $paramPairs[$mapping[$field]] = $v;
            }
            else if($field =='group_num' || $field =='Fgroup_num') {
                $paramPairs['num'] = $v;
            }
        }

        return $paramPairs;
    }

    public function fieldPairsToParamPairsRelation($filedPairs,DAO $dao,$flag)
    {
        $paramPairs = array();
        $fieldPrefix = $this->adapter->fieldPrefix();
        $fieldPrefix2 = $dao->adapter->fieldPrefix();
        $mapping = $this->adapter->paramsMapping();
        $mapping2 = $dao->adapter->paramsMapping();
        $mapping = array_flip($mapping);
        $mapping2 = array_flip($mapping2);
        $prefixLength = strlen($fieldPrefix);
        $prefixLength2 = strlen($fieldPrefix2);

        foreach ($filedPairs as $k => $v) {
            $field = substr($k, $prefixLength);
            $field2 = substr($k, $prefixLength2);
            if(isset($mapping[$field])) {
                $param = isset($mapping[$field]) ? $mapping[$field] : $field;
                $paramPairs[$param] = $v;
            }
            else if(isset($mapping2[$field2])) {
                $param = isset($mapping2[$field2]) ? $mapping2[$field2] : $field2;
                $paramPairs[$flag.'.'.$param] = $v;
                $paramPairs[$flag][$param] = $v;
            }
//                $param = isset($mapping[$field]) ? $mapping[$field] : $field;
//                $paramPairs[$param] = $v;
        }

        return $paramPairs;
    }

    public function paramsToCondition($params,$flag='')
    {
        $whereClause = '';
        $bindParams = array();
        $fields = $this->paramPairsToFieldPairs($params);

        foreach ($fields as $f => $v) {
            $operation = 'eq';
            $value = $v;
            if (is_array($value)) {
                $operation = $value['operation'];
                $value = $v['value'];
            }
            $this->appendOperation($whereClause, $bindParams, $flag.$f, $operation, $value);
        }

        return array($whereClause, $bindParams);
    }

    private function appendOperation(&$whereClause, &$bindParams, $field, $operation, $value)
    {

        if (!empty($whereClause)) {
            $whereClause .= ' AND';
        }
        $whereClause .= sprintf(' %s', $field);

        if ($operation == 'in') {
            $placeholders = array_pad(array(), count($value), '?');
            $whereClause .= sprintf(' IN (%s)', implode(',', $placeholders));
            foreach ($value as $v) {
                $bindParams[] = $v;
            }
        }
        else if ($operation == 'no_in') {
            $placeholders = array_pad(array(), count($value), '?');
            $whereClause .= sprintf(' NOT IN (%s)', implode(',', $placeholders));
            foreach ($value as $v) {
                $bindParams[] = $v;
            }
        }
        else if ($operation == 'range') {
            $this->appendOperation($whereClause, $bindParams, $field, 'ge', $value[0]);
            $this->appendOperation($whereClause, $bindParams, $field, 'le', $value[1]);
        }
        else if ($operation == 'rangegt') {
            $this->appendOperation($whereClause, $bindParams, $field, 'gt', $value[0]);
            $this->appendOperation($whereClause, $bindParams, $field, 'le', $value[1]);
        }
        else if ($operation == 'rangelt') {
            $this->appendOperation($whereClause, $bindParams, $field, 'ge', $value[0]);
            $this->appendOperation($whereClause, $bindParams, $field, 'lt', $value[1]);
        }
        else if ($operation == 'prefix') {
            $whereClause .= ' LIKE ?';
            $bindParams[] = '%'.$value . '%';
        }
        else if ($operation == 'prefix_left') {
            $whereClause .= ' LIKE ?';
            $bindParams[] = ''.$value . '%';
        }
        else if ($operation == 'prefix_right') {
            $whereClause .= ' LIKE ?';
            $bindParams[] = '%'.$value . '';
        }
        else if($operation == 'not_null'){
            $whereClause .= ' is not null';
        }
        else if($operation == 'is_null'){
            $whereClause .= ' is null';
        } else {
            $operationMap = array(
                'lt' => '<',
                'le' => '<=',
                'gt' => '>',
                'ge' => '>=',
                'eq' => '=',
                'neq'=> '!='
            );

            if (isset($operationMap[$operation])) {
                $whereClause .= sprintf(' %s ?', $operationMap[$operation]);
                $bindParams[] = $value;
            }
        }
    }

    // 关闭es
    public function closeEs()
    {
        \Yaf_Registry::set('close_es', 1);
    }

    public function isEsEnabled()
    {
        $close_es = \Yaf_Registry::get('close_es');
        if ($close_es == "1") {
            return false;
        }
        return true;
    }


    public function readGroupSpecified($params,$group, $specified, $pager = array(), $sorter = array())
    {
        $eagle_or = '';
        $eagle_or_filed ='';
        if(isset($params['eagle_or'])) {
            $eagle_or = $params['eagle_or'];
            $eagle_or_filed = $params['eagle_or_filed'];
            unset($params['eagle_or']);
            unset($params['eagle_or_filed']);
        }


        if (!$this->adapter->wrapReadParams($params)) {
            return array(
                'statistic' => array('count' => 0),
                'data' => array(),
            );
        }
        empty($sorter) && ($sorter = $this->adapter->defaultSort());

        $condition = $this->paramsToCondition($params);
        $this->eagleOrToCondition($eagle_or, $eagle_or_filed, $condition);
        // db query.
        $db = $this->getDb();

        $stat = $db->getStatics($this->getTable($this->adapter->table()), $condition,$group);

        $count = intval($stat['count_num']);
        $stat['count'] = $count;
        $result = array();

        if ($count > 0) {
            $whereClause = $condition[0];
            if (!empty($sorter)) {
                $sorters = array();
                foreach ($sorter as $s => $o) {
                    $sorters[] = sprintf('%s %s', $this->paramNameToField($s), $o);
                }
                $whereClause .= sprintf(' ORDER BY %s', implode(',', $sorters));
            }
            $offset = 0;

            if ($this->adapter->pagerEnabled()) {
                if (!empty($pager)) {
                    if (!isset($pager['page']) || $pager['page'] < 1) {
                        $pager['page'] = 1;
                    }
                    $offset = ($pager['page'] - 1) * $pager['page_size'];
                    $limit = $pager['page_size'];
                    $whereClause .= sprintf(' LIMIT %d,%d', $offset, $limit);
                }
            }

            $condition[0] = $whereClause;
            if ($count > $offset) {
                $fields = '';
                if (!empty($specified)) {
                    $fields = $this->paramsToFields($specified);
                    $fields = implode(',', $fields);
                }
                $result = $db->getResultsByCondition($this->getTable($this->adapter->table()), $condition, $fields);
            }
        }

        foreach ($result as $k => $v) {
            $result[$k] = $this->fieldPairsToParamPairs($v);
        }

        $retResult = array(
            'statistic' => $stat,
            'data' => $result
        );

        return $this->adapter->wrapListResult($retResult);
    }
}



