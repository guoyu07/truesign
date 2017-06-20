<?php
namespace Truesign\Service;

use Eagle\Service\Bank\FinanceLog;
use Halo\Data\DAO;

class Base {
    //通过 已经查询到到结果 已key为关联 从某张表 中 跟进某个字段查询，获取某些值 返回到 原来到结果集中
    public static function getCityIdFromName($city_name) {
        $array = array(
            '天津'=>74,
            '北京'=>2,
            '上海'=>6,
            '青岛'=>289,
        );
        return $array[$city_name];
    }

    /**
     * A表要用到B表中的列。比如A里只有一个FK_ID，需要从B中查出数据
     * @param $result  A表扣的结果集
     * @param $fromKey  A和B关连时，A表中的外键
     * @param DAO $dao B表的DAO对象
     * @param $searchFromCol  A和B关连时，B表中的主键
     * @param $searchToArray 要查询B表中的哪些列
     * @param $otherKey 放在JOSN字符串里的KEY
     * @return mixed
     */
    public static function mergeData($result, $fromKey, DAO $dao, $searchFromCol, $searchToArray, $otherKey) {
        $data = array();

        foreach ($result['data'] as $e) {
            $data[] = $e[$fromKey];
        }
        $ids = array_unique($data);

        if (empty($ids)) {
            $ids[] = '-1';
        }

        $searchToArray = array_merge($searchToArray, array($searchFromCol));
        $rows = $dao->readSpecified(array($searchFromCol => array(
            'operation' => 'in',
            'value' => array_values($ids),
        )), $searchToArray);

        $rowMap = array();
        foreach ($rows['data'] as $k => $v) {
            $rowMap[$v[$searchFromCol]] = $v;
        }
        foreach ($result['data'] as $k => $v) {
            if ($v[$fromKey] && $rowMap[$v[$fromKey]]) {
                $result['data'][$k][$otherKey] = $rowMap[$v[$fromKey]];
            }
        }
        return $result;
    }

    //涉及到 两张表 查询的情况
    public static function mergeSearchData($result, $fromKey, DAO $dao, $searchFromCol, $params, $pager, $otherKey) {
        $data = array();
        $rowMap = array();
        foreach ($result['data'] as $e) {
            $data[] = $e[$fromKey];
            $rowMap[$e[$fromKey]] = $e;
        }
        $ids = array_unique($data);

        if (empty($ids)) {
            $ids[] = '-1';
        }

        $params[$searchFromCol] = array(
            'operation' => 'in',
            'value' => array_values($ids),
        );

//        print_r($params);

        $rows = $dao->read($params, $pager);

        if ($rows['data']) {
            foreach ($rows['data'] as $k => $v) {
                $rows['data'][$k][$otherKey] = $rowMap[$v[$searchFromCol]];
            }
        }

        return $rows;
    }

    // 获取修改 记录
    public static function getModifyContent(DAO $dao, $param) {
        return $dao->getAdapter()->getModifyContent($dao, $param);
    }

    public static function getAddContent(DAO $dao, $param) {
        return $dao->getAdapter()->getAddContent($dao, $param);
    }

    public static function getDelContent(DAO $dao, $param,$cols) {
        return $dao->getAdapter()->getDelContent($dao, $param,$cols);
    }

    // 获取结果结的总数
    public static function getResultTotal($result) {
        if ($result['statistic']) {
            return $result['statistic']['count'];
        }
        return 0;
    }

    // 抛出异常
    public static function throwException($str, $code = -100) {
        throw new \Exception($str, $code);
    }

    /**
     * 从结果集里，得到一个数组，里面只包含一列指定的$col。去重复。
     * @param $result
     * @param $col
     * @return array
     */
    public static function getColArrayFromResult($result, $col) {
        $data = array();
        foreach ($result['data'] as $e) {
            $data[] = $e[$col];
        }
        $ids = array_unique($data);
        return $ids;
    }

    public static function keyValueFromResult($result, $key) {
        $data = array();
        foreach ($result['data'] as $e) {
            $data[$e[$key]] = $e;
        }
        return $data;
    }

