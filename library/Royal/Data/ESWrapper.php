<?php
/**
 * Esearch class
 * Search functions:
 *  setOffset($offset)
 *  setLimit($limit)
 *  setSelectField(array($field1, $field2))
 *  addEqualCondition($field, $value)
 *  addInCondition($field, array($value1, $value2))
 */

namespace Royal\Data;
use Elasticsearch\Client;
use Royal\Prof\TimeStack;

class ESWrapper {
    public static $searchStatistic = array();
    private $_lastStatistics = array();
    private $_condition = array();
    private $_limit = 10;
    private $_order = array();
    private $_uniqueField = null;
    private $_selectField = null;
    private $_offset = 0;

    private $_host;
    private $_requestParams;
    private $_orLevel = array();


    /**
     * @var Elasticsearch\Client
     */
    protected $_client = null;

    /**
     * @return \Elasticsearch\Client
     */
    public function getClient() {
        if (!$this->_client) {
            $this->_client = new Client(array('hosts' => array($this->_host)));
        }

        return $this->_client;
    }


    public function __construct($host = '127.0.0.1') {
        $this->_host = $host;
//        $this->_requestParams = $requestParams;
    }

    public function setLastOrLevel($level = 0) {
        $conditions = $this->getMustCondition();
        if ($level && $conditions) {
            $index = count($conditions) - 1;
            $this->_orLevel[$index] = $level;
        }
        return $this;
    }

    public function appendCondition($condition) {
        $this->_condition['query']['filtered']['query']['bool']['must'][] = $condition;
        return $this;
    }

    public function getMustCondition() {
        if (isset($this->_condition['query']['filtered']['query']['bool']['must'])) {
            return $this->_condition['query']['filtered']['query']['bool']['must'];
        }
        return array();
    }

    public function setRequestParams($requestParams) {
        $this->_requestParams = $requestParams;
        return $this;
    }

    public function reset() {
        $this->_condition = array();
        $this->_uniqueField = null;
        $this->_selectField = null;
        $this->_order = array();
        $this->_lastStatistics = array();
        $this->_orLevel = array();
        return $this;
    }

    public function getUniqueField() {
        return $this->_uniqueField;
    }

    public function setLastStatistics($statistics) {
        $this->_lastStatistics = $statistics;
    }

    public function getLastStatistics() {
        return $this->_lastStatistics;
    }

    public function setCondition($condition) {
        $this->_condition = $condition;
        return $this;
    }

    public function getCondition() {
        if (!$this->_orLevel) {
            return $this->_condition;
        }
        $condition = $this->_condition;
        $mustConditions = $this->getMustCondition();
        $orConditions = array();
        foreach ($this->_orLevel as $k => $level) {
            if (!isset($orConditions[$level])) {
                $orConditions[$level]['bool']['must'] = array();
            }
            $orConditions[$level]['bool']['must'][] = $mustConditions[$k];
            unset($mustConditions[$k]);
        }
        $condition['query']['filtered']['query']['bool']['must'] = array_values($mustConditions);
        $condition['query']['filtered']['filter']['or'] = array_values($orConditions);
        return $condition;
    }

    public function cleanCondition() {
        $this->_condition = array();
        $this->_orLevel = array();
        return $this;
    }

    public function setOrder($order) {
        $this->_order = $order;
        return $this;
    }

    public function getOrder() {
        return $this->_order;
    }

    public function cleanOrder() {
        $this->_order = array();
        return $this;
    }

    public function setLimit($limit) {
        $this->_limit = $limit;
        return $this;
    }

    public function getLimit() {
        return $this->_limit;
    }

    public function setOffset($offset) {
        $this->_offset = $offset;
        return $this;
    }

    public function getOffset() {
        return $this->_offset;
    }

    public function setSelectField($selectedField) {
        $this->_selectField = $selectedField;
        return $this;
    }

    public function getSelectField() {
        return $this->_selectField;
    }

    public function searchByField($field, $value) {
        $condition = array(
            'term' => array(
                $field => $value,
            ),
        );
        $this->appendCondition($condition);
        return $this->search();
    }

    public function maxLimit() {
        return $this->setLimit(100);
    }

    public function select($field) {
        return $this->setSelectField($field);
    }

    public function in($field, $value) {
        return $this->addInCondition($field, $value);
    }
//like
    public function prefix($field, $value) {
        if(!is_array($value)) {
            $value = array($value);
        }
        return $this->addInCondition($field, $value);
    }

    public function lt($field, $value) {
        return $this->addLessThanCondition($field, $value);
    }

    public function le($field, $value) {
        return $this->addLessOrEqualCondition($field, $value);
    }

    public function lte($field, $value) {
        return $this->addLessOrEqualCondition($field, $value);
    }

    public function gt($field, $value) {
        return $this->addGreaterThanCondition($field, $value);
    }

    public function ge($field, $value) {
        return $this->addGreaterOrEqualCondition($field, $value);
    }

