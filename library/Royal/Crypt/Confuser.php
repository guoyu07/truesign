<?php
namespace Royal\Crypt;
class Confuser {
    public static function encode($data, $iv = 'X', $loop = 4) {
        if (strlen($data) < 2) {
            return $data;
        }
        $map = str_split($data);
        foreach ($map as $k => $v) {
            $map[$k] = ord($v);
        }
        $x = ord($iv);
        for ($i = 0; $i < $loop; $i++) {
            foreach ($map as $k => $v) {
                $x^= $v;
                $map[$k] = $x;
                $x = ($x == 255) ? 0 : $x + 1; // boundary check
            }
        }
        $data = '';
        foreach ($map as $v) {
            $data.= chr($v);
        }
        return $data;
    }

    public static function decode($data, $iv = 'X', $loop = 4) {
        if (strlen($data) < 2) {
            return $data;
        }
        $data = str_split($data);
        $data = array_reverse($data, true);
        $len = count($data);
        $map = array();
        foreach ($data as $v) {
            $map[] = ord($v);
        }
        while ($loop--) {
            foreach ($map as $k => $v) {
                $x = ($k + 1 >= $len) ? ((!$loop) * (ord($iv) - 1) + (!!$loop) * $map[0]) : $map[$k + 1];
                $x = ($x == 255) ? 0 : $x + 1; // boundary check
                $map[$k]^= $x;
            }
        }
        foreach ($map as $k => $v) {
            $map[$k] = chr($v);
        }
        $data = array_reverse($map);
        $data = join('', $data);
        return $data;
    }

    /**
     * 产生假数据
     * value:    真实的数据
     * seed:     相关数据，如id等
     * floatMin: 假数据对照真数据最小下浮
     * floatMax: 假数据对照真数据最大上浮
     */
    public static function fake($value, $seed, $floatMin = -0.1, $floatMax = 0.1) {
        $base = $floatMax - $floatMin;
        $hash = crc32($seed . $value . $floatMin . $floatMax);
        $float = ($hash % 10000) / 10000 * $base + $floatMin;
        $value+= $value * $float;
        return $value;
    }
}
