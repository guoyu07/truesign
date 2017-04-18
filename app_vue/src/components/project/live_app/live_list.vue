<template>
  <div id="live_list">
      <!--<button v-on:click="sort_list">Shuffle</button>-->
      <el-input placeholder="命令" v-model="push_info" >

          <el-button slot="append" icon="search"   v-on:click="push_click"></el-button>
      </el-input>
      <!--<transition-group name="flip-list" tag="div"-->
                        <!--v-bind:css="false"-->
                        <!--v-on:before-enter="beforeEnter"-->
                        <!--v-on:enter="enter"-->
                        <!--v-on:leave="leave">-->
          <!--<li  v-for="item in computedList" v-bind:key="item">-->

              <!--<p style="width: 315px !important; color: white" class="videouri is_select_video" :uri="item.videouri">{{ item.videoname }}</p>-->

          <!--</li>-->
      <!--</transition-group>-->
     <table class="ui celled table video-list-table" style="background-color:rgba(255,251,251,0.06);text-align: left; font-size: 14px ;color: white ">
              <thead>
                  <tr >
                      <th style="width: 60px;background-color:rgba(255,251,251,0.06);color: white;display: none">编号</th>
                      <th style="width: 310px !important;">电影</th>
                      <th>票数</th>
                  </tr>
              </thead>
            <tbody style="padding: 0;margin: 0;height: auto !important;">
                <tr style="border: solid 2px red; ">
                    <td style="display: none;">{{ onplay_item.id }}</td>
                    <td style="width: 300px !important;" class="videouri" :uri="onplay_item.videouri">{{ onplay_item.videoname }}</td>
                    <td>{{ onplay_item.videoticket }}</td>

                </tr>
            </tbody>

              <transition-group name="flip-list" tag="tbody"
                                v-bind:css="false"
                                v-on:before-enter="beforeEnter"
                                v-on:enter="enter"
                                v-on:leave="leave">

                  <tr  v-for="(item,index) in items" v-bind:key="item" :data-index="index">
                      <td style="display: none">{{ item.document_id }}</td>
                      <td style="width: 315px !important;" class="videouri is_select_video" :uri="item.videouri">{{ item.videoname }}</td>
                      <td >{{ item.videoticket }}</td>
                  </tr>
              </transition-group>


      </table>
      <div id="topshow">
          <div style="position: fixed;left: 50%;top:10px;color: white; font-size: 15px;width: 1200px;height: 50px;transform: translateX(-50%);font-weight: 800">
              <transition-group name="flip-list" tag="div"
                                v-bind:css="false"
                                v-on:before-enter="beforeEnter"
                                v-on:enter="enter"
                                v-on:leave="leave">

                  <li  v-for="(item,index) in tips" v-bind:key="item" :data-index="index">
                        {{ item }}
                  </li>
              </transition-group>
          </div>
          <div style="position: fixed;right: 0;top:0;">
              <table class="ui celled table danmu-list-table" style="background-color:rgba(255,251,251,0.06);text-align: left; font-size: 14px ;color: white ;
              width: 360px !important;
                height: 120px !important;
    ">
                  <thead>
                  <tr>
                      <!--<th ></th>-->
                      <!--<th style="width: 300px !important;"></th>-->
                      <!--<th >时间戳</th>-->
                      <!--<th >来源</th>-->
                  </tr>
                  </thead>

                  <transition-group name="danmu-list" tag="tbody" style="height: 120px !important;">
                      <!--v-bind:css="false"-->
                      <!--v-on:before-enter="beforeEnter"-->
                      <!--v-on:enter="enter"-->
                      <!--v-on:leave="leave"-->

                      <!--<tr  v-for="item in items" v-bind:key="item.filename">-->
                      <tr class="danmu-list-item"  v-for="(item,index) in danmu_list" v-bind:key="item" :data-index="index">
                          <td >

                              {{ item.nickname }}</td>
                          <td style="width: 300px !important;">{{ item.content }}</td>
                          <!--<td >{{ item.time }}</td>-->
                          <!--<td >{{ item.source }}</td>-->
                      </tr>
                  </transition-group>
              </table>
          </div>
      </div>
      <div class="ball-container hidden" >         </div>


  </div>
</template>
<script>
    import LocalVoucher from '../../../api/LocalVoucher'
    import Vue from 'vue'

    const _ = require('lodash');
    import Velocity from 'velocity-animate'
    var done = ''
