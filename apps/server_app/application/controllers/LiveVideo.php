<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class LiveVideoController extends  OAppBaseController
{
    public function getVideoListAction(){
        $scandir = new \Royal\Util\Scandir();
        $video_list = $scandir->scan('e:/movie');
        echo $video_list;
    }

}