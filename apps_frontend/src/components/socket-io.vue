<template>
    <div>
        <button type="button" class="btn btn-default" @click="socket_init">连接socket</button>
        <button type="button" class="btn btn-default" @click="disconnect">断开socket</button>

        {{ msg }}
        <hr>
        <input type="text" v-model="cmd" />
        <button type="button" class="btn btn-default" @click="emit">emit</button>

    </div>
</template>

<script>
    import io from 'socket.io-client'
    //    var socket = require('socket.io-client')('ws://127.0.0.1:9501');
    //    socket.on('connect', function(){
    //        alert(1)
    //    });
    //    socket.on('event', function(data){
    //        alert(2)
    //
    //    });
    //    socket.on('disconnect', function(){
    //        alert(3)
    //
    //    });
    export default {
        mounted () {
            this.socket = io.connect('http://127.0.0.1:9501');
            this.socket.on('connect', function(){
                console.log('已连接')
            });
            this.socket.on('event', function(data){
                console.log('事件')
                console.log(data)
            });
            this.socket.on('disconnect', function(){
                console.log('断开连接')
            });
            this.socket.on('reconnect',function(){
                console.log('重新连接上')
            })
            this.socket.on('reconnecting',function(){
                console.log('重新连接中')

            })

        },
        data () {
            return {
                msg: '',
                socket: '',
                cmd:'cmd'
            }
        },
        computed: {

        },
        methods: {
            socket_init()  {
//                this.socket.socket.reconnect()
                setInterval(() => {
                    this.socket.emit('heartbeat', 1);
                },1000)
                this.msg = 'start'


            },
            disconnect() {
                this.socket.disconnect()
                this.msg = 'end'
            },
            reconnect() {
                this.socket.socket.connect()
            },
            emit(){
                let cmd = this.cmd
                console.log(cmd)
                this.socket.emit(cmd,123);
            }


        },

    }
</script>

<style lang="css">
</style>
