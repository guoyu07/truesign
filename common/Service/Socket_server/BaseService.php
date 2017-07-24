<?php
/**
 * Created by PhpStorm.
 * User: liuwei
 * Date: 16/6/12
 * Time: 下午2:13
 */

namespace Truesign\Service\Socket_server;




use Royal\Crypt\SHA256;
use Truesign\Service\AppBaseService;

class BaseService extends AppBaseService
{





    public function __construct()
    {

    }

    public function outData($title, $widget, $gap = '')
    {

    }


    //阿里云图片缩放（2016-11-28 最新API）
    /*
     * 这里按宽度标准缩放
     */

    public static function zoompic($size){

        $zoom_param = '?x-oss-process=image/resize,m_lfit,w_'.$size.'&';
        return $zoom_param;
    }
}