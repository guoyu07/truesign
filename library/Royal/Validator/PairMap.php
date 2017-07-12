<?php
/**
 * User: iamsee
 * Date: 14/10/29
 * Time: PM9:36
 */

namespace Royal\Validator;


class PairMap{
    private static  $_operation = array(
        // http参数 -> es操作方法
        'lt' => 'lt',
        'le' => 'le',
        'gt' => 'gt',
        'ge' => 'ge',
        'eq' => 'eq',
        'range' => 'range',
        'prefix' => 'prefix',
        'prefix_left' => 'prefix_left',
        'prefix_right' => 'prefix_right',
        'in' => 'in',
        'or' => 'or',
    );

    private static $_oppositeOperation = array(
        'lt' => 'gt',
        'le' => 'ge',
        'gt' => 'lt',
        'ge' => 'le',
    );

    static public function opposite($operation) {
        return static::$_oppositeOperation[$operation];
    }

    static public function hasOpposite($operation) {
        return isset(static::$_oppositeOperation[$operation]);
    }

    static public function exists($operation) {
        return isset(static::$_operation[$operation]);
    }
}