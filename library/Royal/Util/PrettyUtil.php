<?php

namespace Royal\Util;

class PrettyUtil {
    /**
     * Deprecate
     * Make string with both Chinese and English much more pretty
     * XXX
     */
    public static function prettyString($str) {
        $str = preg_replace('/([^\x21-\x7e，。？；：’‘”“【】、·~￥…（）\— ])([a-zA-Z0-9])/u', '\1 \2', $str);
        $str = preg_replace('/([a-zA-Z0-9])([^\x21-\x7e，。？；：’‘”“【】、·~￥…（）\— ])/u', '\1 \2', $str);
        return $str;
    }
}
