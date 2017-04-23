<template>
  <div>
    <window_resize style="display: none"></window_resize>
    <slidermenu class="top_menu" style="z-index: 99999"></slidermenu>
    <footerbar class="top_footer" style="z-index: 99999"></footerbar>
    <!--<transition name="fade-top-down" mode="out-in">-->
      <router-view></router-view>
    <!--</transition>-->


  </div>
</template>

<script>
    require('./api/lib/helper/mouse')
    import slidermenu from './components/common/slidermenu.vue'
    import footerbar from './components/common/footerbar.vue'
    import window_resize from './components/help/window_resize.vue'

    // import fullScreen from './utils/fullScreen'
    export default {
        data () {
            return {
                'show_nav':true,
                'msg' : 'hello world',
                'transitionName':''
            }
        },
        mounted(){
            $("#nav").on('dblclick',function () {
                $("#nav").removeClass('pullin')
                $("#nav").addClass('pullup')
            })
        },
        components:{
            slidermenu,
            footerbar,
            window_resize
        }


    }

</script>

<style>



  #nav{
    margin-bottom: 0;
    padding-bottom: 0;
    width:  100%;

    opacity:12;position: relative; right:0;z-index:1000
  }


  </style>