    public function apiGetInputRule($result, $res = array()) {
        foreach ($result as &$val) {
            if ($res) {
                if ($val['erpid']) {
                    if (strpos($val['erpid'], '$') === false) {
                        $val['current_value'] = strval($res[$val['erpid']]);
                    } else {
                        $erpId = explode('$', $val['erpid']);
                        $val['current_value'] = $res[$erpId[0]] . '-' . $res[$erpId[1]];
                    }
                }
            }else{  //新增，添加上redis中设置的楼层，总楼层，梯，户
                $login = new \Eagle\Service\LoginInfo();
                $user_id = $login->getUserId();

                $select_params = \Eagle\Service\BaseRedis::get('block_params_'.$user_id);

                if($select_params){ //如果设置的redis没有过期
                    $select_params = json_decode( $select_params, true );

                    switch ($val['erpid'])
                    {
                        case 'floor':
                            $val['current_value'] = $select_params['extra_floor'];
                            $val['cannot_edit'] = true;
                            break;
                        case 'top_floor':
                            $val['current_value'] = $select_params['extra_total_floor_num'];
                            $val['cannot_edit'] = true;
                            break;
                        case 'ladder':
                            $val['current_value'] = $select_params['extra_lists'];
                            $val['cannot_edit'] = true;
                            break;
                        case 'family':
                            $val['current_value'] = $select_params['extra_rooms'];
                            $val['cannot_edit'] = true;
                            break;
                        default:
                            break;
                    }
                }
            }
            if ($val['field_type'] == 'number') {
                $val['type'] = 'input';
                $val['input_type'] = 'number';
                $val['reg'] = '[\\d]+';
            } elseif ($val['field_type'] == 'float') {
                $val['type'] = 'input';
                $val['input_type'] = 'number';
                $val['reg'] = '[\\d]+[\\.]?[\\d]*';
            } elseif ($val['field_type'] == 'input') {
                $val['type'] = 'input';
            } elseif ($val['field_type'] == 'select') {
                $val['type'] = 'select';
            } elseif ($val['field_type'] == 'multi') {
                $val['type'] = 'multi_select';
            } elseif ($val['field_type'] == 'range_float') {
                $val['type'] = 'range';
                $val['input_type'] = 'number';
                $val['reg'] = '[\\d]+[\\.]?[\\d]*';
            } elseif ($val['field_type'] == 'range_number') {
                $val['type'] = 'range';
                $val['input_type'] = 'number';
                $val['reg'] = '[\\d]+';
            } elseif ($val['field_type'] == 'email') {
                $val['type'] = 'range';
                $val['input_type'] = 'email';
            } elseif ($val['field_type'] == 'no_region') {
                $val['type'] = 'region';
                $val['count'] = 0;
            } elseif ($val['field_type'] == 'region') {
                $val['type'] = 'region';
                $val['count'] = 1;
            } elseif ($val['field_type'] == 'mul_region') {
                $val['type'] = 'region';
                $val['count'] = 10;
            } else {
                $val['type'] = $val['field_type'];
            }
            unset($val['field_type']);
        }
        return $result;
    }

    public function setParam($key, $type, $value, &$param) {
        if($type == 'in') {
            if(empty($value)) {
                $value = array('0');
            }
        }
        //            $suffixMap = array('le'=>'以下', 'lt'=>'以下', 'ge'=>'以上', 'gt'=>'以上');
        $param[$key] = array(
            'operation' => $type,
            'value' => $value
        );
    }

    // 增加管理后台日志
    public function addBackendLog($id,$type, $subtype, $content) {
        $backendLog = new BackendLog();
        $backendLog->add($type, $subtype, $id, $content);
    }

    public function uuid()
    {
        list($prefix, $suffix) = explode('.', uniqid(null, 1));
        $uuid = "$prefix$suffix";
        return $uuid;
    }

    public function returnError($msg)
    {
        return array(
            'code'=>-100,
            'desc'=>$msg,
        );
    }
    //二维数组排序
    public function array_sort($arr,$keys,$type='asc'){
        $keysvalue = $new_array = array();
        foreach ($arr as $k=>$v){
            $keysvalue[$k] = $v[$keys];
        }
        if($type == 'asc'){
            asort($keysvalue);
        }else{
            arsort($keysvalue);
        }
        //reset($keysvalue);
        foreach ($keysvalue as $k=>$v){
            $new_array[] = $arr[$k];
        }
        return $new_array;
    }

    //增加管理后台财务日志
    public static function addFinanceLog($id, $type, $sub_type, $content)
    {
        $finance_log = new FinanceLog();
        $finance_log->add($type,$sub_type,$id,$content);
    }

    //阿里云图片缩放（2016-11-28 最新API）
    /*
     * 这里按宽度标准缩放
     */
    public static function zoompic($size){

        $zoom_param = '?x-oss-process=image/resize,m_lfit,w_'.$size.'&';
        return $zoom_param;
    }
}
