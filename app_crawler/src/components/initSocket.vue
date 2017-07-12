<template>
  <div class="top_router_view" :style="{width: sysinfo.screenWidth,height:sysinfo.screenWidth,overflow:'hidden'}">
    <div  id="live_ctrl_terminal">
      <div id="ctrl_conn">
        <div id="link_server" style="box-shadow: 0 0 10px #57DCDF">
          <div  @click="socket_init" class="ctrl_cls init_conn">连接伺服器</div>
          <div  @click="disconnect" class="ctrl_cls init_conn" style="background-color: rgba(231,129,83,0.52)">断开连接伺服器</div>
          <transition name="slide-fade-right" mode="out-in">
            <div  v-if="website.conn_status" key="conn_on">

              <div class="loader">
                <div class="loading--1"></div>
                <div class="loading-0"></div>
                <div class="loading-1"></div>
                <!--<div class="loading-2">{{ conn_info }}</div>-->
              </div>

            </div>
            <div  v-if="!website.conn_status" key="conn_off">
              <div class="loader">
                <div class="loading-3"></div>
                <!--<div class="loading-4">{{ conn_info }}</div>-->
              </div>
            </div>


          </transition>
        </div>
        <transition name="slide-fade-down-top" mode="out-in">

          <div v-if="website.conn_status" id="app_form" style="box-shadow: 0 0 10px #57DCDF;">
            <transition name="slide-fade-down-top" mode="out-in">
              <div v-if="!website.encryption_key ||  !website.access_user" key="access_no">
                <div id="select_app" :class="{
                    ui:ClassisActive, loading:ClassisActive,
                     fluid:ClassisActive, multiple:ClassisActive, search:ClassisActive,
                       selection:ClassisActive, dropdown:ClassisActive,
                       disabled:authing
                  }"
                     style="background: transparent; display: inline-block;position: static;box-shadow: 0 0 10px #57DCDF" disabled="">
                  <input type="hidden" name="select_app" value="">
                  <i class="dropdown icon"></i>
                  <!--<input class="search_better" style="padding: 0; width: 100%;height: 100%; position: static; border: none !important;">-->
                  <div class="search" ></div>
                  <div class="default text"></div>
                  <div class="menu" style="background: transparent !important;">

                    <div class="item"  v-for="app in app_rules" style="color: white !important; background: transparent !important;"
                         :data-value="app['appname']"
                         :data-id="app['document_id']"
                         :app-level="app['applevel']">{{ app['appname'] }}#{{app['document_id']}}@{{ app['applevel']}}</i></div>


                  </div>
                </div>
                <transition name="slide-fade-right" mode="out-in">
                  <form id="bind_app" style=" " action="javascript:;" v-if="top_level>0">
                    <div class="app_level" >{{ top_level }}</div>
                    <input type="button" class="cancel_auth" value="取消鉴权" v-if="authing" @click="cancel_auth"></input>
                    <div class="ui icon input loading forminput">
                      <input type="text" id="level_key" v-model="level_key" placeholder="输入级别key" autocomplete="off">
                      <i class="search icon"></i>
                    </div>
                    <div class="ui icon input loading forminput">
                      <input type="password" id="level_pass" v-model="level_pass" placeholder="输入级别encode" autocomplete="off">
                      <i class="search icon"></i>
                    </div>
                    <div style="height: 60px">
                      <button @click="bind_app_auth" v-if="level_key && level_pass" :class="{ui:ClassisActive, inverted:ClassisActive, teal:ClassisActive, basic:ClassisActive,
                   button:ClassisActive,loading:authing,    forminput:ClassisActive}"  style="margin-top: 10px">Authentication</button>
                    </div>
                  </form>
                </transition>
              </div>
              <div v-if="website.encryption_key && website.access_user" key="access_yes" style="width: 100%;height: 100%;">

                <div class="" style="margin:0 auto;width: 60%; height: 80%;margin-top:25px;box-shadow: 0 0 20px #57DCDF;border-radius: 5px">
                  <img :src="getheadpic" width="40%" height="100%" style="box-shadow: 0 0 20px #57DCDF;" >
                  <div style="display: inline-block;width: 60%;height: 100%;float: right; text-align: center">
                    <a class=" card-btn" >{{ website.access_user.nickname }}</a>

                    <a class=" card-btn" style="margin-top: 10%">{{ website.access_user.level }}</a>
                  </div>
                </div>


              </div>

            </transition>
          </div>
        </transition>

      </div>
      <!--<div id="ctrl_show">-->
      <!--</div>-->
      <!--<div id="cut_line"></div>-->
    </div>
  </div>
