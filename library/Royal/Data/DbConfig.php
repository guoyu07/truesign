<?php
namespace Royal\Data;


use function var_dump;

class DbConfig {
    protected static $_dbConf = array();


    public function getAttrByDbField($row) {
        $type = $row['Type'];
        $length = 0;
        if (strpos($type, '(')) {
            list($type, $length) = explode('(', rtrim($type, ')'));
        }
        $attr = array(
            'type' => $type,
            'length' => $length,
        );
        if (strpos($row['Extra'], 'auto_increment') !== false) {
            $attr['auto_increment'] = true;
        }
        return $attr;
    }

    public function diffKey(DAOAdapter $adapter,$mysql_user = null) {
        $exceptKeys = $this->analysisKeyByAdapter($adapter);

        $currentKeys = $this->analysisKeyByDb($adapter,$mysql_user);
        echo $adapter->table().PHP_EOL;
        if($adapter->table() === 'business'){
            var_dump($exceptKeys);
        }

        $table = Remote::getTable($adapter->table_Prefix().$adapter->table());
        $sqls = array();


        foreach ($exceptKeys as $name => $key) {

            if (!isset($currentKeys[$name])) {
                $keyField = join(',', $key['field']);
                if ($key['type'] == 'unique') {
                    $keyType = 'UNIQUE KEY';
                } elseif ($key['type'] == 'primary') {
                    $keyType = 'PRIMARY KEY AUTO_INCREMENT';
                } else {
                    $keyType = 'KEY';
                }

                $sqls[] = "ADD $keyType ($keyField)";
            }
            unset($currentKeys[$name]);
        }

        foreach ($currentKeys as $name => $key) {
            $keyName = $key['name'];
            $sql = "DROP KEY `$keyName`";
            array_unshift($sqls, $sql);
        }

        if ($sqls) {
            $sqls = "ALTER TABLE `$table` " . join(', ', $sqls);
            return array($sqls);
        }

        return $sqls;
    }

    public function analysisKeyByAdapter(DAOAdapter $adapter) {
        $dao = DAO::instance($adapter);
        $keys = $adapter->keyConf();

        foreach ($keys as $k => $row) {
            $fields = array();
            foreach ($row['field'] as $field) {
                $fields[] = $dao->paramNameToField($field);
            }
            sort($fields);
            $keys[$k]['field'] = $fields;
        }

        $id = $adapter->primaryIdParamName();
        if ($id) {
            $idField = $dao->paramNameToField($id);
            $keys["primary_$idField"] = array(
                'type' => 'primary',
                'field' => array($idField),
            );
        }

        $createtimeField = $adapter->autoCreateTimestamp();
        if ($createtimeField) {
            $createtimeField = $dao->paramNameToField($createtimeField);
            $keys[$createtimeField] = array(
                'type' => 'normal',
                'field' => array($createtimeField),
            );
        }

        $uptimeField = $adapter->autoUpdateTimestamp();
        if ($uptimeField) {
            $uptimeField = $dao->paramNameToField($uptimeField);
            $keys[$uptimeField] = array(
                'type' => 'normal',
                'field' => array($uptimeField),
            );
        }

        $rows = array();
        foreach ($keys as $row) {
            $keyName = $row['type'] . '_' . join('_', $row['field']);
            $rows[$keyName] = $row;
        }

        return $rows;
    }

    public function analysisKeyByDb(DAOAdapter $adapter,$mysql_user = null) {
        $db = DAO::instance($adapter,$mysql_user)->getDb($mysql_user);
        $table = $adapter->table_Prefix().$adapter->table();
        $exists = $db->get_var("show tables like '$table'");
        if (!$exists) {
            return array();
        }

        $keyRows = array();
        $rows = $db->get_results("show keys in `$table`");
        foreach ($rows as $row) {
            if (!isset($keyRows[$row['Key_name']])) {
                if ($row['Key_name'] == 'PRIMARY') {
                    $type = 'primary';
                } elseif ($row['Non_unique'] == 0) {
                    $type = 'unique';
                } else {
                    $type = 'normal';
                }
                $keyRows[$row['Key_name']] = array(
                    'field' => array(),
                    'type' => $type,
                    'name' => $row['Key_name'],
                );
            }
            $keyRows[$row['Key_name']]['field'][] = $row['Column_name'];
        }

        $rows = array();
        foreach ($keyRows as $k => $row) {
            $field = $row['field'];
            sort($field);
            $keyName = $row['type'] . '_' . join('_', $field);
            $rows[$keyName] = array(
                'type' => $row['type'],
                'field' => $field,
                'name' => $row['name'],
            );
        }

        return $rows;
    }

