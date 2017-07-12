<template>
    <div id="card" @mouseover="overthis($event)" @mouseleave="leavethis($event)" @click="clickthis" :data-index="dataIndex"  style="display: inline-block;margin: 15px 15px">


                <div class='post-module' >
                    <div class='thumbnail'>
                        <img :src='itemData.img'>
                    </div>
                    <div class='post-content'>
                        <div class='category' style="text-align: center">{{itemData.type}}</div>
                        <h1 class='title'>{{itemData.title}}</h1>
                        <!--<h2 class='sub_title'>童话故事是儿童文学的重要体裁。</h2>-->
                        <p class='description'>{{itemData.note}}</p>
                        <!--<div class='post-meta'>-->
                              <!--<span class='timestamp'>-->
                                <!--<i class='fa fa-clock-o'></i>-->
                                <!--6 分钟前-->
                              <!--</span>-->
                                        <!--<span class='comments'>-->
                                <!--<i class='fa fa-comments'></i>-->
                                <!--<a href='#'>39 回复</a>-->
                              <!--</span>-->
                        <!--</div>-->
                    </div>
                </div>



    </div>

</template>



<script>
    import router_build from '../../app_config/router-build'
    import Velocity from 'velocity-animate'
    import Vue from 'vue'

    export default {
        data() {
            return{

            }
        },
        props:[
            'itemData',
            'dataIndex'
        ],
        mounted(){



        },
        methods:{
            clickthis:function (e) {
                var target = $(e.currentTarget)
                var data_index = target.attr('data-index')
                this.$root.eventHub.$emit('showCardDetail',data_index)
            },
            overthis:function(e){
                var target = $(e.currentTarget)
                target.find('.description').animate({
                    'height':'80px'

                }, {
                    duration: 300,

                })
            },
            leavethis:function(e){

                var target = $(e.currentTarget)
                target.find('.description').stop().animate({
                    'height':'0px'

                }, {
                    duration: 200,

                })
            }
        },
        computed:{

        }

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

    .post-module {
        cursor pointer
        position: relative;
        z-index: 1;
        display: block;
        background: transparent !important;
        width 260px
        height: 310px;
        -webkit-box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.15);
        -moz-box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.15);
        -webkit-transition: all 0.3s linear 0s;
        -moz-transition: all 0.3s linear 0s;
        -ms-transition: all 0.3s linear 0s;
        -o-transition: all 0.3s linear 0s;
        transition: all 0.3s linear 0s;
    }
    .post-module:hover,
    .hover {
        -webkit-box-shadow: 0px 1px 35px 0px rgba(0, 0, 0, 0.3);
        -moz-box-shadow: 0px 1px 35px 0px rgba(0, 0, 0, 0.3);
        box-shadow: 0px 1px 35px 0px rgba(0, 0, 0, 0.3);
    }
    .post-module:hover .thumbnail img,
    .hover .thumbnail img {
        -webkit-transform: scale(1.1);
        -moz-transform: scale(1.1);
        transform: scale(1.1);
        opacity: .6;
    }
    .post-module .thumbnail {
        background: transparent;
        height: 100%;
        overflow: hidden;
    }

    .post-module .thumbnail img {
        display: block;
        -webkit-transition: all 0.3s linear 0s;
        -moz-transition: all 0.3s linear 0s;
        -ms-transition: all 0.3s linear 0s;
        -o-transition: all 0.3s linear 0s;
        transition: all 0.3s linear 0s;
    }
    .post-module .post-content {
        position: absolute;
        bottom: 0;
        background: rgba(255, 255, 255, 0.88);
        width: 100%;
        padding: 30px;
        -webkti-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-transition: all 0.3s cubic-bezier(0.37, 0.75, 0.61, 1.05) 0s;
        -moz-transition: all 0.3s cubic-bezier(0.37, 0.75, 0.61, 1.05) 0s;
        -ms-transition: all 0.3s cubic-bezier(0.37, 0.75, 0.61, 1.05) 0s;
        -o-transition: all 0.3s cubic-bezier(0.37, 0.75, 0.61, 1.05) 0s;
        transition: all 0.3s cubic-bezier(0.37, 0.75, 0.61, 1.05) 0s;
    }
    .post-module .post-content .category {
        position: absolute;
        top: -34px;
        left: 0;
        background: #e74c3c;
        padding: 10px 15px;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .post-module .post-content .title {
        margin: 0;
        padding: 0 0 10px;
        color: #333333;
        font-size: 26px;
        font-weight: 700;
    }
    .post-module .post-content .sub_title {
        margin: 0;
        padding: 0 0 10px 0;
        color: #e74c3c;
        font-size: 16px;
        font-weight: 400;
    }
    .post-module .post-content .description {
        overflow-y  hidden

        color: #666666;
        font-size: 14px;
        line-height: 1.8em;
        height 0
    }
    .post-module .post-content .post-meta {
        margin: 30px 0 0;
        color: #999999;
    }
    .post-module .post-content .post-meta .timestamp {
        margin: 0 16px 0 0;
    }
    .post-module .post-content .post-meta a {
        color: #999999;
        text-decoration: none;
    }
    .hover .post-content .description {
        display: block !important;
        height: auto !important;
        opacity: 1 !important;
    }
    .container {
        margin: 15px 5px 0 5px;
        padding 0 !important
        width 200px
    }
    .container:before,
    .container:after {
        content: '';
        display: block;
        clear: both;
    }
    .container .column {
        -webkti-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .container .column .demo-title {
        color: #D5D6E2;
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .container .info {
        text-align: center;
    }
    .container .info h1 {
        font-size: 24px;
        font-weight: 400;
        color: #333333;
    }
    .container .info span {
        color: #666666;
        font-size: 12px;
    }
    .container .info span a {
        color: #000000;
        text-decoration: none;
    }
    .container .info span .fa {
        color: #e74c3c;
    }
</style>
