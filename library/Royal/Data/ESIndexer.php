<?php


namespace Royal\Data;

use \PDO;
use Halo\Data\DAOAdapter;

class ESIndexer {
    /**
     * @var DAOAdapter
     */
    private $adapter;

    /**
     * @return ESWrapper
     */
    public function getEs() {
        return Remote::getEs($this->adapter->searcher());
    }

    public function getFuzzyEs() {
        return Remote::getEs($this->adapter->fuzzySearcher());
    }

    /**
     * @return \Halo\Data\MySQL
     */
    public function getDb() {
        $dbConf = $this->adapter->dbConfig();
        $db = ($dbConf) ? $dbConf : $this->adapter->database();
        return Remote::getDb($db);
    }

    public function descTable() {
        $tableName = $this->adapter->table();
        $sql = "desc `$tableName`";
        $descRows = $this->getDb()->query($sql)->fetchAll(PDO::FETCH_NUM);
        $properties = array();
        foreach ($descRows as $desc) {
            $type = $desc[1];
            $fieldName = $desc[0];
            if (empty($tableOptions['select']) || in_array($fieldName, $tableOptions['select'])) {
                if (stripos($type, 'double') !== false) {
                    $type = 'double';
                } elseif (stripos($type, 'float') !== false) {
                    $type = 'float';
                } elseif (stripos($type, 'bigint') !== false) {
                    $type = 'long';
                } elseif (stripos($type, 'int') !== false) {
                    $type = 'integer';
                } else {
                    $type = 'string';
                }
                $properties[$fieldName] = $type;
            }
        }

        return $properties;
    }

    public function paramsToFields($params) {
        $fields = array();
        $fieldPrefix = $this->adapter->fieldPrefix();
        $mapping = $this->adapter->paramsMapping();
        foreach ($params as $k) {
            $field = isset($mapping[$k]) ? $mapping[$k] : $k;
            $field = sprintf('%s%s', $fieldPrefix, $field);
            $fields[] = $field;
        }

        return $fields;
    }

    public function paramNameToField($paramName) {
        $fieldPrefix = $this->adapter->fieldPrefix();
        $mapping = $this->adapter->paramsMapping();
        $field = isset($mapping[$paramName]) ? $mapping[$paramName] : $paramName;
        return sprintf('%s%s', $fieldPrefix, $field);
    }

    public function doIndexAndMapping($es, $indexName, $typeName, $properties = array()) {
        $param = array();
        $param['index'] = strtolower($indexName);
        if ($es->indices()->exists($param)) {
            $es->indices()->delete($param);
        }

        $tokenizer = array();
        $analyzer = array();
        $filter = array();
        foreach ($properties as $k => $property) {
            if (!empty($property['analyzer'])) {
                $analyzer = array_merge($analyzer, $property['analyzer']);
                unset($properties[$k]['analyzer']);

                if (isset($property['tokenizer'])) {
                    $tokenizer = array_merge($tokenizer, $property['tokenizer']);
                    unset($properties[$k]['tokenizer']);
                }

                if (isset($property['filter'])) {
                    $filter = array_merge($filter, $property['filter']);
                    unset($properties[$k]['filter']);
                }

                $properties[$k]['indexAnalyzer'] = $property['name'];
                unset($properties[$k]['name']);
            }
        }
        $param['body']['settings']['analysis'] = array(
            'analyzer' => $analyzer,
            'tokenizer' => $tokenizer,
            'filter' => $filter,
        );

        $es->indices()->create($param);
        $param['type'] = $typeName;
        $param['body'][$typeName]['properties'] = $properties;
        unset($param['body']['settings']);
        $es->indices()->putMapping($param);
//        print_r($param);
//        print_r($properties);exit;
    }

