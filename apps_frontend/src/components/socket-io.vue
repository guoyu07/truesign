<template lang="html">
  <div>
      {{ msg }}
      <button type="button" class="btn btn-default" @click="socket_init">连接socket</button>
      <button type="button" class="btn btn-default" @click="disconnect">断开socket</button>
  </div>
</template>

<script>
    import io from 'socket.io-client'

    export default {
//        mounted () {
//            this.msg = 'start'
//            this.socket = SOCKET_CLIENT.socket
//
//        },
        data () {
            return {
                msg: '',
                socket: ''
            }
        },
//        computed: {
//
//        },
        methods: {
            socket_init()  {
                this.socket = io.connect('ws://127.0.0.1:9501');
                setInterval(() => {
                    this.socket.emit('heartbeat', 1);
                },1000)
                this.msg = 'start'
            },
            disconnect() {
                this.socket.disconnect()
                this.msg = 'end'
            }


        }
    }
</script>

<style lang="css">
</style>
