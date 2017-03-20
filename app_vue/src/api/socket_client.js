
const SOCKET_CLIENT  =  {
    data : {
        wSock       : null,
        to       : '',
        message        : null,
        payload:null,
        wsserver    : 'ws://192.168.1.5:9501',
        response : '',
        this_vue : null,
        conn_status : false,
        status:'暂未连接到服务器'
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
        this.data.wSock  =  new WebSocket('ws://192.168.1.5:9501/?unique_auth_code='+unique_auth_code);
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
            that.data.status = '连接正常'
            that.data.this_vue.check_status()
            SOCKET_CLIENT.print('wsopen',event);
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

                if(response_data.data.response.response_data.data.init_status){
                    that.data.this_vue.unique_auth_code = response_data.data.response.response_data.data.unique_auth_code
                    that.data.this_vue.doStorageAuthCode()
                }

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
                that.data.status = '连接关闭'
                that.data.this_vue.stop_check_status()
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
        if(this.data.status !== '连接正常'){
            this.data.status = 'truesign ico pre connect to socket server ……'
            console.log(this.data.status);
        }
    },
}
export  default  SOCKET_CLIENT
