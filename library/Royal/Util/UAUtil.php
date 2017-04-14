<?php


namespace Royal\Util;


class UAUtil {
    public static function isMobileDevice() {
        $mobileUa = array('iPhone', 'Android', 'MIDP', 'Opera Mobi', 'Opera Mini', 'BlackBerry', 'HP iPAQ', 'IEMobile', 'Samsung',
            'MSIEMobile', 'Windows Phone', 'HTC', 'LG', 'MOT', 'Nokia', 'Symbian', 'Fennec', 'Maemo', 'Tear', 'Midori',
            'Windows CE', 'WindowsCE', 'Smartphone', '240x320', '176x220', '320x320', '160x160', 'webOS', 'Palm',
            'armv', 'Sagem', 'SGH', 'SonyEricsson', 'MMP', 'UCWEB');

        $ua = $_SERVER['HTTP_USER_AGENT'];
        foreach ($mobileUa as $v) {
            if (strpos($ua, $v) !== false) {
                return true;
            }
        }

        return false;
    }

    public static function fromMobileWeChat() {
       // return false;
        if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
            return true;
        }
        return false;
    }
}