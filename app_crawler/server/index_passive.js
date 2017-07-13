/**
 * Created by ql-qf on 2017/7/11.
 */


/*编码配置项端*/
phantom.outputEncoding = 'utf-8' //解决中文乱码
var $ = require('../node_modules/jquery/dist/jquery.min.js')
var config = {
    waitTime: 15000,
}
var search_url = {
    taobao:'https://s.taobao.com/search?imgfile=&js=1&stats_click=search_radio_all%3A1&initiative_id=staobaoz_20170713&ie=utf8&sort=sale-desc&q=',
    jd:''
}
var userAgent=[
    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X)',
    'Mozilla/4.2 (compatible; MSIE 6.0; Windows NT 5.0)',
    'Mozilla/4.3 (compatible; MSIE 6.1; Windows NT 5.1)',
    'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:52.0) Gecko/20100101',
]

var post_result_uri = 'http://127.0.0.1:7000/common/log'
var i = 1;


var keyList = []
var comment = {}
var search_key = []
var response = {
    key:'',
    type:'',
    comment:[
        {source:'',value:[]}
    ],

}


/*
 * 编码：websocket post client 端
 * */


//
var conn_status = false
var payload = {
    to:'',
    payload_type:'c_point2_c_msg',
    payload_data:{point_key:'spider_debug',msg:'starting send debug info'},
    yaf:{
        module:'index',
        controller:'Wsserver',
        action:'getToidByPointkey',
    }
}
var wSock = {}
initSocket('spider_server','spider_server,spider_debug','spider_server')
function initSocket( unique_auth_code,point_key,receive_key){

    if(!conn_status){
        ws(unique_auth_code,point_key,receive_key);
    }
}
function ws(unique_auth_code,point_key,receive_key){

    console.log('准备连接到服务器=>')

    // this.data.wSock  =  new WebSocket('ws://iamsee.com:9501/?unique_auth_code='+unique_auth_code);
    wSock  =  new WebSocket('ws://127.0.0.1:9501/?unique_auth_code='+unique_auth_code+'&point_key='+point_key+'&receive_key='+receive_key);
    wsOpen();
    wsMessage();
    wsOnclose();
    wsOnerror();

}
function wsOpen(){

    wSock.onopen  =  function( event ) {
        conn_status = true
        console.log('websocket 连接已打开')

    }

}
function wsMessage(){


    wSock.onmessage = function(event){
        var reader = new FileReader();
        reader.readAsText(event.data, 'utf-8');
        reader.onload = function (e) {
            event.datastr = reader.result
            var response_data  =  JSON.parse(event.datastr)
            var status = response_data.status
            var type = response_data.type

            if(type === 'spider_server'){
                console.log('...............'+type+'...............')
                console.log(JSON.stringify(response_data))
                search_key = response_data.data.response.search_key
                response = {
                    key:'',
                    type:'',
                    comment:[
                        {source:'',value:[]}
                    ],

                }
                response.key = search_key

                startHanlde()

            }
            else{
                // console.log('...............'+type+'...............')
                // console.log(JSON.stringify(response_data))
            }

        }


    }
    wSock.onerror  =  function(event){

        console.log('websocket 连接出现错误');
    }



}
function wsOnclose(){
    wSock.onclose  =  function(event){
        conn_status = false
        console.log('websocket 连接已经关闭')
    }
}
function wsOnerror(){
    wSock.onerror  =  function(event){

        console.log('websocket 连接出现错误');
    }
}
function wsSend(payload){
    var  send_blob =String2Blob(JSON.stringify(payload))
    wSock.send(send_blob);
}

/*数据转换工具类*/
function String2Blob(str) {
    var blob = new Blob([str], {
        type: 'text/plain'
    });
    return blob
}
// var loop = setInterval(function () {
//     if(conn_status){
//         clearInterval(loop)
//     }
// })

/*
 * 编码逻辑端
 * */



