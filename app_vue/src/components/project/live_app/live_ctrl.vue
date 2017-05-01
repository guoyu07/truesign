<template>
  <div >
    <div  id="live_ctrl_terminal">
      <div id="ctrl_conn">
        <div id="link_server" style="box-shadow: 0 0 10px #57DCDF">
          <div  @click="socket_init" class="ctrl_cls init_conn">连接伺服器</div>
          <div  @click="disconnect" class="ctrl_cls init_conn" style="background-color: rgba(231,129,83,0.52)">断开连接伺服器</div>
          <transition name="slide-fade-right" mode="out-in">
            <div  v-if="conn_status" key="conn_on">

              <div class="loader">
                <div class="loading--1"></div>
                <div class="loading-0"></div>
                <div class="loading-1"></div>
                <div class="loading-2">{{ conn_info }}</div>
              </div>

            </div>
            <div  v-if="!conn_status" key="conn_off">
              <div class="loader">
                <div class="loading-3"></div>
                <div class="loading-4">{{ conn_info }}</div>
              </div>
            </div>


          </transition>
        </div>
        <transition name="slide-fade-down-top" mode="out-in">

          <div v-if="conn_status" id="app_form" style="box-shadow: 0 0 10px #57DCDF;">
            <transition name="slide-fade-down-top" mode="out-in">
              <div v-if="!encryption_key" key="access_no">
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
              <div v-if="encryption_key" key="access_yes" style="width: 100%;height: 100%;">
                  <div class="" style="margin:0 auto;width: 60%; height: 80%;margin-top:25px;box-shadow: 0 0 20px #57DCDF;border-radius: 5px">
                      <img :src="getheadpic" width="40%" height="100%" style="box-shadow: 0 0 20px #57DCDF;" >
                    <div style="display: inline-block;width: 60%;height: 100%;float: right; text-align: center">
                      <a class=" card-btn" >{{ access_user.nickname }}</a>

                      <a class=" card-btn" style="margin-top: 10%">{{ access_user.level }}</a>
                    </div>
                  </div>


              </div>

            </transition>
          </div>
        </transition>

      </div>
      <div id="ctrl_show">
          <!--{{ unique_auth_code }}-->
          <!--<div class="ui icon input loading ">-->
            <!--<input type="text"  v-model="localStorage_key" placeholder="key">-->
            <!--<i class="search icon"></i>-->
          <!--</div>-->
          <!--<div class="ui icon input loading ">-->
            <!--<input type="text"  v-model="localStorage_value" placeholder="value">-->
            <!--<i class="search icon"></i>-->
          <!--</div>-->
          <!--<hr>-->
          <!--<button @click="doStorageSave"  :class="{ui:ClassisActive, inverted:ClassisActive, teal:ClassisActive, basic:ClassisActive,-->
               <!--button:ClassisActive,    forminput:ClassisActive}"  style="margin-top: 10px">存储</button>-->
        <!--<button @click="doStorageGet"  :class="{ui:ClassisActive, inverted:ClassisActive, teal:ClassisActive, basic:ClassisActive,-->
               <!--button:ClassisActive,    forminput:ClassisActive}"  style="margin-top: 10px">读取</button>-->
        <!--<button @click="doStorageGetKeys"  :class="{ui:ClassisActive, inverted:ClassisActive, teal:ClassisActive, basic:ClassisActive,-->
               <!--button:ClassisActive,    forminput:ClassisActive}"  style="margin-top: 10px">获取keys</button>-->
        <!--<button @click="doStorageClear"  :class="{ui:ClassisActive, inverted:ClassisActive, teal:ClassisActive, basic:ClassisActive,-->
               <!--button:ClassisActive,    forminput:ClassisActive}"  style="margin-top: 10px">清空</button>-->
        <!--{{ localvoucher_keys }}-->
        <table class="ui celled table danmu-list-table" style="background-color:rgba(255,251,251,0.06);text-align: left; font-size: 14px ;color: white ;">
          <thead>
            <tr>
              <th >昵称</th>
              <th style="width: 280px">消息</th>
              <th >时间戳</th>
              <th >来源</th>
            </tr>
          </thead>

          <transition-group name="danmu-list" tag="tbody">
                            <!--v-bind:css="false"-->
                            <!--v-on:before-enter="beforeEnter"-->
                            <!--v-on:enter="enter"-->
                            <!--v-on:leave="leave"-->

            <!--<tr  v-for="item in items" v-bind:key="item.filename">-->
            <tr class="danmu-list-item"  v-for="item in danmu_list" v-bind:key="item"   >
              <td >{{ item.nickname }}</td>
              <td style="width: 300px">{{ item.content }}</td>
              <td >{{ item.time }}</td>
              <td >{{ item.source }}</td>
            </tr>
          </transition-group>
        </table>
      </div>
      <div id="cut_line"></div>
    </div>
  </div>
