<template>
  <div id="live_app">
      <live_list class="live_list" ></live_list>
      <!--<live_video class="live_video" ></live_video>-->
      <live_info class="live_info" ></live_info>

      <transition name="slide-fade">
          <live_ctrl class="live_ctrl" v-if="ctrl" ></live_ctrl>

      </transition>
      <a href="javascript:void(0)" ID="btn-ctrl" class="button" @click.stop.prevent="actionctrl">CTRL</a>

  </div>
</template>
<script>
//    var player = videojs('my-player');
    const Waves  = require('node-waves');

    import live_list from './live_app/live_list.vue'
    import live_info from './live_app/live_info.vue'
    import live_video from './live_app/live_video.vue'
    import live_ctrl from './live_app/live_ctrl.vue'
    export default {

        data() {
            return {
                video_list:[],
                ctrl:true


            }
        },
        methods:{
            actionctrl:function () {
                this.ctrl = !this.ctrl
            },
        },
        mounted(){

            var Waves_config = {
                // How long Waves effect duration
                // when it's clicked (in milliseconds)
                duration: 500,

                // Delay showing Waves effect on touch
                // and hide the effect if user scrolls
                // (0 to disable delay) (in milliseconds)
                delay: 200
            };

// Initialise Waves with the config
            Waves.init(Waves_config);

            Waves.attach('.button', ['waves-button', 'waves-float'])

        },
        computed:{

        },
        components:{
            live_list,
            live_video,
            live_info,
            live_ctrl
        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
    html,body{
        height:100%;
        width 100%
        margin:0px;
        background rgba(0, 0, 0, 0.35)
    }
    body
        overflow hidden
    nav
        display none
    #live_app
        position:absolute

        .live_list

            display inline-block
            position fixed
            z-index 2
            overflow visible
            width: 300px;
            left 10px
            top 20px
        .live_info
            display inline-block
            position fixed
            z-index 2
            overflow hidden
            width: 300px;
            right 10px
            top 20px


        .live_video
            position:fixed
            z-index 1
            left 0
            right 0
            float left
            overflow hidden
            scroll no

        .live_ctrl

            position:fixed
            z-index 3
            overflow hidden
            width 100%;
            height 100%
            background-color: rgba(55, 73, 93, 0.9); backdrop-filter: blur(5px)
        #btn-ctrl

            position fixed
            background-color rgba(0, 192, 0, 0.03)
            z-index 5
            right 0
            bottom 0
            color #0f0f10

        .button
        {
            font-weight:bold;
            font-size:15px;
            padding:10px 15px;
            border:none;
            margin:50px;
            cursor:pointer;


            border-radius:5px;
            box-shadow:0 0 2px rgba(0,0,0,0.5);
            text-shadow:0 0 5px rgba(255,255,255,0.5);
            display:inline-block; /*它是重要为按钮旋转*/
        }

        .slide-fade-enter-active,.slide-fade-leave-active {
            transition: all 1.2s ease;
        }

        .slide-fade-enter, .slide-fade-leave-active {
            transform: translateX(100%);
            opacity: .1;
        }




</style>