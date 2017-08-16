import {String2Blob} from './lib/helper/javascriptDatatypeConvert'
import reconnectingwebsocket from 'reconnectingwebsocket'
import {isEmptyValue} from './lib/helper/dataAnalysis'
import store from '../store'
import getters from '../store/getters'
const SOCKET_CLIENT = {
    data: {
        wSock: null,
        to: '',
        message: null,
        payload: null,
        wsserver: 'ws://120.24.54.208:9501',
        response: '',
        this_vue: null,
        conn_status: false,
        status: '暂未连接到服务器',
        check_socket_status: '',
    },
    init: function (unique_auth_code = 0) {
        this.copyright();
        unique_auth_code = isEmptyValue(store.getters.socketinfo.unique_auth_code)?'':store.getters.socketinfo.unique_auth_code
        console.log('unique_auth_code=>',unique_auth_code)
        let socket_url = this.data.wsserver+'/?unique_auth_code=' + unique_auth_code
        if (!this.data.conn_status) {
            this.ws(socket_url);
        }
        else {
            console.log('当前已经为连接状态')
        }


    },

    ws: function (socket_url) {

        console.log('准备连接到服务器=>')
        // this.data.wSock  =  new WebSocket('ws://iamsee.com:9501/?unique_auth_code='+unique_auth_code);
        this.data.wSock = new reconnectingwebsocket(socket_url);
        this.wsOpen();
        this.wsMessage();
        this.wsOnclose();
        this.wsOnerror();

    },
    wsSend: function () {

        // console.log('send=>')
        // console.log(JSON.stringify(this.data.payload))
        let unique_auth_code = isEmptyValue(store.getters.socketinfo.unique_auth_code)?'':store.getters.socketinfo.unique_auth_code
        let token = isEmptyValue(store.getters.socketinfo.token)?'':store.getters.socketinfo.token
        this.data.payload.payload_data.unique_auth_code = unique_auth_code
        this.data.payload.payload_data.token = token
        var send_blob = String2Blob(JSON.stringify(this.data.payload))
        // console.log('this.data.wSock',this.data.wSock)
        this.data.wSock.send(send_blob);

    },
    wsClose: function () {

        console.log('关闭连接')
        this.data.wSock.close();


    },
    wsOpen: function () {
        let that = this
        this.data.wSock.onopen = function (event) {
            that.data.status = '连接正常'
            that.data.conn_status = true
            that.data.this_vue.conn_status = true
            SOCKET_CLIENT.print('wsopen', event);
        }
    },
    wsMessage: function () {

        let that = this
        this.data.wSock.onmessage = function (event) {

            if (typeof event.data === 'object') {
                var reader = new FileReader();
                reader.readAsText(event.data, 'utf-8');
                reader.onload = function (e) {
                    event.datastr = JSON.parse(reader.result)
                    let type = event.datastr.type
                    // console.log('-----------'+type+'------------')
                    if(type === 'open'){
                        // console.log(event.datastr.msg.unique_auth_code)

                        store.dispatch('updateSocketInfo',{
                            unique_auth_code:{
                                type: 'update',
                                value: event.datastr.msg.unique_auth_code
                            },
                            cid:{
                                type: 'update',
                                value: event.datastr.msg.cid
                            },
                            socket_response:{
                                type: 'update',
                                value: event.datastr.socket_response
                            }
                        })
                    }
                    else if(type === 'ping'){
                        store.dispatch('updateSocketInfo',{
                            ping:{
                                type: 'update',
                                value: event.datastr
                            }
                        })
                    }
                    else{
                        store.dispatch('updateSocketInfo',{
                            socket_response:{
                                type: 'update',
                                value: event.datastr.socket_response
                            },
                            relation:{
                                type: 'update',
                                value: event.datastr.relation
                            }
                        })
                    }
                    /*
                    * 判定存储token
                    * */
                    if(type === 'login'){
                        console.log(event.datastr)
                        if(event.datastr.socket_response.code === 0){
                            let login_info = JSON.parse(event.datastr.socket_response.desc)
                            store.dispatch('updateSocketInfo',{
                                token:{
                                    type: 'update',
                                    value: login_info.token
                                },
                                userinfo:{
                                    type: 'update',
                                    value: login_info.ctrlname
                                },
                                apps:{
                                    type: 'update',
                                    value: login_info.apps
                                }

                            })
                        }
                    }
                }
            }
            else {
                event.datastr = event.data
            }


        }


    },
    wsOnclose: function () {
        let that = this

        this.data.wSock.onclose = function (event) {
            console.log('[c]close=>');
            console.log(event)
            that.data.conn_status = false
            that.data.this_vue.conn_status = false

            // if(event.type === 'close'){
            //     // that.data.wSock = null
            // }
            // if(that.data.wSock){
            //     console.log('关闭失败')
            // }
            // else{
            //     that.data.status = '连接关闭'
            //     console.log('关闭成功')
            //     that.data.this_vue.$root.eventHub.$emit('socket_close',1)
            //
            //
            //
            //
            // }
        }
    },
    wsOnerror: function () {
        this.data.wSock.onerror = function (event) {
            console.log('[c]error=>');
            console.log(event);
        }
    },

    print: function (flag, obj) {
        console.log('----' + flag + ' start-------');
        console.log(obj);
        console.log('----' + flag + ' end-------');
    },
    copyright: function () {
        if (this.data.status !== '连接正常') {
            this.data.status = 'truesign ico pre connect to socket server ……'
            console.log(this.data.status);
        }
    },
    loopCheckStatus: function () {
        // var that = this
        // if(check_socket_status){
        //     clearInterval(check_socket_status)
        // }
        //
        // var check_socket_status = setInterval(function () {
        //     console.log('loopCheckStatus')
        //     console.log('that.data.wSock.readyState',that.data.wSock.readyState)
        // that.data.this_vue.$root.eventHub.$emit('check_level',1)
        // if(!that.data.wSock){
        //     // console.log('socket_error->','未连接')
        //     that.data.this_vue.$root.eventHub.$emit('socket_error','未连接')
        // }
        // else if(that.data.wSock.readyState != 1){
        //     // console.log('socket_error->',that.data.wSock.readyState)
        //     that.data.this_vue.$root.eventHub.$emit('socket_error',that.data.wSock.readyState)
        //     that.data.wSock = ''
        //
        // }
        // else{
        //     // console.log('socket_status->',that.data.wSock.readyState)
        //     that.data.this_vue.$root.eventHub.$emit('socket_error',that.data.wSock.readyState)
        // }
        // },3000)
        // this.data.check_socket_status = check_socket_status
    }
}
export  default  SOCKET_CLIENT
