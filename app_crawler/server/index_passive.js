/**
 * Created by ql-qf on 2017/7/11.
 */


/*编码配置项端*/
phantom.outputEncoding = 'utf-8' //解决中文乱码
var $ = require('../node_modules/jquery/dist/jquery.min.js')
var x = require('casper').selectXPath;
var utils = require('utils');
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
// initSocket('spider_server','spider_server,spider_debug','spider_server')
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
*   编码 http webserver 服务端
* */

var ip_server = '127.0.0.1:6600';

//includes web server modules
var server = require('webserver').create();

//start web server
var service = server.listen(ip_server, function(request, response) {

})
console.log('Server running at http://' + ip_server+'/');

/*
 * 编码逻辑端
 * */



function startHanlde(type) {

    var casper = require('casper').create({
        clientScripts: [
            'lib/jquery.min.js',
        ],
        pageSettings: {
            webSecurityEnabled: false,
            loadImages: false,
            loadPlugins: false
        },

        logLevel: 'debug',
        verbose: false,
        waitTimeout: 1000000,
        // onWaitTimeout: handleWaitTimeout,
        // onTimeout: handleTimeout,
        // onError: handleError,

    })
    // casper.options.pageSettings.proxy = 'http://192.168.2.1:9630';
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

            links = this.evaluate(getLinks_BySearch);
            comment.source = 'http:'+links



        })

       
    });
    casper.then(function () {

        if(comment.source.indexOf('taobao')>=0)
        {
            console.log('获取到 【淘宝】 商品连接 '+comment.source)

            casper.thenOpen(comment.source, function () {

                console.log('准备提取评论request jsonp url')
                comment.request_url = 'http:'+this.getElementAttribute('#reviews','data-listapi')+'&currentPageNum=1&pageSize=50&rateType=1'
                console.log(comment.request_url)
                this.evaluate(function (url) {
                    console.log('url->'+url)
                    $.ajax({
                        async: false,
                        url: url,      //跨域到http://www.wp.com，另，http://test.com也算跨域
                        type:'GET',                                //jsonp 类型下只能使用GET,不能用POST,这里不写默认为GET
                        dataType:'jsonp',                          //指定为jsonp类型
                        success:function(result){
                            console.log('work!')
                            console.log('jsonp_response_comment'+JSON.stringify(result))
                        },
                        error:function(msg){
                            console.log(msg)
                        }
                    });
                },comment.request_url);




            })
        }
        else if(comment.source.indexOf('tmall')>=0){
            console.log('获取到 【天猫】 商品连接 '+comment.source)
            if(false){
                console.log('修改天猫商品链接为手机端天猫商品连接')
                comment.source = comment.source.replace(/detail\./,'detail.m.')
                console.log(comment.source)
                casper.thenOpen(comment.source, function () {

                    console.log('准备提取评论request jsonp url')
                    casper.waitForSelector('header a:nth-child(3)', function() {
                        console.log('评论链接出现，准备点击')
                        var click_value = this.getHTML('header a:nth-child(3)')
                        this.click('header a:nth-child(3)');

                        this.click('div.toshop');
                        // this.mouseEvent('click', 'div.toshop', "20%", "50%");
                        var page_content = this.getPageContent();
                        out2png(this,page_content)

                    });

                })
            }
            else{
                console.log('进行tmall电脑端爬取')
                console.log(comment.source)
                casper.thenOpen(comment.source, function () {
                    console.log('准备提取评论request jsonp url')
                    var links = document.querySelectorAll('head script');
                    var currect_link = Array.prototype.map.call(links, function(e) {
                        if (typeof e.getAttribute('src') === 'string') {
                            return e.getAttribute('src')
                        }
                    })
                    out2png(this,this.getPageContent())
                        // casper.waitForSelectorTextChange('head', function() {
                        //     this.echo('头部内容改变了')
                        //     if(this.getHTML('head').indexOf('listTryReport.htm')>=0){
                        //         console.log('拿到最终head script')
                        //         out2png(this,'success   '+this.getHTML('head'))
                        //     }
                        //     else{
                        //         if (this.exists('#J_TabBar li')){
                        //             this.click('#J_ItemRates')
                        //             this.click('#J_TabBar li:nth-child(2)')
                        //             this.wait(4000, function() {
                        //                 out2png(this,'error   '+this.getHTML('head'))
                        //             });
                        //
                        //             getheader()
                        //         }
                        //         else{
                        //             this.wait(4000, function() {
                        //                 out2png(this,'error   '+this.getHTML('head'))
                        //             });
                        //             getheader()
                        //         }
                        //
                        //
                        //     }
                        //
                        // })
                    this.click('#J_ItemRates')
                    casper.waitFor(function check() {
                        return this.evaluate(function() {
                            console.log('debug---------------------------')

                            var links = document.querySelectorAll('head script');
                            var currect_link = Array.prototype.map.call(links, function(e) {
                                if (typeof e.getAttribute('src') === 'string') {
                                    return e.getAttribute('src').indexOf('adapter.js')
                                }
                            })
                            console.log(currect_link)
                            for (var item in currect_link){
                                if(currect_link[item] >= 0){
                                    return true
                                }
                            }
                            // console.log($('.rate-grid')[0].innerHTML)

                        });
                    }, function then() {
                        this.echo('第一次加载完成，等待点击进入第二次加载')
                        if (this.exists('#J_TabBar li')) {
                            console.log('存在评论点击链接，准备点击')
                            this.click('#J_ItemRates')
                            this.click('#J_TabBar li:nth-child(2)')
                            out2png(this,this.getPageContent())
                            casper.waitFor(function check() {
                                return this.evaluate(function() {
                                    console.log('debug 第二次加载中---------------------------')

                                    var links = document.querySelectorAll('head script');
                                    var currect_link = Array.prototype.map.call(links, function(e) {
                                        if (typeof e.getAttribute('src') === 'string') {
                                            return e.getAttribute('src').indexOf('&currentPage=1&append=0&content=1&tagId=&posi=&picture=&ua')
                                        }
                                    })
                                    console.log(currect_link)
                                    for (var item in currect_link){
                                        if(currect_link[item] >= 0){
                                            return true
                                        }
                                    }
                                    // console.log($('.rate-grid')[0].innerHTML)

                                });
                            },function then() {
                                console.log('评论数据加载完成')
                                var currect_links = this.evaluate(getlinksBycurrectDom)
                                // console.log(currect_links)
                                // console.log(typeof currect_links)
                                for (var item in currect_links){

                                    if(currect_links[item].indexOf('&currentPage=1&append=0&content=1&tagId=&posi=&picture=&ua') >= 0){
                                        var jsonp_url = currect_links[item]
                                        console.log('提取到jsonp 评论url => ')
                                        // var jsonp_fun = jsonp_url.match(/callback=\S*/)[0].replace(/callback=/,'')
                                        var jsonp_fun = jsonp_url.match(/callback=\S*/)[0]
                                        jsonp_url = jsonp_url.replace(jsonp_fun,'jsonp_success')
                                        console.log(jsonp_url)

                                        this.evaluate(function (url) {
                                            console.log('url->'+url)
                                            $.ajax({
                                                async: false,
                                                url: url,      //跨域到http://www.wp.com，另，http://test.com也算跨域
                                                type:'GET',                                //jsonp 类型下只能使用GET,不能用POST,这里不写默认为GET
                                                dataType:'jsonp',
                                                // jsonpCallback:jsonp_fun,
                                                success:function(result){
                                                    console.log('work!')
                                                    console.log('jsonp_response_comment'+JSON.stringify(result))

                                                },
                                                error:function(msg){
                                                    console.log('error')
                                                    console.log(JSON.stringify(msg))
                                                },

                                            });
                                            function jsonp_success(data) {
                                                console.log('jsonp_success',data)
                                            }
                                        },jsonp_url);
                                        console.log('done')
                                    }
                                }
                            })
                        }
                    });

                    // casper.waitForSelectorTextChange('.rate-grid', function() {
                    //     this.echo('评论加载出来了')
                    //     this.click('#J_ItemRates div span.tm-label')
                    //
                    //     // this.echo(this.getHTML('.rate-grid'))
                    //     out2png(this,this.getHTML('head'))
                    // })
                    // casper.waitForSelectorTextChange('#J_TabBar', function() {
                    //     this.echo('The text on .selector has been changed.');
                    //     casper.waitForSelectorTextChange('.rate-grid', function() {
                    //         this.echo('评论加载出来了')
                    //         this.click('#J_ItemRates div span.tm-label')
                    //
                    //         // this.echo(this.getHTML('.rate-grid'))
                    //         out2png(this,this.getHTML('head'))
                    //     })
                    // });
                    // casper.wait(100, function() {
                    //     out2png(this,100)
                    //     casper.wait(1000, function() {
                    //         out2png(this,1000)
                    //         casper.wait(2000, function() {
                    //             out2png(this,2000)
                    //
                    //         })
                    //     })
                    //
                    // })

                })
            }

        }


    })
    casper.on('remote.message', function(remote_console) {
        console.log('remote->',remote_console)

        if(remote_console.indexOf('jsonp_response_comment')>=0){
            console.log('接收到jsonp 评论数据')
            var build_remote_console = remote_console.replace(/jsonp_response_comment/,'')
            console.log('------------------------')
            if(isJSON(build_remote_console)){
                console.log('获取到评论数据!')
                out2png(this,build_remote_console)
            }
            else{
                console.log('IP封禁!')
            }
        }
        if(remote_console.indexOf('debug')>=0){
            // console.log('remote->debug->',remote_console)
        }
    })
    casper.on('error',function (err) {
        console.log('err',err)
    })
    casper.on('timeout',function (out) {
        console.log('timeout',out)
    })
    casper.run(function() {
        return true;
    });
    function getLinks_BySearch() {
        var links = document.querySelectorAll('.items div.J_MouserOnverReq[data-index="0"] div.pic a');
        return Array.prototype.map.call(links, function(e) {
            return e.getAttribute('href');
        });

    }
    function getlinksBycurrectDom(selector,attr) {
        var links = document.querySelectorAll('head script');
        return Array.prototype.map.call(links, function(e) {
            return e.getAttribute('src');
        });
    }
    

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