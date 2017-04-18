<template>
    <div class="top_router_view" @scroll="scrollthis"  id="fullpageTop" style="overflow-y: scroll">

        <div class="div-scroll-block" v-for="(item,index) in scroll_block_list" :key="item" :data-index="index">
            <img width="100%" height="100%" :src="item.bg" style="text-align: center;position: absolute"/>
            <div class="div-scroll-info" style="position: absolute">
                <div style="margin-top: 2%" v-html="item.info">
                    {{item.info}}
                </div>
           </div>

        </div>


    </div>
</template>



<script>
//    require('fullpage.js/dist/jquery.fullPage.css')
//    require('fullpage.js/vendors/jquery.easings.min.js')
////    require('fullpage.js/vendors/fullpage.parallax.limited.min.js')
//    require('fullpage.js/dist/jquery.fullpage.extensions.min.js')
    require('jquery.scrollto/jquery.scrollto.js')
    import {apihost} from '../../app_config/base_config.js'
    import axios from 'axios'
    import {axios_config} from '../../api/axiosApi'
    const index_api = apihost+'index/'
    export default {
        data(){
            return{
                scroll_block_list:[

                ],
                scrollTop: 0
            }
        },
        mounted(){
//            $('#fullpageTop')[0].scrollTop=200;
            this.refrshindex()

        },
        methods: {
            refrshindex(){
                axios.post(index_api+'getIndex')
                    .then((res) => {
                        console.log(res)
                        this.scroll_block_list = res.data.data
                    })
            },
            scrollthis:function (e) {
                var vm = this
                var divheight = $('.div-scroll-block')[0].scrollHeight
                var scroll_now = $('#fullpageTop')[0].scrollTop
                var target_div_n = parseInt(scroll_now/divheight)

                if(vm.scrollTop%divheight === 0){

                    vm.scrollTop += 1

                    if(scroll_now>vm.scrollTop){
                        console.log('向下滚动')
                        console.log(target_div_n)
                        var tmp_scroll = vm.scrollTop + divheight
                        $('#fullpageTop').animate({
                            scrollTop : tmp_scroll
                        },{
                            duration:1000,
                            complete:function () {
                                setTimeout(function () {
                                    vm.scrollTop += (divheight - 1)
                                    console.log(vm.scrollTop)
                                },300)

                            },

                        })
                        $('.div-scroll-info:eq('+target_div_n+')').animate({
                            width:'50%',

                        },{
                            duration:300,
                            complete:function () {


                            },
                        })
                            .animate({
                                marginTop:'28%',

                            },{
                                duration:300,
                                complete:function () {


                                },
                            })

                    }
                    else{
                        console.log('向上滚动')
                        console.log(target_div_n)
                        tmp_scroll = vm.scrollTop - divheight
                        if(tmp_scroll<0){
                            tmp_scroll = 0
                        }
                        $('#fullpageTop').animate({
                            scrollTop : tmp_scroll
                            },{
                                duration:1000,
                                complete:function () {
                                    setTimeout(function () {
                                        vm.scrollTop -= divheight
                                        vm.scrollTop -= 1
                                        console.log(vm.scrollTop)
                                    },300)
                            },

                        })
                        $('.div-scroll-info:eq('+(target_div_n)+')').animate({
                            marginTop:'10%'
                        },{
                            duration:300,
                            complete:function () {


                            },
                        })
                            .animate({
                                width:'100%'
                            },{
                                duration:300,
                                complete:function () {


                                },
                            })
                    }

                }
            }
        },
        components:{

        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
.div-scroll-block
    transition: all 1.5s;
    width 100%
    height 100%
    .div-scroll-info
        /*transition: all 1.5s;*/

        position absolute
        z-index 5
        width 100%
        right 0
        height 60%
        margin-top 10%
        background: hsla(0,0%,100%,.3);
        text-align center
        /*animation:parallax_up_down 1s 10*/
    .div-scroll-info::before
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        filter: blur(20px);
@keyframes parallax_up_down{
    0%   {
        transform: translateY(0%)

    }
    50% {
        transform: translateY(60%)
    }
    100% {

        transform: translateY(0%)
    }
}

</style>
