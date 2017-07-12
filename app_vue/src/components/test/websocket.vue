<template>
  <div style="text-align: center">
      <button type="button" class="btn btn-default" @click="socket_init">连接socket</button>
      <button type="button" class="btn btn-default" @click="disconnect">断开socket</button>
      <input value="已经连接" v-if="conn_status">
      <input value="已经断开" v-else="conn_status">
        <div style="width: 800px;height: 300px;margin-left:50%;transform: translateX(-50%)">
            <label for="module">module</label><input v-model="yaf.module" id="module">
            <label for="controller">controller</label><input v-model="yaf.controller" id="controller">
            <label for="action">action</label><input v-model="yaf.action" id="action">
            <input id="send" value="提交" @click="send" type="button">

        </div>
      <hr>
      <div style="width: 800px;height: 300px;margin-left:50%;transform: translateX(-50%)">
            {{ socket_response }}
      </div>

  </div>
</template>

<script>
    import SOCKET_CLIENT from '../../api/socket_client'

    export default {
        mounted () {
            var vm = this
            this.$root.eventHub.$on('socket_response',function (data) {
                console.log('socket_response',data)
                vm.socket_response = data
            })
            this.$root.eventHub.$on('conn_status',function (data) {
                vm.conn_status = data

            })
        },
        data () {
            return {
                conn_status:false,
                yaf:{
                    module:'index',
                    controller:'index',
                    action:'test'
                },
                socket_response:'...'
            }
        },
        computed: {

        },
        methods: {

            socket_init()  {
                SOCKET_CLIENT.data.this_vue = this
                SOCKET_CLIENT.init()

            },
            disconnect() {

                SOCKET_CLIENT.wsClose()

            },
            send(){
                var vm = this
                var params = {
                    to:null,
                    payload_type:'test',
                    payload_data:{},
                    yaf:this.yaf
                }
                SOCKET_CLIENT.data.payload = params
                SOCKET_CLIENT.wsSend()
            }


        },

    }
</script>

<style lang="css">

</style>
