<?php
/**
 * Created by PhpStorm.
 * User: ql-win
 * Date: 2017/3/10
 * Time: 13:20
 */
use EasyWeChat\Foundation\Application;
class WechatController extends  IAppBaseController
{
    private $options;
    private $app;
    public function init(){
        $this->options = [
            'debug'  => true,
            'app_id' => 'wxf33755627a5fcbac',
            'secret' => 'd3293b3e73a5b00460f4b95fcd675f4d',
            'token'  => 'iamseeql',
            // 'aes_key' => null, // 可选
            'log' => [
                'level' => 'debug',
                'file'  => '../logs/'.date("Ymd").'/easywechat.log', // XXX: 绝对路径！！！！
            ],
            //...
        ];
        $this->app = new Application($this->options);
    }
    public function InterfaceAction()
    {
        $token = 'iamseeql';
        $params = $this->getParams(array('timestamp','nonce','signature','echostr'));
        $signature = $params['signature'];
        $echostr = $params['echostr'];
        unset($params['signature']);
        unset($params['echostr']);
        $params['token'] = $token;
        sort($params,SORT_STRING);
        $tmpstr = implode($params);
        $tmpstr = sha1($tmpstr);
        if($tmpstr == $signature){
            echo $echostr;
            exit;
        }

    }
    public function indexAction(){

        $mail = new PHPMailer;
//        $this->app->server->setMessageHandler(function ($message) {
//            return new \EasyWeChat\Message\Text(['content' => '您好！overtrue。']);
//
//        });
//        $response = $this->app->server->serve();
//        // 将响应输出
//        $response->send(); // Laravel 里请使用：return $response;

    }

    public function GroupAllSendAction()
    {
        $broadcast = $this->app->broadcast;
        $broadcast->sendText("大家好！欢迎使用 EasyWeChat。");
    }

    public function FreshMenuAction()
    {
        $menu = $this->app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "今日歌曲",
                "key"  => "V1001_TODAY_MUSIC"
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "搜索",
                        "url"  => "http://www.soso.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "视频",
                        "url"  => "http://v.qq.com/"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD"
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
    }






}
