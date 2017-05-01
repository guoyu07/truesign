<template>
    <div >

        <div id="chatapp" @mousedown="startdragthis($event)" @mouseup="overdragthis(false,$event)" :style="{top:chat_top}" @click="clickthis($event)" @mouseover="overthis($event)" @mouseout="outthis($event)">
        <div v-if="chat_box_mode===0">
            <div id="msg-div">
                <transition-group id="chat-msg" class="chat-msg-min" name="chat-msg" tag="div"
                                  v-on:before-enter="beforeEnter"
                                  v-on:enter="enter"
                                  v-on:after-enter="afterEnter"
                                  v-on:enter-cancelled="enterCancelled"
                                  v-on:leave="leave"

                >
                    <li class="msg_item" v-for="(item,index) in msg_list" :key="item"
                        style="height: 60px">
                        <!--<a :href="item.path" >{{item.name}}</a>-->
                        <div class="item_main" style="position: relative">

                            <div class="msg_headpic_info" :style="{float: item.type==='send'?'right':'left',display: 'inline-block'}" >
                                <img  :src="item.website_user.headpic" width="50px" height="50px" :style="{float:item.type==='send'?'right':'left'}">

                            </div>

                            <div class=" msg_content_bg" style="position: absolute;z-index:9">
                                <div class="about_info" :style="{marginLeft:item.type==='send'?'195px':'60px'}">
                                    {{item.website_user.username}}
                                </div>
                                <div class="about_info" :style="{marginLeft:item.type==='send'?'6px':'229px',width:'150px',marginTop:'38px'}">
                                    {{item.website_user.ip}}
                                </div>
                                <div class="about_info" :style="{marginLeft:item.type==='send'?'24px':'198px',width:'163px'}">
                                    {{item.timestamp}}
                                </div>
                                <!--<effectlogo id="effectlogo"   :logo_width="'36'" logo_pos='center' :style="{position: 'absolute',zIndex:15,marginLeft:item.type==='send'?'-120px':'188px'}"></effectlogo>-->
                            </div>
                            <div class="msg_content" :style="{zIndex:10,textAlign: 'center',marginLeft:item.type==='send'?'6px':'60px',lineHeight:'50px',width:'310px'}" >
                                {{ item.msg }}
                   </div>
                        </div>

                    </li>
                </transition-group>
                <div id="send-chat" style="width: 100%;background-color: transparent;height: 10%;position: absolute;bottom: 0;border-top:2px solid white">
                    <!--<vue-editor v-model="msg" style="position: absolute;z-index:10;background-color: whitesmoke;width: 100%;height: 15%;overflow: auto"  :editorToolbar="customToolbar"-->

                    <!--&gt;</vue-editor>-->
                    <input type="text" v-model="msg" style="position: absolute;box-shadow: 0 0 10px #57DCDF;width: 80%;height:28px;bottom:0">
                    <input type="button" value="发送" @click="send_msg" style="position: absolute;right: 0;bottom:0;height:28px;box-shadow: 0 0 10px #57DCDF;width: 20%;z-index:50">
                </div>

            </div>
            <div id="msg-ctrl-bar">
                <div  class="msg-ctrl-bar-item-bar" style="">
                <span style="padding: 5px 18px;color: #55EEEF;line-height: 40px;cursor: pointer;border: 2px solid white" @click="changeMode">
                    <i v-if="chat_box_mode === 1">普通模式</i>
                    <i v-else="chat_box_mode === 0">精简模式</i>
                </span>
                </div>
                <div id="show_select_bar" class="msg-ctrl-bar-item-bar" style="height: 94.5%">
                    <div id="select_user_list_bar" style="height: 40px !important;">
                        <div id="select_app" class="ui loading fluid multiple search selection dropdown"
                             style="background: transparent; display: inline-block;position: static;box-shadow: 0 0 10px #57DCDF" disabled="">
                            <input type="hidden" name="select_app" value="">
                            <!--<i class="dropdown icon"></i>-->
                            <!--<input class="search_better" style="padding: 0; width: 100%;height: 100%; position: static; border: none !important;">-->
                            <div class="search" ></div>
                            <div class="default text"></div>
                            <div class="menu" style="background: transparent !important;">

                                <div class="item"  v-for="(item,index) in chat_user_list" style="color: white !important; background: transparent !important;"
                                     :data-value="item['name']"
                                     :data-level="item['website_level']"><i>{{ item['name'] }}#{{item['website_level']}}</i></div>


                            </div>
                        </div>
                    </div>
                    <div id="show_user_list_bar">
                        <ol>
                            <li>总人数 1 </li>
                            <li><label>①</label> 1 </li>
                            <li><label>②</label> 1 </li>
                            <li><label>③</label> 1 </li>
                            <li><label>④</label> 1 </li>
                            <li><label>⑤</label> 1 </li>
                            <li><label>⑥</label> 1 </li>
                            <li><label>⑦</label> 1 </li>
                            <li><label>⑧</label> 1 </li>
                            <li><label>⑨</label> 1 </li>
                        </ol>
                    </div>
                </div>
                <div class="lock_top" @click="lock_top_bar" style="vertical-align: middle;text-align: center;padding-top: 10px">
                    <img v-if="lock_top" src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo/timg.jpg" width="50px" height="50px"></img>
                    <img v-else="lock_top" src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo/u=363288239,1143495983&fm=23&gp=0.jpg" width="50px" height="50px"></img>
                </div>
            </div>
        </div>
        <div v-if="chat_box_mode===1">
            <div id="msg-div">
                <transition-group id="chat-msg"  name="chat-msg" tag="div"
                                  v-on:before-enter="beforeEnter"
                                  v-on:enter="enter"
                                  v-on:after-enter="afterEnter"
                                  v-on:enter-cancelled="enterCancelled"
                                  v-on:leave="leave"
                                  v-bind:css="false"
                >

                    <li class="msg_item" v-for="(item,index) in msg_list" :key="item"
                        style="position: relative;overflow: visible;margin-top: 30px" >
                        <div class="item_main" style="position: relative"  >

                            <div class="msg_headpic_info" :style="{float: item.type==='send'?'right':'left',display: 'inline-block'}" >
                                <img  :src="item.website_user.headpic" width="50px" height="50px" :style="{float:item.type==='send'?'right':'left'}">

                            </div>

                            <div class=" msg_content_bg" style="position: relative;z-index:9">
                                <div v-if="item.type==='send'">
                                    <div class="about_info" style="right:8%">
                                        {{item.website_user.username}}
                                    </div>
                                    <div class="about_info" style="right:75%;">
                                        {{item.website_user.ip}}
                                    </div>
                                    <div class="about_info" style="right:40%;width: 160px">
                                        {{item.timestamp}}
                                    </div>
                                </div>
                                <div v-if="item.type!=='send'">
                                    <div class="about_info" style="left:8%">
                                        {{item.website_user.username}}
                                    </div>
                                    <div class="about_info" style="left:70%;">
                                        {{item.website_user.ip}}
                                    </div>
                                    <div class="about_info" style="left:30%;width: 160px">
                                        {{item.timestamp}}
                                    </div>
                                </div>

                                <!--&lt;!&ndash;<effectlogo id="effectlogo"   :logo_width="'36'" logo_pos='center' :style="{position: 'absolute',zIndex:15,marginLeft:item.type==='send'?'-120px':'188px'}"></effectlogo>&ndash;&gt;-->
                            </div>

                            <div  v-if="item.type==='send'" class="msg_content "
                                 :style="{textAlign:'center',position: 'relative',zIndex:100,right:'-5px',lineHeight:'11px',paddingTop:'20px',paddingBottom:'20px',marginBottom:'20px',overflow:'hidden'}" v-html="item.msg">

                            </div>

                            <div   v-if="item.type!=='send'" class="msg_content "
                                   :style="{textAlign:'center',position: 'relative',zIndex:100,marginLeft:item.type==='send'?'6px':'90px',lineHeight:'11px',paddingTop:'20px',paddingBottom:'20px',marginBottom:'20px',overflow:'hidden'}" v-html="item.msg">
                            </div>

                        </div>

                    </li>
                </transition-group>
                <div class="handler"
                     style="height: 20px;width: 100%;"
                     data-type="chat-msg-pos-ctrl-bar"
                     @mousedown="startdragthis($event)" @mouseup="overdragthis(false,$event)"  >
                    <input type="button" @click="send_msg" v-model="btn_send_info"
                           style="padding: 5px 20px;
                           border: 2px solid white ;text-align: center;position: absolute;right: 10px;margin-top: 20px;background-color: rgba(87,220,223,0.25) !important;z-index:100" >

                </div>

                <div id="send-chat" class="send-chat" :style="{height:editor_height+40+'px'}" >

                    <!--<VueEditor v-model="msg"></VueEditor>-->
                    <wangeditor :editorShow_height="editor_height+''"></wangeditor>
                </div>

            </div>
            <div id="msg-ctrl-bar" style="">
                <div  class="msg-ctrl-bar-item-bar" >
                    <span style="padding: 5px 18px;color: #55EEEF;line-height: 40px;cursor: pointer;border: 2px solid white" @click="changeMode">
                        <i v-if="chat_box_mode === 1">普通模式</i>
                        <i v-else="chat_box_mode === 0">精简模式</i>
                    </span>
                </div>
                <div id="show_select_bar" class="msg-ctrl-bar-item-bar" style="height: 94.5%">
                    <div id="select_user_list_bar" style="height: 40px !important;">
                        <div id="select_app" class="ui loading fluid multiple search selection dropdown"
                             style="background: transparent; display: inline-block;position: static;box-shadow: 0 0 10px #57DCDF" disabled="">
                            <input type="hidden" name="select_app" value="">
                            <!--<i class="dropdown icon"></i>-->
                            <!--<input class="search_better" style="padding: 0; width: 100%;height: 100%; position: static; border: none !important;">-->
                            <div class="search" ></div>
                            <div class="default text"></div>
                            <div class="menu" style="background: transparent !important;">

                                <div class="item"  v-for="(item,index) in chat_user_list" style="color: white !important; background: transparent !important;"
                                     :data-value="item['name']"
                                     :data-level="item['website_level']"><i>{{ item['name'] }}#{{item['website_level']}}</i></div>


                            </div>
                        </div>
                    </div>
                    <div id="show_user_list_bar">
                        <ol>
                            <li>总人数 1 </li>
                            <li><label>①</label> 1 </li>
                            <li><label>②</label> 1 </li>
                            <li><label>③</label> 1 </li>
                            <li><label>④</label> 1 </li>
                            <li><label>⑤</label> 1 </li>
                            <li><label>⑥</label> 1 </li>
                            <li><label>⑦</label> 1 </li>
                            <li><label>⑧</label> 1 </li>
                            <li><label>⑨</label> 1 </li>
                        </ol>
                    </div>
                </div>
                <div class="lock_top" @click="lock_top_bar" style="vertical-align: middle;text-align: center;padding-top: 10px">
                    <img v-if="lock_top" src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo/timg.jpg" width="50px" height="50px"></img>
                    <img v-else="lock_top" src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo/u=363288239,1143495983&fm=23&gp=0.jpg" width="50px" height="50px"></img>
                </div>
            </div>
        </div>



        </div>
    </div>
