<template>
    <div id="navmenu" style="z-index:9999;position: absolute;width: 100%;min-width: 1200px;">
            <div
                    style="position: absolute;width: 100%;height: 60px;hsla(0,0%,100%,.5);
                    background-color: rgba(255,255,255,0.33);box-shadow: 0 0 20px black;
                    border-radius: 2px;
                    "></div>
            <div id="navbar" style="height: 60px;position: absolute;background-color: transparent;">
                <div style="display: inline-block; " id="logodiv" >
                    <img height="58px" :src="logo" />
                </div>
            </div>
            <el-menu :default-active="activeIndex" class="" mode="horizontal" @select="handleSelect" :router="true" theme="light"
                     style="background-color: rgba(255,255,255,0.0);width: 30%;right:0;position: absolute">

                <el-menu-item v-for="(item,index) in menulist" :key="item" :index="index+''">{{item}}</el-menu-item>

            </el-menu>


            </div>

</template>



<script>
//    import router_build from '../../app_config/router-build'
    import Velocity from 'velocity-animate'
    import Vue from 'vue'

    export default {
        data() {
            return {
                activeIndex:'1',
                navmenu_list:[],

            };
        },
        props:{
            logo:{
                type: String,
                default: 'https://res.wx.qq.com/mpres/htmledition/images/bg/bg_logo318e8e.png', //left_top
                required: false,

            },
            menulist:{
                default: [1,2,3,4],
                required: false,

            },


        },
        methods: {
            handleSelect(key, keyPath) {
//                console.log(key, keyPath);
                this.$root.eventHub.$emit('changeNavMenu',key)
                this.$root.eventHub.$emit('destoryFullPage',key)

            }
        },
        mounted(){

        },

        computed:{

        }

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
.slide-fade-out-in-enter{
    /*
    transition: all 1.5s
    opacity: 0
    transform translateX(-20%)
    */
}
.slide-fade-out-in-leave-active{
    /*
    transition: all 1.5s
    opacity: 0
    transform translateX(20%)
    */
    animation:content-fade-out 2s 1;

}
.slide-fade-out-in-enter-active{
    animation:content-fade-in 2s 1;
}
.slide-fade-out-in-leave{
    /*
    transition: all 1.5s
    */
}

.time {
    font-size: 13px;
    color: #999;
}

.bottom {
    margin-top: 13px;
    line-height: 12px;
}

.button {
    padding: 0;
    float: right;
}

.image {
    width: 100%;
    display: block;
}

.clearfix:before,
.clearfix:after {
    display: table;
    content: "";
}

.clearfix:after {
    clear: both
}

@keyframes content-fade-out{
    0%   {
        transform: scale(1,1);


    }
    50% {
        transform: scale(0.9,0.9);

    }
    100% {

        /*transform: scale(0.9,0.9);*/
        transform: translateY(-100%) scale(0.9,0.9);

        /*transform: translateY(-100%);*/
        background-color: #232e3d;
    }
}
@keyframes content-fade-in {
    0% {
        transform: translateY(-100%);
        background-color: #232e3d;

    }
    100% {
        transform: translateY(0);
        background-color: #3D4D5C;

    }
}
    #navmenu>li
        font-family "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "Noto Sans CJK SC", "WenQuanYi Micro Hei", Arial, sans-serif !important
        word-spacing 2px
        letter-spacing 2px
        transition all 1.5s
        font-weight 400
        font-size 16px !important
    #navmenu>li:after
        content ''
    .is-active
        color #57DCDF !important

</style>