//    import AxiosApi from '../../../api/axiosApi'
//    const axioxapi = new AxiosApi()
    export default {
        data() {
            return {
                items:[],
                item_uri:'',
                onplay_item:{
                    videoname:'绝密飞行',
                    videoticket:0,
                    document_id:0,
                    videouri:false,
                },
                push_info:'',
                carry_info:[],
                show: false,
                playdone:false,
                danmu_list:[],
                pre_tips:[
                    '点播方法 #电影名-电影币 目前每人5000币，每次点播电影币需大于100，电影名只要能在列表中区分出来(不可以包含【续、-】等字符)',
                    '讨论群 136273935 有好看的电影可以在群里或者弹幕里提出，会及时下载',
                    '讨论群 136273935 群里有偿提供相关技术，本直播二十四小时无需人值守【可以在bilibili，斗鱼，战旗，熊猫，全民等等类网站接入】',
                    '热衷于人工智能和机器的相关影视，求推荐'
                ],
                tips:[]

            }
        },
        props: {

        },
        created(){
            var vm = this
//            for(let i=0; i<49; i++){
//                vm.items.push(
//                    {
//                        'nickname':'123',
//                        'videoticket':'123',
//                        'videoname':'123',
//                    }
//                )
//            }

//            axioxapi.axios.get('//localhost:5001/livevideo/getvideolist')
//                .then((res) => {
//                    this.items = res.data
//
//                })
            if(LocalVoucher.getValue('livevideolist')){
                this.items = JSON.parse(LocalVoucher.getValue('livevideolist'))
            }

            this.$root.eventHub.$on('initLiveVideoList', function (data) {
                console.log('live_list->initLiveVideoList')
                console.log(data)

                console.log(vm.items);
                console.log(typeof vm.items);
                if(data.data){
                    vm.items = data.data
                    LocalVoucher.setKeyValue('livevideolist',JSON.stringify(data.data))
                }
            })
        },
        mounted(){
            var vm = this
            var i = 0
            setInterval(function () {
                console.log('更新提示')
                if(i===vm.pre_tips.length){
                    i = 0
                }


                vm.tips.push(vm.pre_tips[i])
                if(vm.tips.length > 3){
                    vm.tips.shift()
                }
                i++
            },5000)
//            for(let i=0; i<49; i++){
//                vm.items.push(
//                    {
//                        'nickname':'123',
//                        'videoticket':'123',
//                        'videoname':'123',
//                    }
//                )
//            }
            $('input').css('color','#6cf2ff')

            $('input').css('background-color','transparent')
            $('input').focus(function () {
                $('input').css('border-color','#6cf2ff')
            })

            $('input').blur(function () {
                $('input').css('border-color','#BFCBD9')
            })
            $('.el-input-group__append').css('background-color','rgba(255, 255, 255, 0)')
            $('button').css('background-color','rgba(255, 255, 255, 0)')
            $('.el-input-group__append').hover(function () {
                $('.el-input-group__append,.el-input-group__append>button').css('border-color','#6cf2ff')
            },function () {
                $('.el-input-group__append,.el-input-group__append>button').css('border-color','#BFCBD9')
            })

            this.$root.eventHub.$on('DanmuDemand', function (data) {
                console.log('live_list->DanmuDemand')
                console.log(data)
                vm.push_click(data.nickname,data.match_movie,data.match_ticket)

            })

            this.$root.eventHub.$on('getNextPlay', function (data) {
                console.log('live_list->getNextPlay')
                console.log(data)
                vm.playdone = true
                vm.changeplay()

            })

            this.$root.eventHub.$on('toShowDanmuList',function (data) {

                console.log('toShowDanmuList->refreshDanmu')


                vm.danmu_list.unshift(data)
                if(vm.danmu_list.length>8){


                    vm.danmu_list.splice(9,100)

                }
            })


        },
        updated(){
            $('tr').removeClass('onplay')
            $('tr').removeClass('nextplay')
            $('tr:eq(1)').addClass('onplay')
            $('tr:eq(2)').addClass('nextplay')

        },
        methods:{
            changeplay:function () {
                let vm = this
                Vue.nextTick(function () {


                    if(vm.playdone){
                        console.log(vm.items[0])
                        var preplay = {
                            document_id:$.trim($('td:eq(3)').html()),
                            videoname:$.trim($('td:eq(4)').html()),
                            videouri:$('td:eq(4)').attr('uri'),
                            videoticket:$.trim($('td:eq(5)').html()),
                        }

                        vm.onplay_item = preplay
                        vm.items[0].videoticket = -1;
                        vm.playdone =  false;
                        vm.sort_list();
                        vm.fnSendNextPlay(preplay)

                    }
                })
            },
            fnSendNextPlay:function (data) {
                console.log('fnSendNextPlay')
                this.$root.eventHub.$emit('sendNextPlay',data)
            },
            push_click: function (target_user=1,target_movie,target_ticket) {
                if(this.push_info.indexOf('test') !== -1){
                    this.push_test()

                }
                else{
                    target_movie = this.push_info?this.push_info:target_movie
                    if(this.push_info){
                        target_user = 'root'
                        target_ticket = 1000
                    }
                    var select_els =  $('.is_select_video:contains('+target_movie+')')

                    if(select_els !== undefined){
                        if(select_els.length === 1){

                            let el_rect = select_els[0].getBoundingClientRect()
                            if($('.ball-container').length  > 0){
                                $('.ball-container').css('height',el_rect.height)
                                var clone_ball = $('.ball-container:first').clone()
                                var target_ball_id =  Date.parse(new Date())+'-'+Math.random()+'-'+Math.random()
                                clone_ball.attr('target_ball_id',target_ball_id)
                                clone_ball.attr('target_move',target_movie)
                                clone_ball.attr('target_user',target_user)
                                clone_ball.attr('target_ticket',target_ticket)
                                clone_ball.append('<p style="text-align: center;font-size: 25px;line-height: 40px">'+target_user+' : '+target_ticket+'</p>')
                                clone_ball.insertAfter($('.ball-container:first'))
                                var ball_target = $('div[target_ball_id="'+target_ball_id+'"]')
                                ball_target.removeClass('hidden')
                                var vm =this
                                ball_target.animate({top:el_rect.top}).animate({
                                    top:el_rect.top
                                })
                                    .animate({
                                        right:window.innerWidth-el_rect.width-30,
                                        opacity:0.0
                                    },{
                                        duration:5500,
                                        complete:function () {
                                            vm.items.forEach(function (item,k) {

                                                if(item.videoname.indexOf(target_movie) !== -1){
                                                    vm.items[k].videoticket = parseInt(vm.items[k].videoticket) + parseInt(target_ticket)
                                                }
                                            })
                                            this.remove()
                                            vm.sort_list(true)
                                        }
                                    });
                            }
                        }
                    }
                }


            },
            push_test:function () {
                var test_info = this.push_info.replace('test:','').split(',')

                this.push_info = ''
                var vm = this
                test_info.forEach(function(v,k){
                    vm.push_click('hi',v,'100')
                })


            },
            sort_list: function (changeplay=false) {

                this.items = _.sortBy(this.items, function(item) {
                    return -item.videoticket;
                });
                if(changeplay){
                    this.changeplay()
                }

            },
            shuffle: function () {
                this.items = _.shuffle(this.items)
            },
            beforeEnter(el) {
                console.log('beforeEnter')
                el.style.opacity = 0
                el.style.height = 0
            },
            enter(el) {
                console.log('enter')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 1, height: '1.6em' },
                        { complete: done }
                    )
                }, delay)
            },
            afterEnter(el) {
                console.log('afterenter')
            },
            enterCancelled(el) {
                console.log('enterCancelled')
            },
            beforeLeave(el){
                console.log('befaoreLeave')
            },
            leave(el){
                console.log('Leave')
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
                console.log('afterLeave')
            },
            leaveCancelled(el){
                console.log('leaveCancelled')
            },




        },
        computed:{
            computedList: function () {
                var vm = this
                if(this.push_info.indexOf('test') === -1){
                    return this.items.filter(function (item) {
                        return item.videoname.toLowerCase().indexOf(vm.push_info.toLowerCase()) !== -1
                    })
                }
                else{
                    return this.items
                }

            }
        },
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

    .video-list-table thead tr {
        display:block;
        background :transparent !important
    }
    .video-list-table tbody {
        display: block;
        height:700px !important
        overflow: auto !important


    }
    .video-list-table th {
        width:160px;
        background transparent !important
        color white !important
    }
    .video-list-table td {
        width:160px;
    }

    .flip-list-move {
        transition: transform 1s;
    }

    .onplay,th{
        background-color rgba(163, 155, 161, 0.4)
        backdrop-filter blur(10px)
    }
    .nextplay,th{
        background-color rgba(163, 155, 161, 0.4)
        backdrop-filter blur(10px)
    }
    .onplay:after {
        position absolute
        left 250px
        margin-top 12px
        border solid 2px #6cf2ff
        padding 0 3px 0 3px

        background-color rgba(255, 254, 252, 0.0)
        backdrop-filter blur(10px)
        color #6cf2ff
        font-size 12px
        content: "播放中"
    }
    .nextplay:after
        position absolute
        left 250px
        margin-top 10px
        border solid 2px #6cf2ff
        padding 0 3px 0 3px

        background-color rgba(255, 254, 252, 0.0)
        backdrop-filter blur(10px)
        color #6cf2ff
        font-size 12px
        content: "待定中"
    .ball-container
        background-color:rgba(255, 254, 252, 0.2);
        backdrop-filter:blur(5px);
        border: rgba(119, 118, 117, 0.12) 2px solid;
        color: white;
        position fixed
        right -100px
        top 0px
        z-index 4
        width 350px
        height 30px
        line-height 30px
        overflow hidden
        border-radius: 30px 0 0 30px;


</style>