</template>



<script>
    import { VueEditor } from 'vue2-editor'
    import wangeditor from '../../../tools/wangeditor.vue'
    import Velocity from 'velocity-animate'
    import Vue from 'vue'
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../../../loading/effect_logo.vue'
    import { analysis_socket_response } from '../../../../api/lib/helper/dataAnalysis'
    import moment from 'moment'

    export default {
        data() {
            return{
                lock_top:false,
                btn_send_info:'提交',
                base_editor_height:115,
                editor_height:115,
                chat_top:'96%',
                lock:false,
                top_lock:true,
                msg_text:'',
                msg:'',
                msg_list:[

                ],
                customToolbar: [
                    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                    [{ 'color': [] }],          // dropdown with defaults from theme
                    [{ 'align': [] }],
                ],
                drag_param:{
                    pos_pageY:0,
                    chat_msg_height:0,

                    //
                    currentDisTance : 20,
                    resizeMode : 0,
                    isResize : false,
                    isDrag: false,
                    isStartResize : false,
                    currentDisX:'',
                    currentDisY:'',
                    offsetWidth:'',
                    pull_pointX:'',

                },
                chat_box_mode:0,
                chat_box_param:{
                    mini_width:550,
                },
                chat_user_list:[
                    {
                        name:1,
                        website_level:1
                    },
                    {
                        name:2,
                        website_level:2
                    },
                    {
                        name:3,
                        website_level:3
                    },
                ]

            }
        },

        computed: {
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ])
        },
        mounted(){
            var vm = this
            this.$root.eventHub.$on('editor_content',function (data) {
                //console.log('editor_content',data)
                vm.msg = data.html
                vm.msg = data.html
                vm.msg_text = data.text
            })
            $(document).mouseup(function() {
                vm.overdragthis(true,false)
                vm.drag_param.pos_pageY = 0

            });
            this.doinitDropDown()


            this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
                vm.changeModeShow(vm.chat_box_mode)
            })


            this.$root.eventHub.$on('socket_response',function (data) {
                var socket_response = analysis_socket_response(data)

                if (socket_response.response_type === 'c2c_msg') {
                    console.log('c2c_msg',socket_response.base_response)
                    if(socket_response.base_response.relation.from.fd === vm.website.socket_id){
                        socket_response.base_response.response.type='send'
                    }
                    socket_response.base_response.response.timestamp = moment(socket_response.base_response.response.timestamp).format('YYYY-MM-DD HH:mm:ss');
                    vm.msg_list.push(socket_response.base_response.response)
                    Vue.nextTick(function () {
                        $('#chat-msg')[0].scrollTop = $('#chat-msg')[0].scrollHeight

                    })
                }
            })

        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            changeModeShow(mode=0){
                if(mode===1){
                    let $chat_box_target = $('#chatapp')
                    let page_width = window.innerWidth
                    if(page_width<900){
                        page_width=900
                    }
                    var chat_box_width = $chat_box_target.width()
//                    var show_be_left = page_width-chat_box_width
                    $chat_box_target.css('top','2%')
                    $chat_box_target.css('left','10px')
                    $chat_box_target.width(page_width-10)
                    $('#msg-div').width(page_width-15-150)
                    $('#msg-ctrl-bar').css('left',page_width-15-150+'px')
                    $('#msg-ctrl-bar').css('width','150px')
                    this.lock = true
//                    Vue.nextTick(function () {
//                        $('#quill-container').height(112)
//                        $('.send-chat').height(150)
//
//                    })

                }
                else{
                    let $chat_box_target = $('#chatapp')
                    let page_width = window.innerWidth
                    var show_be_left = page_width-550
                    $chat_box_target.css('left',show_be_left+'px')
                    $chat_box_target.width(550)
                    $('#msg-div').width(400)
                    $('#msg-ctrl-bar').css('left','400px')
                    this.lock = false
                }
                Vue.nextTick(function () {
                    $('#chat-msg')[0].scrollTop = $('#chat-msg')[0].scrollHeight

                })
            },
            changeMode(){
                this.chat_box_mode = (this.chat_box_mode===0)?1:0
                if(this.chat_box_mode === 1){
                    this.changeModeShow(1)
                }
                else{
                    this.changeModeShow(0)

                }
            },
            doinitDropDown(){
                var vm = this
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
                    },
                    onRemove(removedValue, removedText, $removedChoice){
                        //console.log('remove')
                        //console.log(removedValue)
                        //console.log(removedText)
                        //console.log($removedChoice)
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
            startdragthis(e){
                //console.log('startdragthis')
                var vm =this
                var $target = $(e.currentTarget)
                if($target.attr('data-type') === 'chat-msg-pos-ctrl-bar'){
                    var me = $target
                    var pos_pageY = e.pageY
                    var chat_msg_height = $('#chat-msg').height()
                    this.drag_param.pos_pageY = pos_pageY
                    this.drag_param.chat_msg_height = chat_msg_height
                    $(document).mousemove(function (e){
                        if($target.attr('data-type') === 'chat-msg-pos-ctrl-bar'){
                            if(vm.drag_param.pos_pageY){
                                var lt = e.pageY - vm.drag_param.pos_pageY;
                                //console.log('lt',lt)
                                $('#chat-msg').height(vm.drag_param.chat_msg_height + lt)
                                vm.editor_height = window.innerHeight - $('#chat-msg').height()-100
                            }


                        }
                    });

                }
                else{
                    if(!this.lock){
                        this.drag_param.isDrag = true
                    }
                    if (!this.drag_param.isDrag && !this.drag_param.isResize) return;

                    $target.css("position", "fixed");
                    $target.css("cursor", "move");
                    this.drag_param.currentDisX = e.pageX - $target.offset().left;
                    this.drag_param.currentDisY = e.pageY - $target.offset().top;
                    $(document).mousemove(function (e) {
                        if (vm.drag_param.isDrag && !vm.lock) {
                            $target.css("cursor", "move");

                            let cursorX = e.pageX - vm.drag_param.currentDisX; //+$(this).offset().left;
                            let cursorY = e.pageY - vm.drag_param.currentDisY; //-$(this).offset().top;
                            $target.css("top", cursorY + "px").css("left", cursorX + "px");
                        }
                        else{
                            $target.css("cursor", "default");
                        }
                    })

                }



            },
            overdragthis(n,e){
                //console.log('overdragthis')
                var $target = $(e.currentTarget)
                if($target.attr('data-type') === 'chat-msg-pos-ctrl-bar'){
                    this.drag_param.pos_pageY = 0

                }
                else{
                    if(n){

                        $target.css("cursor", "default");
                    }

                    this.drag_param.isDrag = false
                    this.drag_param.pull_pointX = 0
                }


            },
            mousemove(e){

                //console.log('mousemove')
                var $target = $(e.currentTarget)
                var vm = this
                if($target.attr('data-type') === 'chat-msg-pos-ctrl-bar'){
                    if(vm.drag_param.pos_pageY){
                        var lt = e.pageY - vm.drag_param.pos_pageY;
                        //console.log('lt',lt)
                        $('#chat-msg').height(vm.drag_param.chat_msg_height + lt)
                    }


                }
                else{
                    if (vm.drag_param.isDrag && !vm.lock) {
                        $target.css("cursor", "move");

                        let cursorX = e.pageX - vm.drag_param.currentDisX; //+$(this).offset().left;
                        let cursorY = e.pageY - vm.drag_param.currentDisY; //-$(this).offset().top;
                        $target.css("top", cursorY + "px").css("left", cursorX + "px");
                    }
                    else{
                        $target.css("cursor", "default");
                    }
                }




            },
            send_msg(){
                var vm =this
                var msg = ''
                if(this.chat_box_mode === 1){
                    msg = this.msg
                }
                else{
                    msg = this.msg
                }
//                var msg_item =  {
//                        type:'send',//receive
//                        website_user:{
//                            headpic:'http://truesign-app.oss-cn-beijing.aliyuncs.com/headpic/demo/10.jpg',
//                        },
//                        timestamp:1493001022,
//                        content:msg
//                    }
//                this.msg_list.push(msg_item)

                var params = {
//                    to:this.website.socket_id,
                    to:[],
                    payload_type:'c2c_msg',
                    msg:msg,
                    timestamp:new Date().getTime()
                }

                this.$root.eventHub.$emit('socket_send',params)
//                Vue.nextTick(function () {
//                   $('#chat-msg')[0].scrollTop = $('#chat-msg')[0].scrollHeight
//
//                })
            },
            overthis(){
                this.chat_top = '2%'
            },
            outthis(){
//                if(!this.top_lock){
                if(!this.lock_top){
                    this.chat_top = '98%'

                }
                else{
                    this.chat_top = '2%'
                }
//
//                }
            },
            lock_top_bar(){
                if(this.lock_top){
                    this.lock_top = false
                }
                else{
                    this.lock_top = true
                }
            },
            clickthis(){
//                if(this.top_lock === false){
//                    this.top_lock = true
//                }
//                else{
//                    this.top_lock = false
//                }
            },
            beforeEnter(el) {
                ////console.log('beforeEnter')
                el.style.opacity = 0
//                el.style.height = 0
            },
            enter(el,done) {
                ////console.log('enter')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
//                        { opacity: 1, height: '80px' },
                        { opacity: 1 },
                        { complete: done }
                    )
                }, delay)
            },
            afterEnter(el) {
                ////console.log('afterenter')

            },
            enterCancelled(el) {
                ////console.log('enterCancelled')
            },
            beforeLeave(el){
                ////console.log('befaoreLeave')
            },
            leave(el,done){
                ////console.log('Leave')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 0, height: 0 },
                        { complete: done }
                    )
                }, delay)
            },
            afterLeave(el){
                ////console.log('afterLeave')
            },
            leaveCancelled(el){
                ////console.log('leaveCancelled')
            },
        },
        components:{
            VueEditor,
            effectlogo,
            wangeditor
        },
        watch:{
            editor_height(val){
                //console.log('lt change')
                $('#quill-container').height(this.editor_height-10)
            }
        }


    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