</template>
<script>
  import { mapGetters,mapActions } from 'vuex'

  import SOCKET_CLIENT from '../api/SOCKET_CLIENT'
//    import LocalVoucher from '../../api/LocalVoucherTools'
    import { analysis_socket_response } from '../api/lib/helper/dataAnalysis'

//    SOCKET_CLIENT.data.this_vue = LocalVoucher.data.this_vue = this
    const Waves  = require('node-waves');
    //    import AxiosApi from '../../../api/axiosApi'
    //    const axioxapi = new AxiosApi()
    const _ = require('lodash');
    var platform = require('platform');
    import Vue from 'vue'

    export default {

        data() {
            return {
                authing:false,
                ClassisActive: true,
                app_rules:[],
                bind_apps:[],
                top_level:0,
                level_key:'',
                level_pass:'',
                module:'',
                controller:'',
                action:'',
                to:'',
                payload_type:'',
                payload_data:'',
                isbindapps:'',
//                本地存储
                localvoucher_keys:'',
                localStorage_key:'',
                localStorage_value:'',

                socket_send_factory:[],
                init_socket_send_factory:[],
                platform:'pc'




            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo',
                'eventfactory',
            ]),
            getheadpic:function () {
                //console.log('getheadpic->',this.access_user)
                return    this.website.access_user.img?this.website.access_user.img:'http://truesign-app.oss-cn-beijing.aliyuncs.com/headpic/7dd98d1001e939010096ed5c7dec54e736d1963f.jpg'

            },

        },
        created(){
            var vm = this
            var os = this.sysinfo.os
            console.log('os->',os)
            if(os){
                var os_type = os.family.toLocaleLowerCase()
                if(os_type.indexOf('os x') > -1 || os_type.indexOf('window') > -1){
//                    console.log('电脑端',os_type)
                }
                else{
//                    console.log('手机端:',os_type)
                    this.platform = 'mobile'

                }
            }
            this.updateWebSite({

                conn_status:0,
                access_user:null

            })
            this.autoInit()


        },
        mounted(){
            var vm = this


            this.$root.eventHub.$on('conn_status',function (data) {
                if(!data){
                    vm.updateWebSite({
                        conn_status:0,
                        socket_id:''
                    })
                }
                else{
                    vm.updateWebSite({
                        conn_status:1,
                    })
                }

            })


            $('.menu').css('background','transparent')
            $('.item').css('background','transparent')
            $('.item').css('color','#f8f8f8')
            $('.item').hover(function () {
                $(this).css('background','rgba(105, 210, 231, 0.47)')
            },function () {
                $(this).css('background','transparent')
            })

            $('.search').css('color','#FFFFFF')

            var config = {
                // How long Waves effect duration
                // when it's clicked (in milliseconds)
                duration: 3000,
                // Delay showing Waves effect on touch
                // and hide the effect if user scrolls
                // (0 to disable delay) (in milliseconds)
                delay: 2000
            };
            Waves.init(config)
            Waves.attach('#app_form', ['waves-block']);
            Waves.attach('#link_server', ['waves-block']);
            Waves.attach('.init_conn', ['waves-button']);
            Waves.attach('.loading-0');
            Waves.attach('.loading-1');
            Waves.attach('.loading-2');
//
//
//            this.$root.eventHub.$on('autoInit',function (data) {
//                vm.autoInit()
//            })
//
//            /*
//            统一接口处理socket 发送信息 放入payload工厂等待遍历发送
//             */
            this.$root.eventHub.$on('socket_send',function (data) {


                var payload_data_final = Object.assign(vm.website,data.payload_data)
                data.payload_data = payload_data_final

                if(data.payload_type === 'fetchPreConnApps' || data.payload_type === 'bind_apps'){
                  vm.updateEventFactory({type:'init_socket_send_factory',event:data})
                }
                else{
                  if(data.payload_type === 'submit_form_login' || data.payload_type === 'get_appCards'){
                      vm.updateEventFactory({type:'unshift_socket_send_factory',event:data})

                  }
                  else{
                      vm.updateEventFactory({type:'socket_send_factory',event:data})

                  }
                }
//                console.log(vm.website.socket_id)
//                console.log('on->socket_send',data.payload_type,data)

            })
//
//            /*
//            处理socket 连接问题状况，及自动重新连接
//             */
            this.$root.eventHub.$on('socket_error',function (data) {
                if(data !== 1){
//                    vm.autoInit(true)
                }
            })
//
//            /*
//              遍历向服务端发送socket信息
//             */

//            var loop_socket_send = setInterval(function () {
//                if(vm.website.conn_status && vm.init_socket_send_factory.length > 0){
//                    var init_data = vm.init_socket_send_factory.shift()
//                    if(init_data){
////                        console.log('init_socket_send_factory',init_data.payload_type,init_data.yaf,init_data.payload_data)
//
//                        vm.to = init_data.to
//                        vm.payload_type = init_data.payload_type
//                        vm.payload_data = init_data.payload_data
//                        vm.module = init_data.yaf.module
//                        vm.controller = init_data.yaf.controller
//                        vm.action = init_data.yaf.action
//                        vm.send()
//                    }
//                }
//                if(vm.website.conn_status && vm.website.isbindapps.length > 0 &&  vm.socket_send_factory.length > 0){
//                    var data = vm.socket_send_factory.shift()
//
//                    if(data){
//                        if(data.payload_type === 'c2c_msg'){
//                            vm.send(true,data)
//                            return
//                        }
////                        console.log('socket_send_factory',data.payload_type,data.yaf,data.payload_data)
//                        vm.to = data.to
//                        vm.payload_type = data.payload_type
//                        vm.payload_data = data.payload_data
//                        vm.module = data.yaf.module
//                        vm.controller = data.yaf.controller
//                        vm.action = data.yaf.action
//                        vm.send()
//                    }
//                }
//
//            },100)
            /*
            统一处理服务器返回信息
            
             */
//
            var i = 1;
            this.$root.eventHub.$on('base_socket_response',function (data) {
//                console.log('initSocket->socket_reponse',data)
                var socket_reponse = analysis_socket_response(data)
                if(socket_reponse.response_type === 'get_shadowsocks_node'){
                    console.log('initsocket->get_shadowsocks_node')
                }
                if(socket_reponse.response_type === 'self_init' && socket_reponse.response_init_data.init_status){


                    vm.updateWebSite({
                        unique_auth_code:socket_reponse.response_init_data.unique_auth_code,
                        conn_status:1,
                        socket_id:socket_reponse.response_init_data.socket_id

                    })
                    vm.updateSysInfo({
                        ip:socket_reponse.response_init_data.ip
                    })
                    vm.$root.eventHub.$emit('checkloginbykey',1)
                    vm.fetchPreConnApps()
                }
                if(socket_reponse.response_type === 'fetchPreConnApps'){
                    vm.doinitapps(socket_reponse.reponse_data)
                }
                if(socket_reponse.response_type === 'bind_apps'){
                    if(socket_reponse.response_init_data.bind_status){
                        vm.updateWebSite({
                            encryption_key:socket_reponse.response_init_data.bind_status,
                            access_user:socket_reponse.response_init_data.access_user,
                            isbindapps:JSON.stringify(socket_reponse.response_init_data.isbindapps),

                        })
                        vm.$root.eventHub.$emit('init_bind_apps',socket_reponse.response_init_data.access_user)
                    }
                    else{
                        vm.$root.eventHub.$emit('showtip',{
                            content:'系统底层 -·'+socket_reponse.response_init_data.note+'·- 请联系【最高】级别管理员'
                        })
                    }
                }
                if(socket_reponse.response_type === 'ping'){
//                    console.log('ping',i)
                    i++
                    if(!vm.website.login_status){

                    }
                    vm.updateWebSite({

                        conn_status:1,

                    })

                    if(vm.website.conn_status && vm.eventfactory.init_socket_send_factory.length > 0){
                        var init_data = vm.eventfactory.init_socket_send_factory.shift()
//                        console.log('init_data->',init_data.payload_type,init_data)
//                        vm.updateEventFactory({type:'shift_init_socket_send_factory'})
                        if(init_data){
//                        console.log('init_socket_send_factory',init_data.payload_type,init_data.yaf,init_data.payload_data)

                            vm.to = init_data.to
                            vm.payload_type = init_data.payload_type
                            vm.payload_data = init_data.payload_data
                            vm.module = init_data.yaf.module
                            vm.controller = init_data.yaf.controller
                            vm.action = init_data.yaf.action
                            vm.send()
                        }
                    }
//                    console.log('怎么不执行',vm.website.conn_status,vm.website.isbindapps.length,)

                    if(vm.website.conn_status && vm.website.isbindapps.length > 0 &&  vm.eventfactory.socket_send_factory.length > 0){
                        var event_data = vm.eventfactory.socket_send_factory.shift()
//                        console.log('event_data->',event_data.payload_type,event_data)

//                        vm.updateEventFactory({type:'shift_socket_send_factory'})


                        if(event_data){
                            if(event_data.payload_type === 'c2c_msg'){
                                vm.send(true,event_data)
                            }
//                        console.log('socket_send_factory',data.payload_type,data.yaf,data.payload_data)
                            else{
                                vm.to = event_data.to
                                vm.payload_type = event_data.payload_type
                                vm.payload_data = event_data.payload_data
                                vm.module = event_data.yaf.module
                                vm.controller = event_data.yaf.controller
                                vm.action = event_data.yaf.action
                                vm.send()
                            }

                        }
                    }
                }

            })

//

        },
        updated(){
            Waves.attach('#app_form', ['waves-block']);
        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
                'updateEventFactory',
            ]),
            autoInit(reinit=false){
                if(reinit){
                    this.socket_init()
                }
                this.socket_init()
                var vm = this
                vm.level_key = 'anonymous'
                vm.level_pass = 'anonymous'
                vm.bind_apps = ['website#2@1']
                var init_bind_app = setInterval(function () {
                    if(vm.website.conn_status){
                        vm.bind_app_auth()
                        clearInterval(init_bind_app)
                    }
                },10)
            },

            fetchPreConnApps(){
                var vm = this
                var params = {
                    to:null,
                    payload_type:'fetchPreConnApps',
                    payload_data:{},
                    yaf:{
                        module:'index',
                        controller:'apps',
                        action:'getAppRule'
                    }
                }
                vm.$root.eventHub.$emit('socket_send',params)
            },
            doinitapps(apprules){
                var vm = this

                this.app_rules = apprules
                vm.updateWebSite({
                    apprules:apprules
                })
                $('#select_app').dropdown({
//                    direction: 'upward',
                    allowAdditions: true,
                    useLabels:true,
                    fullTextSearch:true,
                    label: {
                        transition : 'horizontal flip',
                        duration   : 200,
                        variation  : false,

                    },
                    onChange(value, text, $choice){
                        //console.log('change')


                    },
                    onAdd(addedValue, addedText, $addedChoice){
                        //console.log('add')
                        //console.log(addedValue)
                        //console.log(addedText)
                        //console.log($addedChoice)
                        vm.dealLevel(addedText,'add')
                    },
                    onRemove(removedValue, removedText, $removedChoice){
                        //console.log('remove')
                        //console.log(removedValue)
                        //console.log(removedText)
                        //console.log($removedChoice)
                        vm.dealLevel(removedText,'remove')
                    },
                    onLabelSelect($selectedLabels){
                        //console.log('labelSelect')
                    },
                    onShow(){
                        //console.log('onShow')
                    },
                    onHide(){
                        //console.log('onHide')
                    }



                });
            },
            bind_app_auth(){
                this.authing = 1
                var params = {
                    to:null,
                    payload_type:'bind_apps',
                    payload_data:{
                        apps:this.bind_apps,
                        key:this.level_key,
                        pass:this.level_pass,
                    },
                    yaf:{
                        module:'index',
                        controller:'apps',
                        action:'bindapps'
                    }
                }
                this.$root.eventHub.$emit('socket_send',params)
            },
            cancel_auth(){
                this.authing = 0
            },
            socket_init()  {
                
                SOCKET_CLIENT.data.this_vue = this
                SOCKET_CLIENT.init(this.website.unique_auth_code)

            },
            disconnect() {
                this.updateWebSite({
                    conn_status:0,
                    socket_id:'',

                })
                SOCKET_CLIENT.wsClose()

            },
            send(c2c_msg=false,msg=''){
                if(!c2c_msg){
                    this.payload = {
                        to:parseInt(this.to),
                        payload_type:this.payload_type,
                        payload_data:this.payload_data,
                        yaf:{
                            module:this.module,
                            controller:this.controller,
                            action:this.action,
                        }
                    }

                    SOCKET_CLIENT.data.payload = this.payload
                    SOCKET_CLIENT.data.this_vue = this
                    let response = SOCKET_CLIENT.wsSend()
                }
                else if(c2c_msg){
                    SOCKET_CLIENT.data.payload = msg
                    SOCKET_CLIENT.data.this_vue = this
                    let response = SOCKET_CLIENT.wsSend()
                }



            },
            dealLevel(str,type='remove'){
                if(type==='add'){
                    this.bind_apps.push(str)
                }
                else{
                    let bind_apps = this.bind_apps
                    this.bind_apps = _.remove(bind_apps, function(item) {
                        return item !== str
                    })
                }
                //console.log(this.bind_apps)
                let tmp_level = 0
                this.bind_apps.forEach(function (v,k) {
                    let id = v.match(/#\d+/)[0].replace('#','')
                    let level = v.match(/@\d+/)[0].replace('@','')

                    if(level>tmp_level){
                        tmp_level = level
                    }
                })
                this.top_level = tmp_level
                if(this.top_level === 0){
                    this.authing = 0
                }
                //console.log(this.top_level)

            },
            matchMoveSelect(data){
                console.log('matchMoveSelect')
                //console.log(data)
                var reg = /#.+-\d+/
                var match_data = (data.content + "").match(reg)
                //console.log(match_data[0])
                if(match_data){
                    var match_arr = match_data[0].split('-')

                    var match_movie = match_arr[0].replace('#','')
                    var match_ticket = parseInt(match_arr[1])

                    //console.log(match_movie)
                    //console.log(match_ticket)
                    if(match_ticket>=100){
                        this.module = 'index'
                        this.controller = 'user'
                        this.action = 'demandLiveVideo'
                        this.payload_type = 'demandLiveVideo'
                        this.payload_data = {
                            'unique_auth_code': vm.website.unique_auth_code,
                            'encryption_key': vm.website.encryption_key,
                            'nickname':data.nickname,
                            'match_movie':match_movie,
                            'match_ticket':match_ticket,
                            'source':data.source,

                        }
                        this.send()
                    }


                }


            }


        },

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

  .danmu-list-item {
    transition: all 1s;
    display: block;
  }

  .danmu-list-enter, .danmu-list-leave-active {
    opacity: 0;
    transform: translateY(-30px);
  }
  .danmu-list-leave-active {
    position: absolute;
  }


  .danmu-list-table thead tr {
    display:block;
    background :transparent !important
  }
  .danmu-list-table tbody {
    display: block;
    height: 750px;
    overflow: hidden;


  }
  .danmu-list-table th {
    width:160px;
    background transparent !important
    color white !important
  }
  .danmu-list-table td {
    width:160px;
  }
  /*
.danmu-list-move{
  transition transform   1.5s;
}
*/

  img{
    border-radius 5px !important
  }
  &.card-btn
    margin-top 10% !important
    margin 0 auto
    text-align center
    color white
    width 50% !important
    box-shadow: 0 0 20px #57DCDF !important
    padding 10px 20px 10px 20px
    display block
    border-radius 5px
    font-weight:bold;
    font-size:15px;
    text-shadow:0 0 1px #57DCDF;
  input
    background transparent !important
    color #55EEEF !important
  loader_width = 30%

  .label
    background rgba(105, 210, 231, 0.47) !important
  #live_ctrl_terminal
    width 600px
    height 90%
    margin 40px auto

  #cut_line
    width 2px
    height 100%
    position relative
    margin 10px auto
    background #a2a2a2
    border 1px solid gray
    padding-right  1px
  #ctrl_conn
    width 100%
    height 100%
    position relative
    float left
    #link_server
      padding-bottom 20px
    #app_form

      padding 0
      min-height 300px
      height auto
      text-align center
      #select_app
        display block
        padding 0
        margin 20px 0 20px 0

        width 80%


      #bind_app
        position relative
        margin 20px 0 20px 0

        width: 63%;
        display: inline-block;
        vertical-align: top;
        background: transparent;
        border-right solid 1px rgba(105,210,231,0.44)

        box-shadow: 2px 0px 15px rgba(81, 140, 159, 0.56);
        .app_level
          display inline-block
          width 50px
          height 50px
          border-radius 10px
          background-color rgba(192, 192, 192, 0.31)
          margin-top 10px
          margin-left 10px
          text-align center
          font-size 22px
          line-height:50px;
          color #54FFFF
        .cancel_auth
          border 2px solid rgba(255, 137, 19, 0.44)
          position absolute
          display inline-block
          right 0
          width 100px
          height 30px
          border-radius 10px
          background-color rgba(192, 192, 192, 0.31)
          margin-top 10px
          margin-left 10px
          text-align center
          font-size 16px
          line-height:20px;
          color  rgba(255, 137, 19, 0.44) !important
          margin-top 15px
        .forminput
          margin-left 10%
          margin-top 2%
          width 80%
          background transparent
      #app_form>div
        background-color transparent
  #ctrl_show
    width 45%
    height 100%
    position relative
    float right

  .ctrl_cls,.ctrl_cls:hover
    background-color rgba(105, 210, 231, 0.48)
    color #ffffff
    font-size 16px
    display inline-block
    border-radius:5px;
    margin-top 10px
    margin-left 10px



  .loader {
    width: loader_width
    height 50px
    margin: 0 auto;
    position: absolute;
    left 300px
    margin-top -45px
    margin-left 20px


  }
  .loader .loading-0 {
    display inline-block
    top 20px
    position: absolute;
    width: 100%;
    height: 10px;
    border: 1px solid #69d2e7;
    border-radius: 10px;
    overflow hidden
  }
  .loader .loading-0:before {
    content: "";
    border-radius: 10px;
    display: inline-block;
    position: absolute;
    width: 100%;
    height: 100%;
    background: #69d2e7;
    box-shadow: 10px 0px 15px 0px #69d2e7;
    animation: load_def 3s linear infinite;
  }

  .loader .loading--1 {
    display inline-block
    top 35px
    position: absolute;
    width: 100%;
    height: 10px;
    border: 1px solid #69d2e7;
    background #69d2e7
    border-radius: 10px;
    overflow hidden
  }
  .loader .loading--1:before {
    content: "";
    border-radius: -170px;
    display: inline-block;
    position: absolute;
    width: 60%;
    height: 100%;
    background: #525B64;
    box-shadow: 10px 0px 15px 0px #69d2e7;
    animation: load_def 2s linear infinite;
  }
  .loader .loading-1 {
    display inline-block

    position: relative;
    width: 100%;
    height: 10px;
    border: 1px solid #69d2e7;
    border-radius: 10px;
    animation: turn 4s linear 3.75s infinite;
  }

  .loader .loading-1:before {
    content: "";
    border-radius: 10px;
    display: inline-block;
    position: absolute;
    width: 0%;
    height: 100%;
    background: #69d2e7;
    box-shadow: 10px 0px 15px 0px #69d2e7;
    animation: load 2s linear infinite;
  }
  .loader .loading-2 {
    display inline-block

    width: 100%;
    position: absolute;
    color: #69d2e7;
    left 100%
    font-size: 20px;
    text-align: center;
    animation: bounce 2s  linear infinite;
  }
  .loader .loading-3 {
    display inline-block

    position: relative;
    width: 100%;
    height: 10px;
    border: 1px solid #e79977;
    border-radius: 10px;
    animation: turn 4s linear 3.75s infinite;
  }
  .loader .loading-3:before {
    content: "";
    display: inline-block;
    position: absolute;
    width: 0%;
    height: 100%;
    background: #e79977;
    box-shadow: 10px 0px 15px 0px #e79977;
    animation: load 2s linear infinite;
  }
  .loader .loading-4 {
    display inline-block

    width: 100%;
    position: absolute;
    color: #e79977;
    left 100%
    font-size: 20px;
    text-align: center;
    animation: bounce 2s  linear infinite;
  }
  @keyframes load {
    0% {
      width: 0%;
    }
    87.5%, 100% {
      width: 100%;
    }
  }
  @keyframes load_def {
    0% {
      left: -300px;

    }
    87.5%, 100% {
      left: 300px;
    }
  }

  @keyframes turn {
    0% {
      transform: rotateY(0deg);
    }
    6.25%, 50% {
      transform: rotateY(180deg);
    }
    56.25%, 100% {
      transform: rotateY(360deg);
    }
  }
  @keyframes bounce {
    0%,100% {
      top: -5px;
    }
    12.5% {
      top: 5px;
    }
  }

  .slide-fade-right-enter-active{
    transition: all 1.5s
  }

  .slide-fade-right-enter{
    transition: all 1.5s
    opacity: 0
    transform translateX(-20%)
  }
  .slide-fade-right-leave-active{
    transition: all 1.5s
    opacity: 0
    transform translateX(20%)
  }
  .slide-fade-right-leave{
    transition: all 1.5s

  }



  .slide-fade-down-top-enter-active{
    transition: all 1.5s
  }

  .slide-fade-down-top-enter{
    transition: all 1.5s
    opacity: 0
    transform translateY(-20%)
  }
  .slide-fade-down-top-leave-active{
    transition: all 1.5s
    opacity: 0
    transform translateY(-20%)
  }
  .slide-fade-down-top-leave{
    transition: all 1.5s

  }




</style>