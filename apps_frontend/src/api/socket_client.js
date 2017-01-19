
const chat  =  {
    data : {
        wSock       : null,
        login		: false,
        storage     : null,
        type	    : 1,
        fd          : 0,
        name        : '',
        email       : '',
        avatar      : '',
        rds         : [],//所有房间ID
        crd         : 'a', //当前房间ID
        remains     : [],
        wsserver    : 'ws://127.0.0.1:9501'
    },
    init : function (){
        this.copyright();
        chat.data.storage  =  window.localStorage;
        this.ws();
    },
    disconnect: function () {
        this.data.wSock.close
    },
    doLogin : function( name , email ){
        if(name  ===  '' || email  ===  ''){
            name  =   $('#name').val();
            email  =  $('#email').val();
        }
        name = $.trim(name);
        email = $.trim(email);
        if(name  ===  '' || email  ===  ''){
            chat.displayError('chatErrorMessage_logout','请输入昵称和Email才可以参与群聊哦～',1);
            return false;
        }
        var  re  =  /^(\w-*\.*) + @(\w-?) + (\.\w{2,}) + $/;
        if(!re.test(email)){
            chat.displayError('chatErrorMessage_logout','逗我呢，邮箱长成这样子？？',1);
            return false;
        }
        //登录操作
        chat.data.type  =  1; //登录标志
        chat.data.email  =  email; //邮箱
        chat.data.login  =  true;
        var json  =  {'type': chat.data.type,'name': name,'email': email,'roomid':'a'};
        chat.wsSend(JSON.stringify(json));
        return false;

    },
    logout : function(){

        chat.data.type  =  0;
        chat.data.fd  =  '';
        chat.data.name  =  '';
        chat.data.avatar  =  '';
        this.data.wSock.close()
        // location.reload();
    },


    ws : function(){
        this.data.wSock  =  new WebSocket('ws://127.0.0.1:9501');

        this.wsOpen();

        // this.wsClose()
        this.wsMessage();
        this.wsOnclose();
        this.wsOnerror();
    },
    wsSend : function(data){
        this.data.wSock.send(data);
    },
    wsClose : function () {
        this.data.wSock.close();
    },
    wsOpen : function (){
        let that = this
        this.data.wSock.onopen  =  function( event ){
            //初始化房间
            chat.print('wsopen',event);

            //判断是否已经登录过，如果登录过。自动登录。不需要再次输入昵称和邮箱
            /*
             var isLogin  =  chat.data.storage.getItem('dologin');
             if( isLogin ) {
             var name  =   chat.data.storage.getItem('name');
             var email  =   chat.data.storage.getItem('email');
             chat.doLogin( name , email );
             }
             */

        }
    },
    wsMessage : function(){
        this.data.wSock.onmessage = function(event){
            var d  =  jQuery.parseJSON(event.data);
            console.log(d)

        }
    },
    wsOnclose : function(){
        this.data.wSock.onclose  =  function(event){
            console.log('断开连接');
        }
    },
    wsOnerror : function(){
        this.data.wSock.onerror  =  function(event){
            console.log('服务器关闭');
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
export  default  chat
