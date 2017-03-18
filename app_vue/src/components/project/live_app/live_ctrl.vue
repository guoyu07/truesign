<template>
  <div >
    <div  id="live_ctrl_terminal">
      <div id="ctrl_conn">
        <div id="link_server" style="box-shadow: 0 0 10px #57DCDF">
          <div  @click="socket_init" class="ctrl_cls init_conn">连接伺服器</div>
          <div  @click="disconnect" class="ctrl_cls init_conn" style="background-color: rgba(231,129,83,0.52)">断开连接伺服器</div>
          <transition name="slide-fade-down" mode="out-in">
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
        <div id="app_form" style="box-shadow: 0 0 10px #57DCDF">

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
            <div class="menu">

                <div class="item"  v-for="app in app_rules" style="color: white !important;"
                     :data-value="app['appname']"
                     :data-id="app['document_id']"
                     :app-level="app['applevel']">{{ app['appname'] }}#{{app['document_id']}}@{{ app['applevel']}}</i></div>


            </div>
          </div>
          <transition name="slide-fade-down" mode="out-in">
            <form id="bind_app" style=" " action="javascript:return false" v-if="top_level>0">
              <div class="app_level" >{{ top_level }}</div>
              <input type="button" class="cancel_auth" value="取消鉴权" v-if="authing" @click="cancel_auth"></input>
              <div class="ui icon input loading forminput">
                <input type="text" id="level_key" v-model="level_key" placeholder="输入级别key">
                <i class="search icon"></i>
              </div>
              <div class="ui icon input loading forminput">
                <input type="text" id="level_pass" v-model="level_pass" placeholder="输入级别encode">
                <i class="search icon"></i>
              </div>
              <button @click="bind_app_auth" v-if="level_key && level_pass" :class="{ui:ClassisActive, inverted:ClassisActive, teal:ClassisActive, basic:ClassisActive,
               button:ClassisActive,loading:authing,    forminput:ClassisActive}"  style="margin-top: 10px">Authentication</button>
            </form>
          </transition>
        </div>


      </div>
      <div id="ctrl_show">
        <div class="test1 test2">
          test
        </div>
      </div>
      <div id="cut_line"></div>
    </div>
  </div>
</template>
<script>
    import SOCKET_CLIENT from '../../../api/SOCKET_CLIENT'
    SOCKET_CLIENT.data.this_vue = this
    const Waves  = require('node-waves');
    import AxiosApi from '../../../api/axiosApi'
    const axioxapi = new AxiosApi()
    const _ = require('lodash');

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


            }
        },
        created(){
            this.check_status()

            axioxapi.axios.get('//localhost:5001/apps/getAppRule')
                .then((res) => {
                    this.app_rules = res.data.data


                })
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
            $('.search').css('color','#FFFFFF')



            var config = {
                // How long Waves effect duration
                // when it's clicked (in milliseconds)
                duration: 1500,

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




        },
        methods:{
            doinit(){
                this.module = 'index'
                this.controller = 'apps'
                this.action = 'index'
                this.payload_type = 'seach'
                this.send()


            },
            cancel_auth(){
                this.authing = 0
            },
            bind_app_auth(){
                this.authing = 1
                var params = {
                    apps:this.bind_apps,
                    key:this.level_key,
                    pass:this.level_pass
                }
                this.module = 'index'
                this.controller = 'index'
                this.action = 'index'
                this.payload_type = 'bind_apps'
                this.payload_data = params
                this.send()
            },
            check_status(){

                if(SOCKET_CLIENT.data.wSock){
                    this.conn_info = '保持连接'
                    this.conn_status = true
                }
            },
            stop_check_status(){
                window.clearInterval(this.int_check)
            },
            socket_init()  {
                SOCKET_CLIENT.data.this_vue = this

                SOCKET_CLIENT.init()
            },
            disconnect() {
                SOCKET_CLIENT.data.this_vue = this

                SOCKET_CLIENT.wsClose()
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

            }


        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

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
        padding 0
        margin 20px 0 20px 0
        margin-left 10px
        width 33.5%


      #bind_app
        position relative
        margin 20px 0 20px 0

        width: 63%; height: 90%;
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
    top: 0px;
    left 220px
    color: #e79977;
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

  .slide-fade-down-enter-active{
    transition: all 1.5s
  }

  .slide-fade-down-enter{
    transition: all 1.5s
    opacity: 0
    transform translateX(-20%)
  }
  .slide-fade-down-leave-active{
    transition: all 1.5s
    opacity: 0
    transform translateX(20%)
  }
  .slide-fade-down-leave{
    transition: all 1.5s

  }




</style>