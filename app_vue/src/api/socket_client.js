import { String2Blob } from './lib/helper/javascriptDatatypeConvert'
const SOCKET_CLIENT  =  {
    data : {
        wSock       : null,
        to       : '',
        message        : null,
        payload:null,
        wsserver    : 'ws://iamsee.com:9501',
        response : '',
        this_vue : null,
        conn_status : false,
        status:'暂未连接到服务器',
        check_socket_status:'',
    },
    init : function (unique_auth_code=0){
        this.copyright();
        if(this.data.status === '连接正常'){
            console.log(this.data.status)
        }
        else{
            this.ws(unique_auth_code);
        }

    },

    ws : function(unique_auth_code){
        console.log('准备连接到服务器=>')
        clearInterval(this.data.check_socket_status)
        this.data.wSock  =  new WebSocket('ws://iamsee.com:9501/?unique_auth_code='+unique_auth_code);
        this.wsOpen();
        this.wsMessage();
        this.wsOnclose();
        this.wsOnerror();
        this.loopCheckStatus()
    },
    wsSend : function(){

        // console.log('send=>')
        // console.log(JSON.stringify(this.data.payload))
        var send_blob = String2Blob(JSON.stringify(this.data.payload))
        this.data.wSock.send(send_blob);

    },
    wsClose : function () {

        console.log('尝试关闭')
        this.data.wSock.close();


    },
    wsOpen : function (){
        let that = this
        this.data.wSock.onopen  =  function( event ){
            that.data.status = '连接正常'
            SOCKET_CLIENT.print('wsopen',event);
        }
    },
    wsMessage : function(){
        let that = this
        this.data.wSock.onmessage = function(event){
            var reader = new FileReader();
            reader.readAsText(event.data, 'utf-8');
            reader.onload = function (e) {
                event.datastr = reader.result
                var response_data  =  jQuery.parseJSON(event.datastr);
                let status = response_data.status
                let type = response_data.type
                console.log('...............'+type+'...............')
                that.data.this_vue.$root.eventHub.$emit('socket_response',response_data)
            }


        }



    },
    wsOnclose : function(){
        let that = this

        this.data.wSock.onclose  =  function(event){
            console.log('[c]close=>');
            console.log(event)
            if(event.type === 'close'){
                that.data.wSock = null
            }
            if(that.data.wSock){
                console.log('关闭失败')
            }
            else{
                that.data.status = '连接关闭'
                console.log('关闭成功')
                that.data.this_vue.$root.eventHub.$emit('socket_close',1)




            }
        }
    },
    wsOnerror : function(){
        this.data.wSock.onerror  =  function(event){
            console.log('[c]error=>');
            console.log(event);
        }
    },

    print:function(flag,obj){
        console.log('----'  +  flag  +  ' start-------');
        console.log(obj);
        console.log('----'  +  flag  +  ' end-------');
    },
    copyright:function(){
        if(this.data.status !== '连接正常'){
            this.data.status = 'truesign ico pre connect to socket server ……'
            console.log(this.data.status);
        }
    },
    loopCheckStatus:function () {
        var that = this
        if(check_socket_status){
            clearInterval(check_socket_status)
        }

        var check_socket_status = setInterval(function () {
            that.data.this_vue.$root.eventHub.$emit('check_level',1)
            if(!that.data.wSock){
                // console.log('socket_error->','未连接')
                that.data.this_vue.$root.eventHub.$emit('socket_error','未连接')
            }
            else if(that.data.wSock.readyState != 1){
                // console.log('socket_error->',that.data.wSock.readyState)
                that.data.this_vue.$root.eventHub.$emit('socket_error',that.data.wSock.readyState)
            }
            else{
                // console.log('socket_status->',that.data.wSock.readyState)
                that.data.this_vue.$root.eventHub.$emit('socket_error',that.data.wSock.readyState)
            }
        },3000)
        this.data.check_socket_status = check_socket_status
    }
}
export  default  SOCKET_CLIENT