#wangeditor p{
    line-height 1.4
}

div.handler {

    cursor: row-resize;
    background-color: rgba(147, 197, 255, 0.36);

}

li {
    list-style-type:none;
    font-size 12px
    color white
    font-weight 400
}
#show_user_list_bar
    margin 50px 0px
    text-align left
#show_user_list_bar  li

    height 30px;
    font-weight 800
    font-size 16px
#show_user_list_bar  li label

    text-align left
    width 40px
#chatapp
    background-color rgba(35, 35, 35, 0.95)
    position absolute
    right 5px
    min-width 300px
    width 550px
    height 96%
    top 10%
    box-shadow: 0 0 10px #fffefd
    transition top 1.1s
    #msg-div
        position absolute
        width 400px;
        box-shadow: 0 0 10px #57DCDF
        height 100%
        #chat-msg
            overflow auto
            height 90%
            .msg_item
                overflow hidden
                width 100%
                color:white
                .item_main
                    margin-top 5px
                    width 94%
                    margin-left 3%
                    height auto
                    .msg_headpic_info
                        position relative
                        z-index 11
                        height 100%
                        background-color rgba(245, 245, 245, 0.26)
                        width 50px
                        display inline-block
                        img
                            margin-top 0
                        .img_title
                            line-height 16px
                            width 50px
                    .msg_content_bg
                        line-height 11px
                        font-size 11px
                        width 100%
                        height 100%
                        .about_info
                            position: absolute;width: 120px;height: 15px;
                            background-color: rgba(87, 220, 223, 0.0);line-height: 12px;text-align: center;
                            color #55eeef
                            text-shadow:0 0 5px #fff;
                            /*border-left 2px solid white*/
                            /*border-right 2px solid white*/
                    .msg_content
                        z-index 11
                        line-height: 1.4;
                        font-size: 100%;
                        line-height: 1.4;
                        width 92%
                        /*text-align left*/
                        /*border-top 10px solid whitesmoke*/
                        /*border-bottom 10px solid whitesmoke*/
                        background-color rgba(87, 220, 223, 0.17)
                        box-shadow: 0 0 10px #57DCDF;
                    .big_msg_content
                        line-height normal
                        padding-top 15px
                        height auto

        .chat-msg-min
            min-height 90%
    .send-chat
        width: 100%;background-color: whitesmoke;height: 200px;border-top:2px solid white; border: none
    #msg-ctrl-bar
        position absolute
        left:400px
        width 150px
        height 90%
        background-color transparent
        .msg-ctrl-bar-item-bar
            width 100%
            height 40px
            box-shadow: 0 0 10px #57DCDF;
            text-align center

</style>
