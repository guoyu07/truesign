<template>
  <div id="tools_websocket" style="text-align: left">
        <div style="left:50%;position: absolute;top:0;transform:translate(-50%);min-width: 1000px">

            <button type="button" class="btn btn-default" @click="socket_init">连接socket</button>
            <button type="button" class="btn btn-default" @click="disconnect">断开socket</button>
            <input value="已经连接" v-if="conn_status">
            <input value="已经断开" v-else="conn_status">
            <input id="username" placeholder="用户名" v-model="socket_account.username"  >
            <input id="pass" placeholder="密码"  v-model="socket_account.password">
            <input type="button" @click="login_socket" value="登录">
            <el-select v-if="socketinfo.apps" @change="app_change" clearable filterable style="position: absolute;margin-left: 10px;width: 200px;background-color: white" v-model="app_selected" multiple placeholder="请选择">
                <el-option
                        v-for="item,index in socketinfo.apps"
                        :key="item.document_id"
                        :label="item.document_id+ '  ' +item.appname"
                        :value='"{\""+item.document_id+"\":\""+item.appname+"\"}"'>
                </el-option>
            </el-select>

        </div>
        <div style="left:50%;top:50px;position: absolute;transform:translate(-50%);min-width: 1000px">
            {{socketinfo.unique_auth_code }} cid: {{socketinfo.cid}}
            <p v-if="socketinfo.token">{{socketinfo.userinfo }} token: {{socketinfo.token }}</p>
            <hr>

            {{socketinfo.ping }}
        </div>
        <div style="left:50%;position: absolute;top:150px;transform:translate(-50%);min-width: 1000px">
            <label for="module">module</label><input v-model="yaf.module" id="module">
            <label for="controller">controller</label><input v-model="yaf.controller" id="controller">
            <label for="action">action</label><input v-model="yaf.action" id="action">
            <br>

            <div style="float: left;margin-top: 10px;">
                <label for="action">payload</label><input style="width: 600px" v-model="payload_data" id="payload">
                <input id="send" value="提交" @click="send" type="button" >
            </div>

        </div>

      <div style="width: 800px;height: 300px;min-width: 900px;margin: 0 auto;position: absolute;top:230px;left:50%;transform: translateX(-50%);text-align: center;border-radius: 8px;min-width: 1000px">
          <!--{{ socketinfo.apps }}-->
          <!--{{ app_selected }}-->
          {{ socketinfo.relation }}
          <pre style="width: 100%;height: 100%;background-color: transparent;box-shadow: 0 0 15px #fff">
          {{ socketinfo.socket_response }}
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
                    password:'',
                    authway:'KKK',
                },
                conn_status:false,
                yaf:{
                    module:'index',
                    controller:'Logic',
                    action:'chat',
                },
                payload_data:'{"msg":123}',

                app_selected: []
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
            login_socket(){
                let login_yaf ={
                    module:'index',
                    controller:'Socketauth',
                    action:'auth',
                }
                var params = {
                    to:'self',
                    payload_type:'login',
                    payload_data:this.socket_account,
                    yaf:login_yaf
                }
                SOCKET_CLIENT.data.payload = params
                SOCKET_CLIENT.wsSend()
            },
            send(){
                var vm = this
                var params = {
                    to:'todo',
                    payload_type:'send',
                    payload_data:JSON.parse(this.payload_data),
                    yaf:this.yaf
                }
                SOCKET_CLIENT.data.payload = params
                SOCKET_CLIENT.wsSend()
            },
            app_change(data){
                var vm = this
                let login_yaf = {
                    module: 'index',
                    controller: 'Socketauth',
                    action: 'update_apps',
                };

                var params = {
                    to: 'self',
                    payload_type: 'update_apps',
                    payload_data: {app: this.app_selected},
                    yaf: login_yaf
                }

                SOCKET_CLIENT.data.payload = params;
                SOCKET_CLIENT.wsSend();



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
    #tools_websocket .el-select input
        background-color white
        color black !important
</style>
