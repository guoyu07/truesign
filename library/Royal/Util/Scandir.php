<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:07
 */

namespace Royal\Util;


use Yaf\Exception;

class Scandir
{
    function get_dir_glob($path){

        $tree = array();
        $id = 1;
        throw new \Exception(json_encode(glob('/*')),-199);
        foreach(glob($path.'/*') as $single){

            if(!is_dir($single)){

                if(strstr($single,'mp4'))
                {
                    $file = array();
                    $singlename = basename($single,'.mp4');
                    $file['id']=$id;
                    $file['filename']=$singlename;
                    $file['ticket']=random_int(1,100);
                    $tree[]=$file;
                    $id++;
                }
            }
        }


        return $tree;
    }
    function get_file_name($path){
        if(sizeof(glob($path.'/*')) != 1){
            $sectree = array();
            foreach (glob($path.'/*') as $single){

                $sectree[] = $single;

            }
            return $sectree;
        }else{
            foreach (glob($path.'/*') as $single){
                return $single;

            }

        }

    }
    function scan($path){
        $path = $path?$path:'.';
        $alldata = $this->get_dir_glob($path);

        //$jsalldata = array();
        //foreach ($alldata as $k=>$v) {
        //    $jsalldata[CUtf8_PY::encode($k,'all')]=$v;
        //};

        //var_dump($jsalldata);
        return json_encode($alldata,256);
    }

}