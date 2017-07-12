<?php
namespace Royal\Data;


use Royal\Data\Remote;

class BaseRedis {
    protected static $_prefixRedis = null;
    protected static $_normalRedis = null;
    protected static $_isPrefixEnabled = true;
    protected static $_oldPrefixState = true;

    public static function getClient() {
        if (!self::$_prefixRedis) {
            self::$_prefixRedis = Remote::getRedis('web');
        }
        self::$_prefixRedis->setOption(\Redis::OPT_PREFIX, '');

        return self::$_prefixRedis;
    }

    public static function set($key, $value, $ttl = 0) {
        $result = self::getClient()->set($key, $value);
        if ($ttl) {
            self::getClient()->expire($key, $ttl);
        }
        return $result;
    }

    public static function dailySet($key, $value) {
        $result = self::getClient()->set($key, $value);
        self::dailyExpire($key);
        return $result;
    }

    public static function weeklySet($key, $value) {
        $result = self::getClient()->set($key, $value);
        self::weeklyExpire($key);
        return $result;
    }

    public static function get($key) {
        return self::getClient()->get($key);
    }

    public static function rename($key1, $key2) {
        return self::getClient()->rename($key1, $key2);
    }

    public static function incrBy($key) {
        return self::getClient()->incrBy($key,1);
    }

    public static function delete($key) {
        return self::getClient()->delete($key);
    }

    public static function hSet($key, $field, $value) {

        return self::getClient()->hSet($key, $field, $value);
    }

    public static function hGet($key, $field) {
        return self::getClient()->hGet($key, $field);
    }

    public static function hDel($key, $field) {
        return self::getClient()->hDel($key, $field);
    }

    public static function hKeys($key) {
        return self::getClient()->hKeys($key);
    }

    public static function publish($channel, $msg) {
        return self::getClient()->publish($channel, $msg);
    }

    public static function subscribe($channel, $callback) {
        return self::getClient()->subscribe($channel, $callback);
    }

    public static function multi() {
        return self::getClient()->multi();
    }

    public static function hGetAll($key) {
        return self::getClient()->hGetAll($key);
    }

    public static function hExists($key, $field) {
        return self::getClient()->hExists($key, $field);
    }

    public static function weeklyExpire($key) {
        self::getClient()->expireAt($key, strtotime(date('Y-m-d'))+7*86400);
    }

    public static function dailyExpire($key) {
        self::getClient()->expireAt($key, strtotime(date('Y-m-d'))+86400);
    }
    public static function expire($key,$time) {
        self::getClient()->expire($key, $time);
    }

    public static function expireAt($key,$time) {
        self::getClient()->expireAt($key, $time);
    }

    public static function hIncrBy($key, $field, $interval = 1) {
        return self::getClient()->hIncrBy($key, $field, $interval);
    }

    public static function hLen($key) {
        return self::getClient()->hLen($key);
    }
    public static function keys($keys){
        return self::getClient()->keys($keys);
    }
}
