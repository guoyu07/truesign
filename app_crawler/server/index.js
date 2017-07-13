/**
 * Created by ql-qf on 2017/7/11.
 */


/*编码配置项端*/
phantom.outputEncoding = 'utf-8' //解决中文乱码
var $ = require('../node_modules/jquery/dist/jquery.min.js')
var config = {
    waitTime: 15000,
}
var uri = 'https://item.taobao.com/item.htm?spm=a21ig.146272.757693.1.1a44f7bPVSpmH&id=549067387968'
var post_result_uri = 'http://127.0.0.1:7000/common/log'
var i = 1;


var keyList = []
var source_and_msg = {
    key:'',
    type:'',
    comment_source:'',
    comment:[],
}
/*
 * 编码：websocket post client 端
 * */


//
var conn_status = false
var payload = {
    to:'',
    payload_type:'c_point2_c_msg',
    payload_data:{point_key:'debug_for_spider',msg:'starting send debug info'},
    yaf:{
        module:'index',
        controller:'Wsserver',
        action:'getToidByPointkey',
    }
}
var wSock = {}
initSocket(0,'debug_for_spider')
function initSocket( unique_auth_code,socket_type){

    if(!conn_status){
        ws(unique_auth_code,socket_type);
    }
}
function ws(unique_auth_code,socket_type){

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

            if(type !== 'ping'){
            console.log('...............'+type+'...............')
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
var loop = setInterval(function () {
    if(conn_status){
        clearInterval(loop)
        wsSend(payload)
        casper.run(repeat)
    }
})

/*
 * 编码业务逻辑端
 * */
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
    verbose: true,
    waitTimeout: 1000000,
    onWaitTimeout: handleWaitTimeout,
    onTimeout: handleTimeout,
    onError: handleError,

})
// casper.options.clientScripts.push("../node_modules/babel-polyfill/dist/polyfill.js")
function repeat() {
    casper.thenOpen(uri).then(function () {

        this.echo('do something....')
        var page_content = this.getPageContent()
        out2png(this,page_content)
        this.click('li a[shortcut-label="查看累计评论"]');
        casper.wait(1000, function() {
            this.click('li a[shortcut-label="查看累计评论"]');
            if (this.exists('#reviews-t-val1')){
                this.click('#reviews-t-val1');
            }

            var page_content = this.getPageContent()
            // var tmp_content = $('div.tb-revbd ul').html
            // var tmp_content = this.getHTML('.tb-revbd',true)
            out2png(this,page_content)
        })
        casper.wait(1000, function() {
            this.echo("I've waited for a second.");

            this.click('li a[shortcut-label="查看累计评论"]');
            // if (this.exists('#reviews-t-val1')){
            //     this.click('#reviews-t-val1');
            // }
            var page_content = this.getPageContent()
            out2png(this,page_content)
            // casper.wait(10000, function() {
            //     var page_content = this.getPageContent()
            //     out2png(this,page_content)
            // })

        })




    })
    casper.wait(config.waitTime, function () {
        this.echo('wait time over!')
    })
    casper.run(repeat)
}

casper.start().then(function () {
    this.echo('Starting...')
})


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
    var imgname = i+".png"
    obj.capture('imgs/'+imgname);
    i++
    payload.payload_data.msg = {info:info,img:imgname}
    wsSend(payload)
}