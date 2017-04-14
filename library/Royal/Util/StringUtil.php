<?php


namespace Royal\Util;


class StringUtil {
    static function camelToUnderline($word) {
        return preg_replace(
            '/(^|[a-z])([A-Z])/e',
            'strtolower(strlen("\\1") ? "\\1_\\2" : "\\2")',
            $word
        );
    }

    static function underlineToCamel($word) {
        return preg_replace('/(^|_)([a-z])/e', 'strtoupper("\\2")', $word);
    }

    static function isUrl($str) {
        return preg_match('#^(https?://)?([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?$#', $str);
    }

    static function urlArray($str, $del=';') {
        $arr = explode(';', $str);
        if (empty($arr)) {
            return array();
        } else {
            return array_values(array_filter($arr, function($e) {
                return StringUtil::isUrl($e);
            }));
        }
    }
}