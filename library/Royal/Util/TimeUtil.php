<?php


namespace Royal\Util;


class TimeUtil {

    static function msTimestamp() {
        return round(microtime(true) * 1000, 0);
    }

    static function timeLeft($leftSeconds) {

        $str = '';
        $days = 0;
        if ($leftSeconds > 86400) {
            $days = intval($leftSeconds / 86400);
            $leftSeconds = $leftSeconds % 86400;

            if ($days > 0) {
                $str .= $days.'天';
            }
        }

        if ($leftSeconds > 3600) {
            $hours = intval($leftSeconds / 3600);
            $leftSeconds = $leftSeconds % 3600;
            if ($hours > 0) {
                $str .= $hours.'小时';
            }
        }

        if ($days > 0) {
            return $str;
        }

        if ($leftSeconds > 60) {
            $minutes = intval($leftSeconds / 60);
            if ($minutes > 0) {
                $str .= $minutes.'分钟';
                return $str;
            }
        }

        return '1分钟';
    }
} 