<template>
  <div id="tools_websocket" style="text-align: left">
        <div style="left:50%;position: absolute;top:0;transform:translate(-50%);min-width: 1000px">
            <input id="username" placeholder="用户名" v-model="socket_account.username"  >
            <input id="pass" placeholder="密码"  v-model="socket_account.password">
            <button type="button" class="btn btn-default" @click="socket_init">连接socket</button>
            <button type="button" class="btn btn-default" @click="disconnect">断开socket</button>
            <input value="已经连接" v-if="conn_status">
            <input value="已经断开" v-else="conn_status">

        </div>
        <div style="left:50%;top:50px;position: absolute;transform:translate(-50%);min-width: 1000px">
            {{socketinfo.unique_auth_code }} cid: {{socketinfo.cid}}
        </div>
        <div style="left:50%;position: absolute;top:100px;transform:translate(-50%);min-width: 1000px">
            <label for="module">module</label><input v-model="yaf.module" id="module">
            <label for="controller">controller</label><input v-model="yaf.controller" id="controller">
            <label for="action">action</label><input v-model="yaf.action" id="action">
            <br>

            <div style="float: left;margin-top: 10px;">
                <label for="action">payload</label><input style="width: 600px" v-model="yaf.payload" id="payload">
                <input id="send" value="提交" @click="send" type="button" >
            </div>

        </div>

      <div style="width: 800px;height: 300px;min-width: 900px;margin: 0 auto;position: absolute;top:200px;left:50%;transform: translateX(-50%);text-align: center;border-radius: 8px;min-width: 1000px">

          <pre style="width: 100%;height: 100%;background-color: transparent;box-shadow: 0 0 15px #fff">
          {{ socket_response }}
          </pre>
      </div>

  </div>
</template>

<script>
    import SOCKET_CLIENT from '../../api/new_socket_client'
    import { mapGetters,mapActions } from 'vuex'

    export default {
        mounted () {
            var vm = this
            this.$root.eventHub.$on('socket_response',function (data) {
                console.log('socket_response',data)
                vm.socket_response = data
            })

        },
        data () {
            return {
                socket_account:{
                    username:'',
                    password:''
                },
                conn_status:false,
                yaf:{
                    module:'index',
                    controller:'index',
                    action:'test',
                    payload:''
                },
                socket_response:'...'
            }
        },
        computed: {
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo',
                'appshow',
                'socketinfo'
            ])
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

<style lang="stylus" rel="stylesheet/stylus">
    #tools_websocket
        input
            background-color grey
            padding:5px 10px !important
        label
            padding:5px 10px !important
            background-color rgba(102, 102, 102, 0.28) !important
            border-top-left-radius  5px
            border-bottom-left-radius  5px
        #send
            transition all 0.6s
            width 100px
        #send:hover
            background-color #2bdb8d
</style>
