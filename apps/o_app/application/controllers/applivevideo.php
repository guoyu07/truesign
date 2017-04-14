<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class applivevideoController extends  oAppBaseController
{
    public function getVideoAction(){
//        $scandir = new \Royal\Util\Scandir();
//        $video_list = $scandir->scan('e:/movie');
//
//        $this->setResponseBody($video_list);
        $curl_uri = 'http://192.168.1.6:5000/';
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $curl_uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        $data_response['uri']=$curl_uri;
//        $data_response['data']=$output;
        $arr_output = json_decode($output);
        foreach ($arr_output as $k=>$v){
            $pre_params = [];
            $pre_params['videoname'] = $k;
            $pre_params['videouri'] = $curl_uri.$v;
            $doDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appLiveVideoAdapter());
            $doDao->insertOrupdate($pre_params,array('videoname'=>$k));
        }
        $db_response = $doDao->read(array(),array(),array('document_id'=>'desc'));
        $this->setResponseBody($db_response);

    }

}