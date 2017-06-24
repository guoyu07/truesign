<?php
/**
 * User: iamsee
 * Date: 14/10/30
 * Time: PM2:12
 */

namespace Royal\Crypt;


class AES {
    static function encrypt($str, $iv, $key) {
        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $key, $iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($encrypted);
    }

    static function decrypt($str, $iv, $key) {
        $str = static::hex2bin($str);

        $td = mcrypt_module_open('rijndael-128', '', 'cbc', $iv);

        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return trim($decrypted);
    }

    static function hex2bin($hexData) {
        $binData = '';

        for ($i = 0; $i < strlen($hexData); $i += 2) {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }

        return $binData;
    }
} 