    public function gte($field, $value) {
        return $this->addGreaterOrEqualCondition($field, $value);
    }

    public function eq($field, $value) {
        return $this->addEqualCondition($field, $value);
    }

    //���� ����nullֵ��doc  ���SQL:SELECT tags FROM posts  WHERE  tags IS NOT NULL
    public function not_null($field, $value){
        $this->_condition['query']['filtered']['filter']['exists']['field'] = $field;
        return $this;
    }
    //���������not_null
    public function is_null($field, $value){
        $this->_condition['query']['filtered']['filter']['missing']['field'] = $field;
        return $this;
    }

    public function between($field, $from, $to = null) {
        return $this->addRangeCondition($field, $from, $to);
    }

    public function range($field, $from, $to = null) {
        return $this->addRangeCondition($field, $from, $to);
    }

    public function wildcard($field, $value) {
        return $this->addWildcardCondition($field, $value);
    }

    public function startWith($field, $value) {
        return $this->addStartWithCondition($field, $value);
    }

    public function order($field, $desc = false) {
        $sort = ($desc === false || strtolower($desc) == 'asc') ? 'asc' : 'desc';
        $this->_order[] = array($field => $sort);
        return $this;
    }

    public function addGreaterOrEqualCondition($field, $value) {
        $filter = array('gte' => $value);
        return $this->addRangeConditionByFilter($field, $filter);
    }

    public function addGreaterThanCondition($field, $value) {
        $filter = array('gt' => $value);
        return $this->addRangeConditionByFilter($field, $filter);
    }

    public function addLessThanCondition($field, $value) {
        $filter = array('lt' => $value);
        return $this->addRangeConditionByFilter($field, $filter);
    }

    public function addLessOrEqualCondition($field, $value) {
        $filter = array('lte' => $value);
        return $this->addRangeConditionByFilter($field, $filter);
    }

    public function addStartWithCondition($field, $value) {
        $condition = array(
            'prefix' => array($field => $value),
        );
        $this->appendCondition($condition);
        return $this;
    }

    public function addWildcardCondition($field, $value) {
        $condition = array(
            'wildcard' => array($field => $value),
        );
        $this->appendCondition($condition);
        return $this;
    }

    public function match($field, $value, $phrase = false) {
        return $this->addMatchCondition($field, $value, $phrase);
    }

    public function phraseMatch($field, $value) {
        return $this->addPhraseMatchCondition($field, $value);
    }

    public function addPhraseMatchCondition($field, $value) {
        return $this->addMatchCondition($field, $value, true);
    }

    public function addMatchCondition($field, $value, $phrase = false) {
        if (is_array($field)) {
            $condition = array(
                'multi_match' => ($phrase) ?  array(
                    'query' => $value,
                    'fields' => $field,
                    'analyzer' => 'keyword',
                    // 'type' => 'phrase',
                ) : array(
                    'query' => $value,
                    'fields' => $field,
                    'operator' => 'and',
                )
            );
        } else {
            $condition = array(
                'match' => array(
                    $field => ($phrase) ? array(
                        // 'type' => 'phrase',
                        'analyzer' => 'keyword',
                        'query' => $value,
                    ) : array(
                        'query' => $value,
                        'operator' => 'and',
                    ),
                ),
            );
        }
        $this->appendCondition($condition);
        return $this;
    }

    public function addEqualCondition($field, $value) {
        if (is_array($value)) {
            return $this->addInCondition($field, $value);
        }
        if (is_array($field)) {

            $condition = array(
                'multi_match' => array(
                    'query' => $value,
                    'fields' => $field,
                    'operator' => 'and',
//                    'type' => 'phrase',
                ),
            );
        } else {
            $condition = array(
                'term' => array($field => $value),
            );
        }
        $this->appendCondition($condition);
        return $this;
    }

    public function addRangeCondition($field, $from, $to = null) {
        if ($to === null && is_array($from) && count($from) == 2) {
            list($from, $to) = $from;
        }
        if ($to < $from) {
            $t = $to;
            $to = $from;
            $from = $t;
        }
        $filter = array(
            'from' => $from,
            'to' => $to,
        );
        return $this->addRangeConditionByFilter($field, $filter);
    }

    public function addRangeConditionByFilter($field, $filter) {
        $condition = array(
            'range' => array($field => $filter),
        );
        $this->appendCondition($condition);
        return $this;
    }

    public function addInCondition($field, $value) {
        if(count($value)>1000) {
            $value =array_slice($value,0,1000);
        }
        $condition = array(
            'terms' => array($field => array_values($value)),
        );
        $this->appendCondition($condition);
        return $this;
    }

    public function uniq($field) {
        $this->_condition['facets'][$field]['terms']['field'] = $field;
        $this->_uniqueField = $field;
        return $this;
    }

