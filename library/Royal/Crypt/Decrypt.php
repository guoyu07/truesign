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
    static function  encryption($info,$id,$account_type, $type = 0,$time_salt = 1*24*60*60) {
        $saltkey = '@IAMSEE·TRUESIGN';

        if (!$type) { /*加密操作*/
            $salt = md5($id);
            $key = sha1($saltkey.$salt);
            $info = $account_type.','.$info.','.(time()+$time_salt);
            $encryption_info = $info ^ $key;
            $encode_key =  base64_encode($encryption_info);
            $flag_length_id = self::numMapKey(strlen($id));
            $encode_key =  substr_replace($encode_key,$flag_length_id,3,0); /*id长度标志附着*/
            $encode_key =  substr_replace($encode_key,$id,6,0); /*id附着*/
            return $encode_key;
        }
        else{ /*解密操作*/
            /*解析id*/
            $flag_length_id = substr($info,3,1);

            $id_lenght = self::numMapKey($flag_length_id,true)+1;/*拿到id长度*/

            $id = substr($info,6,$id_lenght);/*获取id*/
            /*解析加密字符码,去除附着的 id长度标志 和id*/
            $info = substr_replace($info,'',6,$id_lenght);/*去除id*/
            $info = substr_replace($info,'',3,1);/*去除id长度标志*/
            $salt = md5($id);
            $key = sha1($saltkey.$salt);

            $info = base64_decode($info);
            $info = $info ^ $key;
            [$account_type,$name,$limit_time] = explode(',',$info);
            $decode_info['id'] = (int)$id;
            $decode_info['account_type'] = $account_type;
            $decode_info['name'] = $name;
            $decode_info['limit_time'] = (int)$limit_time;
            /*进行第一层加密码解密数据校验*/
            if(strlen($decode_info['limit_time']) !== 10 || !$decode_info['id']>0 || empty(json_encode($name)) || !in_array($account_type,array('master','business','worker','socket'))){
                return false;
            }
            else{
                return $decode_info;

            }
        }
    }

    static function numMapKey($value,$type=false){
        $baseSequence = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $baseNumKey = str_split($baseSequence);
        $baseKeyNum = array_flip($baseNumKey);
        if(empty($value)){
            return false;
        }
        if($type > 62){
            return false;
        }
        if(!$type){ /*由num获取key*/

            return $baseNumKey[$value-1];
        }
        else{ /*由key获取num*/
            if(!in_array($value,$baseNumKey)){
                return false;
            }
            else{
                return $baseKeyNum[$value];
            }
        }

    }
}