</template>
<script>
    import SOCKET_CLIENT from '../../../api/SOCKET_CLIENT'
    import LocalVoucher from '../../../api/LocalVoucher'
    SOCKET_CLIENT.data.this_vue = LocalVoucher.data.this_vue = this
    const Waves  = require('node-waves');
//    import AxiosApi from '../../../api/axiosApi'
//    const axioxapi = new AxiosApi()
    const _ = require('lodash');
    var platform = require('platform');
    import Vue from 'vue'

    export default {

        data() {
            return {
                ClassisActive: true,
                authing:0,
                conn_status:false,
                conn_info:'失去连接',
                socket_response:'',
                app_rules:[],
                test:[1,2,3],
                bind_apps:[],
                top_level:0,
                level_color:[
                    'rgba(0,128,0,0.35)',
                    'rgba(0,0,255,0.35)',
                    'rgba(128,0,128,0.35)',
                    'rgba(255,79,6,0.35)',
                ],
                level_key:'',
                level_pass:'',

                module:'',
                controller:'',
                action:'',
                to:'',
                payload_type:'',
                payload_data:'',

                unique_auth_code:'',
                encryption_key:'',
                access_user:'',
                isbindapps:'',
//                本地存储
                localvoucher_keys:'',
                localStorage_key:'',
                localStorage_value:'',

                danmu_list:[]




            }
        },
        created(){
            console.log('live_ctrl->create')
            LocalVoucher.checkStorageMode()
            LocalVoucher.initEngine()
            this.unique_auth_code = LocalVoucher.getValue('unique_auth_code')
            this.check_status()
                this.re_check_bind()

//            axioxapi.axios.get('//localhost:5001/apps/getAppRule')
//                .then((res) => {
//                    this.app_rules = res.data.data
//
//
//                })
        },
        mounted(){
            var vm = this
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


            this.$root.eventHub.$on('autoInit',function (data) {
                console.log('autoInit')
                vm.socket_init()
                vm.level_key = 'root'
                vm.level_pass = 'zhuotong'
                vm.bind_apps = ['live_video#1@1']

                setTimeout(function () {
                    vm.bind_app_auth()

                },1000)
            })



        },
        beforeUpdate(){
//            while(this.danmu_list.length>13){
//                this.danmu_list.pop()
//            }
        },
        updated(){
            Waves.attach('#app_form', ['waves-block']);


        },
        methods:{
            doStorageAuthCode(){
                LocalVoucher.setKeyValue('unique_auth_code',this.unique_auth_code)
            },
            doStorageSave(){
                LocalVoucher.setKeyValue(this.localStorage_key,this.localStorage_value)
            },
            doStorageGet(){
                this.localStorage_value = LocalVoucher.getValue(this.localStorage_key)
            },
            doStorageGetKeys(){
                this.localvoucher_keys = LocalVoucher.getKeys()
            },
            doStorageClear(){
                console.log('clear=>')
                LocalVoucher.clear()
            },


            doinit(){
                this.module = 'index'
                this.controller = 'apps'
                this.action = 'getAppRule'
                this.payload_type = 'getapps'
                this.payload_data = []
                this.send()

            },
            doinitapps(){
                var vm = this
                console.log('doinitapps')
                this.app_rules = this.socket_response.data.data
                this.app_rules =
                console.log(this.app_rules)
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
                        console.log('change')


                    },
                    onAdd(addedValue, addedText, $addedChoice){
                        console.log('add')
                        console.log(addedValue)
                        console.log(addedText)
                        console.log($addedChoice)
                        vm.dealLevel(addedText,'add')
                    },
                    onRemove(removedValue, removedText, $removedChoice){
                        console.log('remove')
                        console.log(removedValue)
                        console.log(removedText)
                        console.log($removedChoice)
                        vm.dealLevel(removedText,'remove')
                    },
                    onLabelSelect($selectedLabels){
                        console.log('labelSelect')
                    },
                    onShow(){
                        console.log('onShow')
                    },
                    onHide(){
                        console.log('onHide')
                    }



                });
            },
            doCheckBindApps(){
                var vm = this
                console.log('doCheckBindApps')
                if(this.socket_response.data.bind_status)
                {
                  this.encryption_key = this.socket_response.data.bind_status
                  this.access_user = this.socket_response.data.access_user
                  this.isbindapps = this.socket_response.data.isbindapps
                    console.log(this.socket_response.data)
                  LocalVoucher.setKeyValue('encryption_key',this.encryption_key)
                  LocalVoucher.setKeyValue('access_user',JSON.stringify(this.access_user))
                  LocalVoucher.setKeyValue('isbindapps',JSON.stringify(this.isbindapps))
                  this.isbindapps.forEach(function (v,k) {
                      console.log(v)
                      console.log(v.indexOf('live_video'))
                      if(v.indexOf('live_video') >= 0){
                          vm.module = 'index'
                          vm.controller = 'appliveVideo'
                          vm.action = 'getVideo'
                          vm.payload_type = 'getLiveVideo'
                          vm.payload_data = {
                              'encryption_key':vm.encryption_key,
                              'unique_auth_code':vm.unique_auth_code,

                          }
                          vm.send()
                      }
                  })

                }
            },
            /*
            用于远程更新
             */
            updateLiveVideo(){
                this.module = 'index'
                this.controller = 'appliveVideo'
                this.action = 'getVideo'
                this.payload_type = 'getLiveVideo'
                this.payload_data = {
                    'encryption_key':this.encryption_key,
                    'unique_auth_code':this.unique_auth_code,

                }
                this.send()
            },
            pauseOrplay(type=1){

                this.$root.eventHub.$emit('pauseOrplay',type)

            },
            touchCtrl(){
                this.$root.eventHub.$emit('touchCtrl','')
            },
            doCheckLogin(){
                var vm = this
                console.log('doCheckLogin')
                console.log(this.socket_response.data)
                if(this.socket_response.data.status)
                {
                    this.unique_auth_code = LocalVoucher.getValue('unique_auth_code')
                    this.encryption_key = LocalVoucher.getValue('encryption_key')
                    this.access_user = JSON.parse(LocalVoucher.getValue('access_user'))
                    this.isbindapps =  JSON.parse(LocalVoucher.getValue('isbindapps'))
                }
                else{

                    this.encryption_key = ''
                    this.access_user = ''
                    this.isbindapps =  ''
                }
            },
            refreshDanmu(){
                var vm = this


                console.log('refreshDanmu')
                console.log(this.socket_response)


                this.danmu_list.unshift(this.socket_response)

//                this.danmu_list.splice(Math.random() * 10,0,this.socket_response)
                this.$root.eventHub.$emit('toShowDanmuList',this.socket_response)
                console.log('emit->toShowDanmuList->')
                if(this.danmu_list.length>15){

//                    Vue.nextTick(function () {
//                        console.log($('.danmu_list_tr').length)
//                        console.log(vm.danmu_list.length)
//                        console.log($('.danmu_list_tr:last').html())
//                        $('.danmu_list_tr:last').remove()
//                    })
//                    if(vm.danmu_list.length>100){
//                        vm.danmu_list.splice(10,100)
//
//                    }
                    this.danmu_list.splice(16,100)
                    console.log(this.danmu_list.length)
                }
                this.matchMoveSelect(this.socket_response)



//                this.danmu_list = _.sortBy(this.danmu_list, function(item) {
//                    return -item.time;
//                });

//                console.log(this.danmu_list)

            },
            initLiveVideoList(){
                console.log('initLiveVideoList');
                console.log(this.socket_response)
                console.log(this.socket_response.response_data.data);
                this.$root.eventHub.$emit('initLiveVideoList',this.socket_response.response_data.data)
            },
            DanmuDemand(){
                console.log('DanmuDemand')
                console.log(this.socket_response.response_data.data);
                if(this.socket_response.response_data.data.status){
                    this.$root.eventHub.$emit('DanmuDemand',this.socket_response.response_data.data.danmu)
                }
            },
            cancel_auth(){
                this.authing = 0
            },
            bind_app_auth(){
                console.log('bind_app_auth')

                let sysinfo = []

                this.authing = 1
                var params = {
                    apps:this.bind_apps,
                    key:this.level_key,
                    pass:this.level_pass,
                    unique_auth_code:this.unique_auth_code
                }
                this.module = 'index'
                this.controller = 'apps'
                this.action = 'bindapps'
                this.payload_type = 'bind_apps'
                this.payload_data = params
                this.send()
            },
            check_status(){

                if(SOCKET_CLIENT.data.status === '连接正常'){
                    this.conn_info = '保持连接'
                    this.conn_status = true
                }
            },
            re_check_bind(){
                if(SOCKET_CLIENT.data.status === '连接正常' && LocalVoucher.getValue('unique_auth_code') && LocalVoucher.getValue('encryption_key')) {

                    this.module = 'index'
                    this.controller = 'apps'
                    this.action = 'checkLogin'
                    this.payload_type = 'checkLogin'
                    this.payload_data = {
                        'unique_auth_code': LocalVoucher.getValue('unique_auth_code'),
                        'encryption_key': LocalVoucher.getValue('encryption_key')

                    }
                    this.send()
                }
            },
            stop_check_status(){
                window.clearInterval(this.int_check)
            },
            socket_init()  {
                SOCKET_CLIENT.data.this_vue = this

                SOCKET_CLIENT.init(this.unique_auth_code)

            },
            disconnect() {
                SOCKET_CLIENT.data.this_vue = this

                SOCKET_CLIENT.wsClose()
                LocalVoucher.clear()
                LocalVoucher.setKeyValue('unique_auth_code',this.unique_auth_code)
                this.bind_apps = []
                this.authing = ''
                this.level_key = ''
                this.level_pass = ''
            },
            reconnect() {

            },
            send(){

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
                console.log(this.bind_apps)
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
                console.log(this.top_level)

            },
            matchMoveSelect(data){
                console.log('matchMoveSelect')
                console.log(data)
                var reg = /#.+-\d+/
                var match_data = (data.content + "").match(reg)
                console.log(match_data[0])
                if(match_data){
                    var match_arr = match_data[0].split('-')

                    var match_movie = match_arr[0].replace('#','')
                    var match_ticket = parseInt(match_arr[1])

                    console.log(match_movie)
                    console.log(match_ticket)
                    if(match_ticket>=100){
                        this.module = 'index'
                        this.controller = 'user'
                        this.action = 'demandLiveVideo'
                        this.payload_type = 'demandLiveVideo'
                        this.payload_data = {
                            'unique_auth_code': LocalVoucher.getValue('unique_auth_code'),
                            'encryption_key': LocalVoucher.getValue('encryption_key'),
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
        computed:{
            getheadpic:function () {
              return    this.access_user.img?this.access_user.img:'http://truesign-app.oss-cn-beijing.aliyuncs.com/headpic/7dd98d1001e939010096ed5c7dec54e736d1963f.jpg'

            },
            dealDanmuList:function () {
                if(this.danmu_list.length>15){
                    var new_danmu_list = this.danmu_list.slice(0,15)
                    this.danmu_list = []
                    this.danmu_list = new_danmu_list
                }
                return  this.danmu_list
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
    width 95%
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
    width 45%
    height 100%
    position relative
    float left
    #link_server
      padding-bottom 20px
    #app_form

      padding 0
      height 30%
      #select_app
        display inline-block
        padding 0
        margin 20px 0 20px 0
        margin-left 10px
        width 33.5%


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