function startHanlde() {

    var casper = require('casper').create({
        clientScripts: [
            'lib/jquery.min.js',
        ],
        pageSettings: {
            webSecurityEnabled: false,
            loadImages: false,
            loadPlugins: false
        },

        logLevel: 'info',
        verbose: false,
        waitTimeout: 1000000,
        onWaitTimeout: handleWaitTimeout,
        onTimeout: handleTimeout,
        onError: handleError,

    })
    casper.options.pageSettings.proxy = 'http://192.168.2.1:9630';
    var currect_ua_index = parseInt((Math.random()*10)%userAgent.length)
    console.log('currect_ua_index',currect_ua_index)
    // casper.userAgent(userAgent[]);
    casper.start().then(function () {
        this.echo('Starting...')
    })
    casper.then(function () {
        // this.page.settings.proxy = 'http://192.168.191.1:9630';
        console.log('准备根据 search_key:'+search_key +' 爬取销量最高商品的URL')
        var build_search_url = search_url.taobao+search_key
        console.log(build_search_url)
        casper.thenOpen(build_search_url, function () {
            // var page_content = this.getPageContent();
            response.key = search_key
            response.type = search_key
            comment = {}

            links = this.evaluate(getLinks);
            comment.source = 'http:'+links



        })

        function getLinks() {
            var links = document.querySelectorAll('.items div.J_MouserOnverReq[data-index="0"] div.pic a');
            return Array.prototype.map.call(links, function(e) {
                return e.getAttribute('href');
            });
        }
    });
    casper.then(function () {
        console.log('准备根据 search_key:'+search_key + ' => item_url: ')
        console.log(comment.source+' 进行评论爬取')

        casper.thenOpen(comment.source, function () {
            // this.waitForSelector('li a[shortcut-label="查看累计评论"]');
            // this.click('li a[shortcut-label="查看累计评论"]');
           
                // this.click('li a[shortcut-label="查看累计评论"]');
                // console.log('进行[累计评论]点击')
                // out2png(this,this.getPageContent())
                console.log('提取评论request jsonp url')
                comment.request_url = 'http:'+this.getElementAttribute('#reviews','data-listapi')+'&currentPageNum=1&pageSize=50&rateType=1'
                
                this.evaluate(function (url) {
                    console.log('url->'+url)
                    $.ajax({
                      async: false,
                      url: url,      //跨域到http://www.wp.com，另，http://test.com也算跨域
                      type:'GET',                                //jsonp 类型下只能使用GET,不能用POST,这里不写默认为GET
                      dataType:'jsonp',                          //指定为jsonp类型
                      success:function(result){  
                          console.log('jsonp_response_comment'+JSON.stringify(result))
                      },
                      error:function(msg){
                          console.log(msg)
                      }
                    }); 
                },comment.request_url);

           


        })

    })
    casper.on('remote.message', function(remote_console) {

        console.log('remote_console',remote_console)
        if(remote_console.indexOf('jsonp_response_comment')>0){
            console.log('接收到jsonp 评论数据')
            var build_remote_console = remote_console.replace('jsonp_response_comment','')
            if(isJSON(build_remote_console)){
                console.log('获取到评论数据!')
                console.log(build_remote_console)
            }
            else{
                console.log('IP封禁!')
            }
        }
        // if(isJSON(remote_console)){
        //
        // }

    })
    casper.run(function() {
        return true;
    });

}


function isJSON(str) {
    if (typeof str == 'string') {
        try {
            var obj=JSON.parse(str);
            if(str.indexOf('{')>-1){
                return true;
            }else{
                return false;
            }

        } catch(e) {
            console.log(e);
            return false;
        }
    }
    return false;
}

function handleWaitTimeout(data) {
    console.log('handleWaitTimeout', data)
}
function handleTimeout(data) {
    console.log('handleTimeout', data)
}
function handleError(data) {
    console.log('handleError', data)
}
function out2png(obj,info) {
    console.log('准备发送调试信息')
    var imgname = i+".png"
    obj.capture('imgs/'+imgname);
    i++
    payload.payload_data.point_key = 'spider_debug'
    payload.payload_data.msg = {info:info,img:imgname}
    wsSend(payload)
    console.log('发送调试信息完成')
}