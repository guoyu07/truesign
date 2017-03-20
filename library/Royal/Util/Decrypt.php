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
    function encryption($info, $type = 0) {
        $salt = md5('iamsee');
        $key = sha1(C('iamsee.com'.$salt));

        if (!$type) {
            return base64_encode($info ^ $key);
        }

        $info = base64_decode($info);
        return $info ^ $key;
    }
}