    public function createIndexAndMapping() {
        $fields = $this->descTable();
        $adapter = $this->adapter;

        if ($adapter->searcher()) {
            $requestParam = $this->adapter->searchRequestParams();
            $analysisFields = array();
            foreach ($adapter->analysisFields() as $field => $map) {
                $field = $this->paramNameToField($field);
                $analysisFields[$field] = $map;
            }
            $properties = array();

            foreach ($fields as $field => $type) {
                if ($analysisFields[$field]) {
                    $properties[$field] = $analysisFields[$field];
                } else {
                    $properties[$field] = ESWordIndexer::normal($type);
                }
            }
            $es = $this->getEs()->getClient();
            $this->doIndexAndMapping($es, $requestParam['index'], $requestParam['type'], $properties);
        }

        if ($adapter->isFuzzySearchEnabled() && $adapter->fuzzySearcher()) {
            $keywordFields = $adapter->keywordFields();
            $requestParam = $adapter->fuzzySearchRequestParams();
            $properties = array();
            $idField = $this->paramNameToField($adapter->primaryIdParamName());
            foreach ($keywordFields as $field => $mapping) {
                $field = $this->paramNameToField($field);
                $properties[$field] = $mapping;
            }
            $properties[$idField] = ESWordIndexer::normal();
            if ($adapter->fixCompanyEnabled() && $adapter->companyIdField()) {
                $field = $this->paramNameToField($adapter->companyIdField());
                $properties[$field] = ESWordIndexer::normal();
            }
            if ($adapter->deleteStateField()) {
                $field = $this->paramNameToField($adapter->deleteStateField());
                $properties[$field] = ESWordIndexer::normal();
            }
            if ($adapter->autoUpdateTimestamp()) {
                $field = $this->paramNameToField($adapter->autoUpdateTimestamp());
                $properties[$field] = ESWordIndexer::normal('integer');
            }
            $es = $this->getFuzzyEs()->getClient();
            $this->doIndexAndMapping($es, $requestParam['index'], $requestParam['type'], $properties);
        }
    }

    public function rebuildIndex(DAOAdapter $adapter, $batchCount = 2000) {
        $this->adapter = $adapter;

        $this->createIndexAndMapping();

        $table = $this->adapter->table();
        $idField = $this->paramNameToField($this->adapter->primaryIdParamName());
        $limit = $batchCount;

        $db = $this->getDb();
        $es = $this->getEs();
        $fuzzyEs = $this->getFuzzyEs();

        if ($adapter->searcher()) {
            $param = $this->adapter->searchRequestParams();
            $index = $param['index'];
            $type = $param['type'];

            $es->setRequestParams($param);
            $offset = 0;

            $condition = '';
            $loginInfo = new LoginInfo();
            $company_id= $loginInfo->getCompanyId();
            if($this->adapter->companyIdField()) {
                $condition.='and '.$this->paramNameToField($this->adapter->companyIdField()).'='.$company_id.' ';
            }

            if(strlen($condition)>3) {
                $condition = substr($condition,3);
            }

            $primaryId = $this->paramNameToField($this->adapter->primaryIdParamName());
            $condition.=' order by '.$primaryId.' desc ';
            while (1) {
                echo sprintf('indexing index:%s  type:%s from %d to %d->'.$idField, $index, $type, $offset, $offset + $limit);
                echo "\r\n";
                $data = $db->getResultsByCondition($table, $condition.sprintf(' LIMIT %d,%d', $offset, $limit));
                if (!empty($data)) {

                    $es->batchIndex($data, $idField);
                }
                if (count($data) < $limit) {
                    break;
                }
                $offset += $limit;
            }
        }

        if ($adapter->isFuzzySearchEnabled()) {
            $param = $adapter->fuzzySearchRequestParams();
            $index = $param['index'];
            $type = $param['type'];
            $fuzzyEs->setRequestParams($param);
            $offset = 0;
            $fields = $this->getKeywordSqlFields();

            $condition = '';
            $loginInfo = new LoginInfo();
            $company_id= $loginInfo->getCompanyId();
            if($this->adapter->companyIdField()) {
                $condition.='and '.$this->paramNameToField($this->adapter->companyIdField()).'='.$company_id.' ';
            }

            if(strlen($condition)>3) {
                $condition = substr($condition,3);
            }

            $primaryId = $this->paramNameToField($this->adapter->primaryIdParamName());

            $condition.=' order by '.$primaryId.' desc ';

            while (1) {
                echo sprintf('indexing index:%s  type:%s from %d to %d', $index, $type, $offset, $offset + $limit);
                echo "\r\n";
                $data = $db->getResultsByCondition($table, $condition.sprintf('  LIMIT %d,%d',$offset, $limit), $fields);
                if (!empty($data)) {
                    $fuzzyEs->batchIndex($data, $idField);
                }
                if (count($data) < $limit) {
                    break;
                }
                $offset += $limit;
            }
        }
//        echo 'rebuild index finished';
//        echo "\r\n";
    }