    public function analysisTable(DAOAdapter $adapter,$mysql_user = null) {
        $db = DAO::instance($adapter)->getDb($mysql_user);
        $table = $adapter->table_Prefix().$adapter->table();
        $exists = $db->get_var("show tables like '$table'");
        if (!$exists) {
            return array();
        }
        $rows = $db->get_results("desc `$table`");
        $attrs = array();
        foreach ($rows as $row) {
            $attrs[$row['Field']] = $this->getAttrByDbField($row);
        }
        return $attrs;
    }

    public function analysisAdapter(DAOAdapter $adapter) {
        $attrs = array();
        $table = $adapter->table();
        $rules = $adapter->paramRules();
        $prefix = $adapter->fieldPrefix();
        $mapping = $adapter->paramsMapping();

        $idField = $mapping[$adapter->primaryIdParamName()];
        $oldid = $idField;

        if(empty($idField)) {
            $idField =$adapter->primaryIdParamName();
        }

        if ($idField) {
            $idField = $prefix . $idField;
            $attrs[$idField] = array();
        }

        foreach ($adapter->paramsMapping() as $k => $field) {
            $attrs["$prefix$field"] = array(
                'type' => $rules[$k]->type,
                'length' => $rules[$k]->max,
            );
        }
        if ($idField) {
            if($rules[$oldid]->type =='bigint') {
                $attrs[$idField]['type'] = 'bigint';
            }
            else {
                $attrs[$idField]['type'] = 'int';
            }
            $attrs[$idField]['key'] = 'primary';
            $attrs[$idField]['auto_increment'] = true;
        }
        if ($createField = $adapter->autoIfDelete()) {
            $attrs["$prefix$createField"] = array(
                'type' => 'int',
                'length' => 1,
            );
        }
        $createtimeField = $adapter->autoCreateTimestamp();
        if ($createtimeField) {
            $attrs["$prefix$createtimeField"] = array(
                'type' => 'timestamp',
//                'length' => 11,
            );
        }
        $uptimeField = $adapter->autoUpdateTimestamp();
        if ($uptimeField) {
            $attrs["$prefix$uptimeField"] = array(
                'type' => 'timestamp',
//                'length' => 11,
            );
        }
        return $attrs;
    }

    public function refreshTable(DAOAdapter $adapter) {
        $db = DAO::instance($adapter)->getDb();

        $sqls = $this->diffTable($adapter);

        foreach ($sqls as $sql) {
            var_dump($sql);
            $db->query($sql);
        }
        return $sqls;
    }
    //更新所有库所有公司单张表
    public function refreshAllDateTable(DAOAdapter $adapter,$mysql_user) {
        $sqls = $this->diffTable($adapter,$mysql_user);
        $db = DAO::instance($adapter,$mysql_user)->getDb($mysql_user);
        foreach ($sqls as $sql) {
            $db->query($sql);
        }
        return $sqls;
    }

    //当前库更新单表key
    public function refreshKey(DAOAdapter $adapter) {
        $db = DAO::instance($adapter)->getDbOld();
        $sqls = $this->diffKey($adapter);
        foreach ($sqls as $sql) {
            $db->query($sql);
        }
        return $sqls;
    }
    //所有有库单表更新key
    public function refreshKeyAllDate(DAOAdapter $adapter,$mysql_user) {
        $sqls = $this->diffKey($adapter,$mysql_user);
        $db = DAO::instance($adapter,$mysql_user)->getDb($mysql_user);
        foreach ($sqls as $sql) {
            $db->query($sql);
        }
        return $sqls;
    }

    public function diffTable(DAOAdapter $adapter,$mysql_user = null) {
        $exceptAttrs = $this->analysisAdapter($adapter);
        $currentAttrs = $this->analysisTable($adapter,$mysql_user);

        if (!$currentAttrs) {

            return $this->getCreateSql($adapter, $exceptAttrs);
        }
        $sql = array();
        $lastField = null;
        $table = Remote::getTable($adapter->table_Prefix().$adapter->table());
        $sqlPrefix = "ALTER TABLE `$table` ";
        foreach ($exceptAttrs as $field => $attr) {
            if (!isset($currentAttrs[$field])) {
                $sql[] = $this->getNewFieldSql($adapter, $field, $attr, $lastField);
            } elseif ($this->isAttrDifferent($attr, $currentAttrs[$field])) {
                $sql[] = $this->getAlterFieldSql($adapter, $field, $attr);
            }
            unset($currentAttrs[$field]);
        }
        foreach ($currentAttrs as $field => $attr) {
            $sql[] = $this->getDropFieldSql($adapter, $field);
        }

        if (!$sql) {
            return array();
        }

        $sql = $sqlPrefix . join(', ', $sql);

        return array($sql);

    }

    public function getDropFieldSql($adapter, $field) {
        $sql = "DROP COLUMN `$field`";
        return $sql;
    }

