
const SOCKET_CLIENT  =  {
    data : {
        wSock       : null,
        to       : '',
        message        : null,
        payload:null,
        wsserver    : 'ws://iamsee.com:9501',
        response : '',
        this_vue : null
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
            var d  =  jQuery.parseJSON(event.data);
            console.log('[c]message=>')
            // console.log(event)
            console.log(d)
            d = JSON.parse(d['data'])
            console.log(d)
            let user_count = d['user_list']['count']
            let user_list = d['user_list']['data']
            let show_user_list = user_count + '\r\n'
            $.each(user_list,function (k,v) {
                show_user_list += 'id=>' + v['id'] + ' nickname=>' + v['nickname'] + '\r\n'
            })


            let show_me = d['relation']['me']
            let show_me_id = show_me['id']
            let show_me_nickname = show_me['nickname']
            if(show_me_id !== 'unknow' && show_me_nickname){
                let me = {
                    'me_id':show_me_id,
                    'me_nickname':show_me_nickname,
                    'me_status':'online'
                }
                that.data.this_vue.me = me

            }
            that.data.this_vue.user_list = show_user_list
            that.data.this_vue.response = JSON.stringify(d['response'])
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
