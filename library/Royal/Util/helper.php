<?php
namespace Royal\Util;
class helper{
    static function  getBody($data, string $info='', int $code=200){
        return (object)([
            'code'=>(int)$code,
            'info'=>(string)$info,
            'data'=>$data
        ]);
    }

    static function exeTime($stime){
        $time = microtime(true) - $stime;
        if ($time > 0) {
            return number_format($time, 4);
        }
        return 0;
    }
    static function run_mem($smem) {
        $smem = array_sum(explode(' ', $smem));
        $emem = array_sum(explode(' ', memory_get_usage()));
        return number_format(($emem - $smem) / 1024) . 'kb';
    }

    static function sfind($str, $findme, $tag = ','){
        return !(strpos($tag . $str . $tag, $tag . $findme . $tag) === false);
    }
    static function get_sysinfo() {
        $sys_info['os']             = PHP_OS;
        $sys_info['zlib']           = function_exists('gzclose');//zlib
        $sys_info['safe_mode']      = (boolean) ini_get('safe_mode');//safe_mode = Off
        $sys_info['safe_mode_gid']  = (boolean) ini_get('safe_mode_gid');//safe_mode_gid = Off
        $sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : L('no_setting');
        $sys_info['socket']         = function_exists('fsockopen') ;
        $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
        $sys_info['phpv']           = phpversion();
        $sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
        return $sys_info;
    }
    static function sysinfo($isDie = false){
        $info = array(
            'OS' => PHP_OS,
            'SERVER_SOFTWARE' => $_SERVER[SERVER_SOFTWARE],
//            'MYSQL' => mysqli_get_server_info(),
            'ZEND' => zend_version(),
            'PhpType' => php_sapi_name(),
            'PhpVersion' => PHP_VERSION,
            'IP' => $_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'],
            'LANG' => getenv("HTTP_ACCEPT_LANGUAGE"),
            'uploadSize' => ini_get('upload_max_filesize'),
            'execTime' => ini_get('max_execution_time') . '秒',
//            'ServerTime' => date("Y-n-j H:i:s"),
//            'LocalTime' => gmdate("Y-n-j H:i:s", time() + 8 * 3600),
//            'LastSpace' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
//            'register_globals' => get_cfg_var("register_globals") == "1" ? "ON" : "OFF",
//            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
//            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? 'YES' : 'NO',
        );
        if ($isDie) {
            die((print_r($info, true)));
        }
        return $info;
    }
    static function getClientIP()
    {
        global $ip;
        $ip = [];
        $ip['REMOTE_ADDR']=$_SERVER['REMOTE_ADDR'];
        $ip['HTTP_VIA']=$_SERVER['HTTP_VIA'];
        $ip['HTTP_X_FORWARDED_FOR']=$_SERVER['HTTP_X_FORWARDED_FOR'];
        return $ip;
//        if(!empty($_SERVER['HTTP_VIA']))    //使用了代理
//        {
//            if(!isset($_SERVER['HTTP_X_FORWARDED_FOR']))
//            {
//                //Anonymous Proxies    普通匿名代理服务器
//                //代理IP地址为 $_SERVER['REMOTE_ADDR']
//            }
//            else
//            {
//                //Transparent Proxies 透明代理服务器
//                //代理IP地址为 $_SERVER['REMOTE_ADDR']
//                //真实ip地址为 $_SERVER['HTTP_X_FORWARDED_FOR']
//            }
//        }
//        else    //没有代理或者是高匿名代理
//        {
//            //真实ip地址为 $_SERVER['REMOTE_ADDR']
//        }
//        if (getenv("HTTP_CLIENT_IP"))
//            $ip = getenv("HTTP_CLIENT_IP");
//        else if(getenv("HTTP_X_FORWARDED_FOR"))
//            $ip = getenv("HTTP_X_FORWARDED_FOR");
//        else if(getenv("REMOTE_ADDR"))
//            $ip = getenv("REMOTE_ADDR");
//        else $ip = "Unknow";
//        return $ip;
    }
}