    public function getAlterFieldSql(DAOAdapter $adapter, $field, $attr) {
        $table = $adapter->table();
        $fieldSql = $this->getFieldSqlByAttr($field, $attr);
        $sql = "MODIFY $fieldSql";
        return $sql;
    }

    public function isAttrDifferent($attr1, $attr2) {
        return ($attr1['type'] != $attr2['type']
            || $attr1['type'] == 'varchar' && $attr1['length'] != $attr2['length']);
    }

    public function getNewFieldSql(DAOAdapter $adapter, $field, $attr, $lastField = null) {
        $table = $adapter->table();
        $fieldSql = $this->getFieldSqlByAttr($field, $attr);
        $sql = "ADD COLUMN $fieldSql";
        if ($lastField) {
            $sql.= " AFTER `$lastField`";
        }
        return $sql;
    }

//    public function getFieldSqlByAttr($field, $attr) {
//        $type = ($attr['type'] == 'varchar') ? $attr['type'] . '(' . $attr['length'] . ')' : $attr['type'];
//        return "`$field` $type";
//    }

    public function getFieldSqlByAttr($field, $attr) {

        $default = ($attr['type'] == 'varchar' || $attr['type'] == 'text') ? "''" : 0;
        if('datetime' === $attr['type']){
            $default = "'".$attr['length']."'";
        }

        $type = ($attr['type'] == 'varchar') ? $attr['type'] . '(' . $attr['length'] . ')' : $attr['type'];
        if($attr['auto_increment'] || $type =='text' || $type =='longtext') {
            return "`$field` $type";
        }

        if($field == 'create_time'){
            $default = 'current_timestamp';
        }
        if($field == 'update_time'){
            $default = 'current_timestamp on update current_timestamp';
        }
//        if($field == 'update_time'){
//            $default = 0;
//        }
        return "`$field` $type default $default";
    }


    public function getCreateSql(DAOAdapter $adapter, $attrs) {

        $table = Remote::getTable($adapter->table_Prefix().$adapter->table());
        $columns = array();
        foreach ($attrs as $field => $attr) {
            $fieldSql = $this->getFieldSqlByAttr($field, $attr);

            $primaryKeySql = (isset($attr['key']) && $attr['key'] == 'primary') ? 'primary key' : '';
            $autoIncSql = ($attr['auto_increment']) ? 'auto_increment' : '';
            $columns[] = "$fieldSql $type $primaryKeySql $autoIncSql";
        }

        $columns = join(",\n", $columns);
//        $sql = "CREATE TABLE `$table`(\n$columns\n) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $sql = "CREATE TABLE `$table`(\n$columns\n) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        return array($sql);
    }

    public function getWebAdapters() {
        $adapterFiles =APPLICATION_PATH.'/../../common/Adapter/E/*Adapter.php';
        $adapters = array();
        foreach (glob($adapterFiles) as $realfile) {

            if (preg_match('/([^\/]*Adapter).php$/', $realfile, $matched)) {
                if ($matched[1] == 'EagleBaseAdapter') {
                    continue;
                }
                $class = 'Eagle\\Adapter\\E\\' . $matched[1];
                if (class_exists($class)) {
                    $adapter = new $class;
                    $adapters[] = $adapter;
                }
            }
        }
        return $adapters;
    }

    public function diffAllKeys() {
        $adapters = $this->getWebAdapters();
        $rows = array();

        foreach ($adapters as $adapter) {
            $row = $this->diffKey($adapter);
            if ($row) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function diffAllTable() {
        $adapters = $this->getWebAdapters();
        $rows = array();

        foreach ($adapters as $adapter) {
            $row = $this->diffTable($adapter);
            if ($row) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function refreshAllTable() {
        $adapters = $this->getWebAdapters();
        foreach ($adapters as $adapter) {
            $this->refreshTable($adapter);
        }
    }


    public function refreshAllKeys() {
        $adapters = $this->getWebAdapters();
        foreach ($adapters as $adapter) {
            $this->refreshKey($adapter);
        }
    }

    public function refreshAllIndex() {
        $adapters = $this->getWebAdapters();
        foreach ($adapters as $adapter) {
            if(!$adapter->companyIdField()) {
                continue;
            }
            if ($adapter->searcher() || $adapter->fuzzySearcher()) {
                $indexer = new ESIndexer();
                $indexer->rebuildIndex($adapter, 2000);
            }
        }
    }


    public function updateAllIndex($company_id) {
        $adapters = $this->getWebAdapters();
        foreach ($adapters as $adapter) {
            if(!$adapter->companyIdField()) {
                continue;
            }
            if ($adapter->searcher() || $adapter->fuzzySearcher()) {
                $indexer = new ESIndexer();
                $indexer->updateIndex($company_id,$adapter, 2000);
            }
        }
    }

}

