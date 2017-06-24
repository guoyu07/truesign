<?php
namespace Royal\Aliyun;
use \Aliyun\OSS\OSSClient;
use Eagle\Service\Image;
use Eagle\Service\LoginInfo;

class Aliyun {
    public static function upload($fileName, $contents) {

        $config = \Yaf_Registry::get('config');
        $callConfig = $config->cdn;
        $Bucket = $callConfig['Bucket'];
        $AccessKeyId = $callConfig['AccessKeyId'];
        $AccessKeySecret = $callConfig['AccessKeySecret'];

        $Endpoint = $callConfig['Endpoint'];
        $client = OSSClient::factory(array(
            'AccessKeyId' => $AccessKeyId,
            'AccessKeySecret' => $AccessKeySecret,
            'Endpoint' => $Endpoint,
        ));
        $ret = $client->putObject(array(
            'Bucket' => $Bucket,
            'Key' => $fileName,
            'Content' => $contents,
        ));
//        return json_decode(array(
//            'Bucket' => $Bucket,
//            'Key' => $fileName,
//            'Content' => $contents,
//        ));

        return $ret;
    }

    // 获取签名
    public static function AppSign($fileName,$param,$back_url,$is_video=false,$cdn_name='') {
        $config = \Yaf_Registry::get('config');
        $callConfig = $config->cdn;
        $AccessKeyId = $callConfig['AccessKeyId'];
        $AccessKeySecret = $callConfig['AccessKeySecret'];

        $id= $AccessKeyId;
        $key= $AccessKeySecret;

        $host = $callConfig['BucketHost'];
        $param['file_name']=$fileName;

        if($is_video) {
            $host = $callConfig['BucketVideoHost'];
            $param['file_name']=$cdn_name;

        }

        $callbackUrl = $callConfig['CallBackUrl'].$back_url;

        $query = '';
        foreach ($param as $k => $v) {
            $query .= '&'.$k.'='.$v;
        }

        $callback_param = array(
            'callbackUrl'=>$callbackUrl,
            'callbackBody'=>'token='.\Yaf_Registry::get('token').$query.'&img_width=${imageInfo.width}',
        );
        $callback_string = json_encode($callback_param);

        $base64_callback_body = base64_encode($callback_string);
        $now = time();
        $expire = 6000000000000000000*30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = self::gmt_iso8601($end);


        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$fileName);
        $conditions[] = $start;


        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['expire'] = $end;
        //这个参数是设置用户上传指定的前缀
//        $response['name'] = '10.jpg';
        $response['key'] = $fileName;
        $response['policy'] = $base64_policy;
        $response['OSSAccessKeyId'] = $id;
        $response['success_action_status'] = '200';
        $response['callback'] = $base64_callback_body;
        $response['signature'] = $signature;
        return array(
            'uri'=>$host,
            'param'=>$response

        );
//        echo json_encode($response);

    }

    public static function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new \DateTime($dtStr);
        $expiration = $mydatetime->format(\DateTime::ISO8601);
        $s = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $s);
        return $expiration."Z";
    }

    // 图片压缩加水印
    public  function Water($param)
    {
        $url = $param['url'];
        $img_width = $param['img_width'];
        if($img_width>1000) {
            $url.='@1000w';
        }
        else {
            $url.='@'.$img_width.'w';
        }

        $config = \Yaf_Registry::get('config');
        $callConfig = $config->cdn;
        $ImgInterUrl = $callConfig['ImgInterUrl'];

        $url = $ImgInterUrl.'/'.$url;

        /*
         * 本地测试
         */
//        $testurl = "http://resource-hz.img-cn-hangzhou.aliyuncs.com";
//        $url = $testurl.'/'.$url;

        // 改公司是否需要加水印
        $loginInfo = new LoginInfo();
        $company_id= $loginInfo->getCompanyId();
        $server_code = $loginInfo->getServerCode();

        $image = new Image();
        $s = $image->WaterCompany($company_id);
        if($s) {
            switch ($s)
            {
                case 1:  //上左
                    $s='&p=1&x=10&y=10';
                    break;
                case 2:  //上中
                    $s='&p=2&y=10';
                    break;
                case 3:  //上右
                    $s='&p=3&x=10&y=10';
                    break;
                case 4:  //中左
                    $s='&x=10&voffset=0';
                    break;
                case 5:  //中
                    $s='&p=5&voffset=0';
                    break;
                case 6:  //中右
                    $s='&p=6&x=10&voffset=0';
                    break;
                case 7:  //下左
                    $s='&p=7&x=10&y=10';
                    break;
                case 8:  //下中
                    $s='&p=8&y=10';
                    break;
                case 9:  //下右
                    $s='&p=9&x=10&y=10';
                    break;
                default:  //中
                    $s='&p=5&voffset=0';
            }
            $url.='|watermark=1&&object='.base64_encode('water/'.$company_id.'.png'.'@55P').'&t=55'.$s;
        }
        else if($img_width<1000) {
            return $url;
        }
        $filecontent = file_get_contents($url);
        $this->upload(''.$param['url'],$filecontent);
        return $url;
    }

    //新图片压缩加水印
    public function WaterNew($param){
        $url = $param['url'];
        $img_width = $param['img_width'];
        $img_height = $param['img_height'];
        if($img_width>1000 || $img_height>1000) {
//            $url.='@1000w';
            if($img_width>$img_height){
                $url .= '?x-oss-process=image/resize,m_lfit,w_'.'1000';
            }
            else{
                $url .= '?x-oss-process=image/resize,m_lfit,h_'.'1000';

            }
        }
        else {
//            $url.='@'.$img_width.'w';
            if($img_width>$img_height){
                $url .= '?x-oss-process=image/resize,m_lfit,w_'.$img_width;
            }
            else{
                $url .= '?x-oss-process=image/resize,m_lfit,h_'.'1000';

            }
        }

        $config = \Yaf_Registry::get('config');
        $callConfig = $config->cdn;
        $ImgInterUrl = $callConfig['ImgInterUrl'];

        $url = $ImgInterUrl.'/'.$url;

        /*
         * 本地测试
         */
//        $testurl = "http://resource-hz.img-cn-hangzhou.aliyuncs.com";
//        $url = $testurl.'/'.$url;

        // 改公司是否需要加水印
        $loginInfo = new LoginInfo();
        $company_id= $loginInfo->getCompanyId();
        $server_code = $loginInfo->getServerCode();

        $image = new Image();
        $s = $image->WaterCompany($company_id);
        if($s) {
            switch ($s)
            {
                case 1:  //上左
//                    $s='&p=1&x=10&y=10';
                    $s = ',g_nw';
                    break;
                case 2:  //上中
//                    $s='&p=2&y=10';
                    $s = ',g_north';
                    break;
                case 3:  //上右
//                    $s='&p=3&x=10&y=10';
                    $s = ',g_ne';
                    break;
                case 4:  //中左
//                    $s='&x=10&voffset=0';
                    $s = ',g_west';
                    break;
                case 5:  //中
//                    $s='&p=5&voffset=0';
                    $s = ',g_center';
                    break;
                case 6:  //中右
//                    $s='&p=6&x=10&voffset=0';
                    $s = ',g_east';
                    break;
                case 7:  //下左
//                    $s='&p=7&x=10&y=10';
                    $s = ',g_sw';
                    break;
                case 8:  //下中
//                    $s='&p=8&y=10';
                    $s = ',g_south';
                    break;
                case 9:  //下右
//                    $s='&p=9&x=10&y=10';
                    $s = ',g_se';
                    break;
                default:  //中
//                    $s='&p=5&voffset=0';
                    $s = ',g_center';
            }

//            $url.='|watermark=1&&object='.base64_encode('water/'.$company_id.'.png'.'@55P').'&t=55'.$s;
            /*
             * 新版图片水印操作
             */
            $url.='/watermark,image_'.base64_encode('water/'.$company_id.'.png'.'?x-oss-process=image/resize,P_75 ').',t_75'.$s;

        }
        else {
            return $url;
        }

        $filecontent = file_get_contents($url);
        try{
            $this->upload(''.$param['url'],$filecontent);

        }catch (Exception $e){
            throw new \Exception(json_encode($e),-198);
        }

        return $url;
    }


}