    public function getKeywordSqlFields() {
        $adapter = $this->adapter;
        $fields = $this->paramsToFields(array_keys($adapter->keywordFields()));
        $idField = $this->paramNameToField($adapter->primaryIdParamName());
        array_unshift($fields, $idField);
        if ($adapter->fixCompanyEnabled() && $adapter->companyIdField()) {
            $fields[] = $this->paramNameToField($adapter->companyIdField());
        }
        if ($adapter->deleteStateField()) {
            $fields[] = $this->paramNameToField($adapter->deleteStateField());
        }
        if ($adapter->autoUpdateTimestamp()) {
            $fields[] = $this->paramNameToField($adapter->autoUpdateTimestamp());
        }
        $fields = join(',', $fields);
        return $fields;
    }

    public function increseFuzzyIndex(DAOAdapter $adapter, $companyId, $batchCount = 2000) {
        $this->adapter = $adapter;
        $dao = new DAO($adapter);
        $db = $this->getDb();
        $table = $adapter->table();
        $es = $this->getFuzzyEs();
        $fuzzyEs = $dao->getFuzzyEs();
        $uptimeField = $this->paramNameToField($adapter->autoUpdateTimestamp());
        $idField = $this->paramNameToField($adapter->primaryIdParamName());

        $sql = array();
        $bind = array();

        $fuzzyEs->select(array($uptimeField))->setLimit(1)->order($uptimeField, 'desc');

        if ($adapter->fixCompanyEnabled() && $adapter->companyIdField()) {
            $companyIdField = $this->paramNameToField($adapter->companyIdField());
            $fuzzyEs->eq($companyIdField, $companyId);
            $sql[] = $companyIdField . ' = ?';
            $bind[] = $companyId;
        }

        $latestRow = $fuzzyEs->search();
        $timestamp = (isset($latestRow[$uptimeField])) ? $latestRow[$uptimeField] : 0;

        if(empty($timestamp)) {
            $timestamp = time()-10*60;
        }
        $sql[] = $uptimeField . ' > ?';
        $bind[] = $timestamp;

        if ($adapter->deleteStateField()) {
            $delField = $this->paramNameToField($adapter->deleteStateField());
            $sql[] = $delField . ' = 0';
        }

        $sql = join(' AND ', $sql);
        $fields = $this->getKeywordSqlFields();

        $i = 0;
        while (1) {
            $offset = $i * $batchCount;
            $sqlCondition = $sql . " LIMIT $offset, $batchCount";
            $args = $bind;
            array_unshift($args, $sqlCondition);
            $condition = call_user_func_array('\\Halo\\Data\\MySQL::condition', $args);
            $rows = $db->getResultsByCondition($table, $condition, $fields);
            if (!empty($rows)) {
                $es->batchIndex($rows, $idField);
            }
            if (count($rows) < $batchCount) {
                break;
            }
            $i++;
        }
    }

    public function updateFuzzyIndex() {
        list($rows) = BaseRedis::multi()->hGetAll('eagle_update_time')->delete('eagle_update_time')->exec();
        EagleRedis::revertPrefix();
        $loginInfo = new \Eagle\Service\LoginInfo();
        $loginInfo->disableSession();
        if (!$rows) {
            return false;
        }
        foreach ($rows as $k => $v) {
            if (!strpos($k, ':')) {
                continue;
            }
            list($adapterName, $companyId,$db,$db_name) = explode(':', $k);
            if (!class_exists($adapterName)) {
                continue;
            }
            $adapter = new $adapterName;
            if (!($adapter instanceof DAOAdapter)) {
                continue;
            }
            if (!$adapter->isFuzzySearchEnabled()) {
                continue;
            }
            $loginInfo->setRegistryFiled("company_id", $companyId);
            $loginInfo->setRegistryFiled("mysql_user", $db.':'.$db_name);
//            print_r($db.':'.$db_name);
            $this->increseFuzzyIndex($adapter, $companyId);
        }
        return true;
    }

    // 单独一个公司 更新es数据

