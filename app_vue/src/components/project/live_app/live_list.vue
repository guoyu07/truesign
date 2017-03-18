<template>
  <div id="live_list">
      <!--<button v-on:click="sort_list">Shuffle</button>-->

      <el-input placeholder="命令" v-model="push_info" >

          <el-button slot="append" icon="search"   v-on:click="push_click"></el-button>
      </el-input>

     <table class="ui celled table" style="background-color:rgba(255,251,251,0.06);text-align: left; font-size: 14px ;color: white ">
              <thead>
                  <tr >
                      <th style="width: 60px;background-color:rgba(255,251,251,0.06);color: white">编号</th>
                      <th style="background-color:rgba(255,251,251,0.06);color: white">电影</th>
                      <th style="width: 110px;background-color:rgba(255,251,251,0.06);color: white">票数</th>
                  </tr>
              </thead>
            <tbody>
                <tr style="border: solid 2px red; ">
                    <td>{{ onplay_item.id }}</td>
                    <td>{{ onplay_item.filename }}</td>
                    <td>{{ onplay_item.ticket }}</td>

                </tr>
            </tbody>
              <transition-group name="flip-list" tag="tbody"
                                v-bind:css="false"
                                v-on:before-enter="beforeEnter"
                                v-on:enter="enter"
                                v-on:leave="leave">
              <!--<tr  v-for="item in items" v-bind:key="item.filename">-->
              <tr  v-for="item in computedList" v-bind:key="item.filename">
                  <td >

                      {{ item.id }}</td>
                  <td>{{ item.filename }}</td>
                  <td >{{ item.ticket }}</td>
              </tr>
              </transition-group>






          </table>



          <div class="ball-container hidden" >         </div>


  </div>
</template>
<script>
    const _ = require('lodash');
    import Velocity from 'velocity-animate'
    var done = ''
    import AxiosApi from '../../../api/axiosApi'
    const axioxapi = new AxiosApi()
    export default {
        data() {
            return {
                items:[],
                onplay_item:{
                    id:0,
                    filename:'待定',
                    ticket:0
                },
                push_info:'',
                carry_info:[],
                show: false,

            }
        },
        props: {

        },
        created(){

//            axioxapi.axios.get('//localhost:5001/livevideo/getvideolist')
//                .then((res) => {
//                    this.items = res.data
//
//                })
        },
        mounted(){
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


        },
        updated(){
            $('tr').removeClass('onplay')
            $('tr').removeClass('nextplay')
            $('tr:eq(1)').addClass('onplay')
            $('tr:eq(2)').addClass('nextplay')
        },
        methods:{

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
                    var select_els =  $('td:contains('+target_movie+')')
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
                                                if(item.filename.indexOf(target_movie) !== -1){
                                                    vm.items[k].ticket = parseInt(vm.items[k].ticket) + parseInt(target_ticket)
                                                }
                                            })
                                            this.remove()
                                            vm.sort_list()
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
            sort_list: function () {

                this.items = _.sortBy(this.items, function(item) {
                    return -item.ticket;
                });

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
                var delay = el.dataset.index * 150
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
                var delay = el.dataset.index * 150
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
                        return item.filename.toLowerCase().indexOf(vm.push_info.toLowerCase()) !== -1
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
        width 150px
        height 30px
        overflow hidden
        border-radius: 30px 0 0 30px;


</style>