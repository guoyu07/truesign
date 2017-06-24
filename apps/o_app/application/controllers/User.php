<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
class userController extends  OAppBaseController
{
    public function saveOrUpdateUserinfoAndDanmuAction()
    {
        $params = $this->getParams(array('nickname','source'),array('content'));
        $doDao_User = new \Royal\Data\DAO(new \Truesign\Adapter\User\userInfoAdapter());
        $search_param = [];
        $search_param['username']=$params['nickname'];
        $search_param['source']=$params['source'];
        $pre_db_user_response = $doDao_User->readSpecified($search_param,array());
        if($pre_db_user_response['statistic']['count'] != 1){
            $pre_user_param = $search_param;
            $pre_user_param['coins']=5000;

            $db_user_response = $doDao_User->insertOrupdate($pre_user_param,$search_param);

//        $doDao_Danmu = new \Royal\Data\DAO(new \Truesign\Adapter\Volume\danmuLogAdapter());
//        $pre_danmu_param = $pre_user_param;
//        $pre_danmu_param['danmu']=$params['content'];
//        $db_danmu_response = $doDao_Danmu->create($pre_danmu_param);

            $db_response['db_user_response']=$db_user_response;
//        $db_response['db_danmu_response']=$db_danmu_response;

            $this->setResponseBody($db_response);
        }
        else{
            $this->setResponseBody(0);
        }


    }

    public function demandLiveVideoAction()
    {
        $params = $this->getParams(array('unique_auth_code','nickname','match_movie','match_ticket','source'),array());
        $do_userDao = new \Royal\Data\DAO(new \Truesign\Adapter\User\userInfoAdapter());
        $db_user_response = $do_userDao->readSpecified(array('username'=>$params['nickname'],'source'=>$params['source']),array('coins'));
        $db_livevideoDao = new \Royal\Data\DAO(new \Truesign\Adapter\Apps\appLiveVideoAdapter());
        if($db_user_response['statistic']['count'] == 1){
            if((int)$db_user_response['data'][0]['coins'] >= (int)$params['match_ticket']){
                $searchParam['videoname'] = array('operation'=>'prefix','value'=>$params['match_movie']);
                $db_livevideo_response = $db_livevideoDao->readSpecified($searchParam,array());
                if($db_livevideo_response['statistic']['count']==1){
                    $db_reponse = [];
                    $drop_coins =  (int)$db_user_response['data'][0]['coins']-(int)$params['match_ticket'];
                    $update_param = [];
                    $update_param['coins'] = $drop_coins;
                    $db_updateuser_response = $do_userDao->updateByQuery(array('coins'=>$drop_coins),array('username'=>$params['nickname']));
                    if($db_updateuser_response){
                        $db_reponse = [];
                        $db_reponse['status']=1;
                        $db_reponse['note']='硬币扣除成功';
                        $danmu = [];
                        $danmu['nickname'] = $params['nickname'];
                        $danmu['match_movie'] = $params['match_movie'];
                        $danmu['match_ticket'] = $params['match_ticket'];
                        $db_reponse['danmu'] = $danmu;
                    }
                    else{
                        $db_reponse['status']=0;
                        $db_reponse['note']='硬币扣除失败';
                    }
                }
                else{
                    $db_reponse['status']=0;
                    $db_reponse['note']='视频查询数目不唯一';
                }


            }
            else{
                $db_reponse['status'] = 0;
                $db_reponse['note'] = '硬币不足';

            }
        }
        else{
            $db_reponse['status']=0;
            $db_reponse['note']='无法确定唯一用户';
        }

        $this->setResponseBody($db_reponse);

    }

}