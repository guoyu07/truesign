
const SOCKET_CLIENT  =  {
    data : {
        wSock       : null,
        to       : '',
        message        : null,
        payload:null,
        wsserver    : 'ws://192.168.1.5:9501',
        response : '',
        this_vue : null,
        conn_status : false
    },
    init : function (){
        this.copyright();
        SOCKET_CLIENT.data.storage  =  window.localStorage;

        if(this.data.wSock){
            console.log('已经是连接状态')
        }
        else{
            this.ws();
        }

    },

    ws : function(){

        this.data.wSock  =  new WebSocket(this.data.wsserver);
        this.wsOpen();
        this.wsMessage();
        this.wsOnclose();
        this.wsOnerror();
    },
    wsSend : function(){

        console.log('send=>')
        console.log(JSON.stringify(this.data.payload))
        let send_reponse = this.data.wSock.send(JSON.stringify(this.data.payload));

    },
    wsClose : function () {

        console.log('尝试关闭')
        this.data.wSock.close();


    },
    wsOpen : function (){
        let that = this
        this.data.wSock.onopen  =  function( event ){
            that.data.this_vue.check_status()
            that.data.this_vue.show_process = true
            SOCKET_CLIENT.print('wsopen',event);
            console.log('[c]open=>')
            console.log(event)
        }
    },
    wsMessage : function(){
        let that = this
        this.data.wSock.onmessage = function(event){
            var response_data  =  jQuery.parseJSON(event.data);
            console.log('[c]message=>')
            console.log(response_data)
            let status = response_data.status
            let type = response_data.type
            console.log(status)
            console.log(type)
            if(status === 200 && type ==='self_init'){
                that.conn_status = true
                that.data.this_vue.conn_status = true
                that.data.this_vue.doinit()
                that.data.this_vue.conn_info = '保持连接'

            }
            else if(status === 200 && type ==='message'){
                console.log('response_data=>')
                console.log(response_data)
                console.log(response_data.data.response.response_data)

                that.data.this_vue.socket_response = response_data.data.response.response_data
                that.data.this_vue.doinitapps()

            }



            // $('#response').val(JSON.stringify(d['response']))
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
                that.data.this_vue.stop_check_status()
                that.data.this_vue.process = 100
                // that.data.this_vue.show_process = false
                that.data.this_vue.me = null
                console.log('关闭成功')
                that.data.this_vue.conn_status = false
                that.data.this_vue.conn_info = '失去连接'

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
        console.log('truesign ico pre connect to socket server ……');
    },
}
export  default  SOCKET_CLIENT
