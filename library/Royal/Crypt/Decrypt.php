<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/20
 * Time: 21:22
 */

namespace Royal\Crypt;


class Decrypt
{
    static function  encryption($info,$id, $type = 0,$time_salt = 10*24*60*60) {
        $salt = md5($id);
        $key = sha1('@IAMSEE·TRUESIGN'.$salt);
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