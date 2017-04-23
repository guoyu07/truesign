<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class ReceiveMsgController extends  iAppBaseController
{

    public function ReceiveMsgAction(){

        $myfile = fopen("get.txt", "w");
        fwrite($myfile,json_encode($_GET,256));
        fclose($myfile);
        $myfile = fopen("post.txt", "w");
        fwrite($myfile,json_encode($_POST,256));
        fclose($myfile);
        $myfile = fopen("server.txt", "w");
        fwrite($myfile,json_encode($_SERVER,256));
        fclose($myfile);
    }

}