    public function getSearchParam($group = null) {
        $param = $this->getRequestParam();
        $param['from'] = $this->getOffset();
        $param['size'] = $this->getLimit();
        $condition = $this->getCondition();
        if (!empty($condition)) {
            $param['body'] = $condition;
        }
        $selectField = $this->getSelectField();
        if (!empty($selectField)) {
            $param['body']['fields'] = $selectField;
        }

        $order = $this->getOrder();
        if (!empty($order)) {
            $param['body']['sort'] = $order;
        }

        if ($this->_uniqueField) {
            $param['size'] = 0;
            $field = $this->_uniqueField;
            $param['body']['facets'][$field]['terms']['size'] = $this->getLimit();
        }

        if ($group) {
            $param['size'] = 0;
            unset($param['from']);
            unset($param['body']['sort']);
            $param['body']['aggs']['group']['terms']['field'] = $group;
        }
        return $param;
    }

    public function getGroup($group) {
        $param = $this->getSearchParam($group);
        $startTime = microtime();
        $result = $this->getClient()->search($param);
        $timeCost = microtime() - $startTime;
        self::$searchStatistic[] = array(
            'param' => $param,
            'values' => $result,
            'timecost' => $timeCost,
        );
        $rows = array();
        if (!isset($result['aggregations']['group']['buckets'])) {
            return $rows;
        }
        foreach ($result['aggregations']['group']['buckets'] as $row) {
            $rows[$row['key']] = $row['doc_count'];
        }
        return $rows;
    }

    public function search() {
        $param = $this->getSearchParam();
        $startTime = microtime();
        $result = $this->callSearchApi($param);
        $timeCost = microtime() - $startTime;
        self::$searchStatistic[] = array(
            'param' => $param,
            'values' => $result,
            'timecost' => $timeCost,
        );
        return $result;
    }

    public function callSearchApi($param) {
        $result = $this->getClient()->search($param);

        $this->_lastStatistics = array();
        $this->_lastStatistics['timecost'] = $result['took'];
        $this->_lastStatistics['timeout'] = $result['timed_out'];
        $this->_lastStatistics['count'] = $result['hits']['total'];
        $this->_lastStatistics['failure'] = $result['_shards']['failed'];

        $ret = array();
        if ($this->_uniqueField) {
            foreach ($result['facets'][$this->_uniqueField]['terms'] as $item) {
                $ret[] = $item['term'];
            }
        } else {
            foreach ($result['hits']['hits'] as $item) {
                $valueIsArray = 0;
                if (isset($item['_source'])) {
                    $row = $item['_source'];
                } elseif (isset($item['fields'])) {
                    $row = $item['fields'];
                    $valueIsArray = 1;
                } else {
                    $row = array();
                }
                foreach ($row as $k => $v) {
                    $row[$k] = ($valueIsArray) ? $v[0] : $v;
                }
                $ret[] = $row;
            }
        }
        return $ret;
    }

    private function getRequestParam() {
        return $this->_requestParams;
    }

    public function index($attrs, $id) {
        if (empty($attrs)) {
            return false;
        }
        $param = $this->getRequestParam();
        $param['id'] = $id;
        $param['body'] = $attrs;
        return $this->getClient()->index($param);
    }

    public function update($attrs, $id) {
        $param = $this->getRequestParam();
        $param['id'] = $id;
        $param['body']['doc'] = $attrs;
        return $this->getClient()->update($param);
    }

    public function delete($docId) {
        $param = $this->getRequestParam();
        $param['id'] = $docId;
        return $this->getClient()->delete($param);
    }

    public function deleteByQuery() {
        $param = $this->getRequestParam();
        $condition = $this->getCondition();
        if (!empty($condition)) {
            $param['body'] = $condition;
            return $this->getClient()->deleteByQuery($param);
        }
        return false;
    }

    public function batchIndex($attrsArray, $idField) {
        if (empty($attrsArray)) {
            return false;
        }
        $param = $this->getRequestParam();
        $body = array();
        foreach ($attrsArray as $attrs) {
            $body[] = array('index'=>array('_id'=>$attrs[$idField]));
            $body[] = $attrs;
        }
        $param['body'] = $body;

        $response = $this->getClient()->bulk($param);

        return $response;
    }

    public function bulkUpdate($ids, $toUpdate) {
        if (empty($ids)) {
            return false;
        }
        $param = $this->getRequestParam();
        $body = array();
        foreach ($ids as $id) {
            $body[] = array('update'=>array('_id'=>$id, '_retry_on_conflict'=>3));
            $body[] = $toUpdate;
        }
        $param['body'] = $body;
        $response = $this->getClient()->bulk($param);

        return $response;
    }


    public function bulkDelete($ids) {
        if (empty($ids)) {
            return false;
        }
        $param = $this->getRequestParam();
        $body = array();
        foreach ($ids as $id) {
            $body[] = array('delete'=>array('_id'=>$id));
        }
        $param['body'] = $body;
        $response = $this->getClient()->bulk($param);
        return $response;
    }
}
