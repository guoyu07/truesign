<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/20
 * Time: 21:22
 */

namespace Royal\Util;


class Decrypt
{
    static function  encryption($info,$salt, $type = 0,$time_salt = 10*24*60*60) {
        $salt = md5($salt);
        $key = sha1('iamsee.com:'.$salt);
        if (!$type) {
            $info = $info.','.(time()+$time_salt);
            $encryption_info = $info ^ $key;
            $encode_key =  base64_encode($encryption_info);
            return $encode_key;
        }

        $info = base64_decode($info);
        $info = $info ^ $key;
        $info_add_limit_time = explode(',',$info);
        $decode_key = $info_add_limit_time;
        return $decode_key;
    }


}