
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
        if(!this.data.login) return false;
        chat.data.type  =  0;
        chat.data.storage.removeItem('dologin');
        chat.data.storage.removeItem('name');
        chat.data.storage.removeItem('email');
        chat.data.fd  =  '';
        chat.data.name  =  '';
        chat.data.avatar  =  '';
        location.reload();
    },


    ws : function(){
        this.data.wSock  =  new WebSocket('ws://127.0.0.1:9501');
        // this.wsOpen();
        // this.wsMessage();
        // this.wsOnclose();
        // this.wsOnerror();
    },
    wsSend : function(data){
        this.data.wSock.send(data);
    },
    wsOpen : function (){
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
            switch(d.code){
                case 1:
                    if(d.data.mine){
                        chat.data.fd  =  d.data.fd;
                        chat.data.name  =  d.data.name;
                        chat.data.avatar  =  d.data.avatar;
                        chat.data.storage.setItem('dologin',1);
                        chat.data.storage.setItem('name',d.data.name);
                        chat.data.storage.setItem('email',chat.data.email);
                        document.title  =  d.data.name  +  '-'  +  document.title;
                        chat.loginDiv(d.data);
                    }
                    chat.addChatLine('newlogin',d.data,d.data.roomid);
                    chat.addUserLine('user',d.data);
                    chat.displayError('chatErrorMessage_login',d.msg,1);
                    break;
                case 2:
                    if(d.data.mine){
                        chat.addChatLine('mymessage',d.data,d.data.roomid);
                        $('#chattext').val('');
                    } else {
                        if(d.data.remains){
                            for(var i  =  0; i < d.data.remains.length; i++){
                                if(chat.data.fd  ===  d.data.remains[i].fd){
                                    chat.shake();
                                    var msg  =  d.data.name  +  '在群聊@了你。';
                                    chat.displayError('chatErrorMessage_logout',msg,0);
                                }
                            }
                        }
                        chat.chatAudio();
                        chat.addChatLine('chatLine',d.data,d.data.roomid);
                    }
                    break;
                case 3:
                    chat.removeUser('logout',d.data);
                    if(d.data.mine && d.data.action  ===  'logout'){

                        return;
                    }
                    chat.displayError('chatErrorMessage_logout',d.msg,1);
                    break;
                case 4: //页面初始化
                    chat.initPage(d.data);
                    break;
                case 5:
                    if(d.data.mine){
                        chat.displayError('chatErrorMessage_logout',d.msg,1);
                    }
                    break;
                case 6:
                    if(d.data.mine){
                        //如果是自己

                    } else {
                        //如果是其他人

                    }
                    //删除旧房间该用户
                    chat.changeUser(d.data);
                    chat.addUserLine('user',d.data);
                    break;
                default :
                    chat.displayError('chatErrorMessage_logout',d.msg,1);
            }
        }
    },
    wsOnclose : function(){
        this.data.wSock.onclose  =  function(event){
        }
    },
    wsOnerror : function(){
        this.data.wSock.onerror  =  function(event){
            //alert('服务器关闭，请联系QQ:1335244575 开放测试2');
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