    public function updateIndex($company_id,DAOAdapter $adapter, $batchCount = 2000) {
        $this->adapter = $adapter;

        $table = $this->adapter->table();
        $idField = $this->paramNameToField($this->adapter->primaryIdParamName());
        $limit = $batchCount;

        $db = $this->getDb();
        $es = $this->getEs();
        $fuzzyEs = $this->getFuzzyEs();



        if ($adapter->searcher()) {
            $param = $this->adapter->searchRequestParams();
            $index = $param['index'];
            $type = $param['type'];

            $es->setRequestParams($param);
            // 删除次公司的es数据
            $es->eq($this->paramNameToField($this->adapter->companyIdField()),$company_id);
            $es->deleteByQuery();

            $offset = 0;
            while (1) {
                echo sprintf('indexing index:%s  type:%s from %d to %d', $index, $type, $offset, $offset + $limit);
                echo "\r\n";
                $data = $db->getResultsByCondition($table, MySQL::condition($this->paramNameToField($this->adapter->companyIdField())." = ? limit $offset, $limit", $company_id));
                if (!empty($data)) {
                    $es->batchIndex($data, $idField);
                }
                if (count($data) < $limit) {
                    break;
                }
                $offset += $limit;
            }
        }

        if ($adapter->isFuzzySearchEnabled()) {


            $param = $adapter->fuzzySearchRequestParams();
            $index = $param['index'];
            $type = $param['type'];
            $fuzzyEs->setRequestParams($param);

            // 删除次公司的es数据
            $fuzzyEs->eq($this->paramNameToField($this->adapter->companyIdField()),$company_id);
            $fuzzyEs->deleteByQuery();

            $offset = 0;
            $fields = $this->getKeywordSqlFields();
            while (1) {
                echo sprintf('indexing index:%s  type:%s from %d to %d', $index, $type, $offset, $offset + $limit);
                echo "\r\n";
//                $data = $db->getResultsByCondition($table, sprintf('LIMIT %d,%d', $offset, $limit), $fields);
                $data = $db->getResultsByCondition($table, MySQL::condition($this->paramNameToField($this->adapter->companyIdField())." = ? limit $offset, $limit", $company_id),$fields);

                if (!empty($data)) {
                    $fuzzyEs->batchIndex($data, $idField);
                }
                if (count($data) < $limit) {
                    break;
                }
                $offset += $limit;
            }
        }
        echo 'rebuild index finished';
        echo "\r\n";
    }

    /**
     * 隐号通话根据条件写入es
     * @param DAOAdapter $adapter
     * @param string $filed
     * @param string $other_condition
     */
    public function rebuildTimeIndexCall(DAOAdapter $adapter,$filed ='',$other_condition='') {

        $dao = new DAO(new $adapter);
        $data = $dao->read(array(),array('page_size'=>1),array('update_time'=>'desc'));
        $update_time = null;
        if($data['data']) {
            foreach($data['data'] as $v ) {
                $update_time = $v['update_time'];
            }
        }
        if(empty($update_time)) {
            $update_time =0;
        }
        var_dump('update_time:'.$update_time);
        $this->adapter = $adapter;

        $table = $this->adapter->table();
        $idField = $this->paramNameToField($this->adapter->primaryIdParamName());
        $limit = 2000;

        $db = $this->getDb();
        $es = $this->getEs();
        $fuzzyEs = $this->getFuzzyEs();

        if ($adapter->searcher()) {
            $param = $this->adapter->searchRequestParams();
            $index = $param['index'];
            $type = $param['type'];
            $es->setRequestParams($param);
            $offset = 0;

            $condition = '';
            $loginInfo = new LoginInfo();
            $company_id= $loginInfo->getCompanyId();
            if($this->adapter->companyIdField()) {
                $condition.='and '.$this->paramNameToField($this->adapter->companyIdField()).'='.$company_id.' ';
            }
            $condition .= 'and update_time>'.$update_time.$other_condition;

            if(strlen($condition)>3) {
                $condition = substr($condition,3);
            }
            $primaryId = $this->paramNameToField($this->adapter->primaryIdParamName());
            $condition.=' order by update_time asc ';

            while (1) {
                echo sprintf('indexing index:%s  type:%s from %d to %d->'.$idField, $index, $type, $offset, $offset + $limit);
                echo "\r\n";
                echo 'condition: '.$condition.'\n';
                $data = $db->getResultsByCondition($table, $condition.sprintf(' LIMIT %d,%d', $offset, $limit),$filed);
                if (!empty($data)) {
                    //先把修改过的删掉
                    $ids = array();
                    foreach($data as $v){
                        $ids[] = $v[$idField];
                    }
                    $es->bulkDelete($ids);
                    //网站二手房租房搜索列表，只看有图的搜索，需要把封面图的字段给去掉，才能用not_null条件
                    if($table == 'erp_house'){
                        foreach($data as &$v){
                            if(empty($v['cover_image_url'])){
                                unset($v['cover_image_url']);
                            }
                        }
                    }
                    //重新添加es
                    $es->batchIndex($data, $idField);

                    var_dump('ok');
                }
                if (count($data) < $limit) {
                    break;
                }
                $offset += $limit;
            }
        }
    }
}
