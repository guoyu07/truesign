<template>
    <div style="overflow: auto">
        <window_resize style="display: none"></window_resize>
        <slidermenu v-if="show_menu" class="top_menu" style="z-index: 99999"></slidermenu>
        <!--<transition name="fade-top-down" mode="out-in">-->
        <div id="route-show" :style="{height:screenHeight+'px'}">
            <router-view></router-view>
        </div>
        <!--</transition>-->
        <footerbar class="top_footer" style=""></footerbar>




    </div>
</template>

<script>
        require('./api/lib/helper/mouse')
        import slidermenu from './components/common/slidermenu.vue'
        import footerbar from './components/common/footerbar.vue'
        import window_resize from './components/help/window_resize.vue'
//        import fullScreen from './utils/fullScreen'
export default {
  data () {
        return {
            show_nav: true,
            show_menu:true,
            msg: 'hello world',
            transitionName: '',
            screenWidth:document.body.clientWidth,
            screenHeight:document.body.clientHeight-25
        }
  },
  created(){
      var vm = this
      this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
          var width2height = data.split(",")
          vm.screenWidth = parseInt(width2height[0])
          vm.screenHeight = parseInt(width2height[1])-25

      })
  },
  mounted () {
            $("#nav").on('dblclick',function () {
                $("#nav").removeClass('pullin')
                $("#nav").addClass('pullup')
            })
  },
  components: {
            slidermenu,
            footerbar,
            window_resize
  }
}

</script>

<style lang="stylus" rel="stylesheet/stylus">

#route-show
    margin 0
    padding 0
    border none
#nav {
    margin-bottom: 0;
    padding-bottom: 0;
    width: 100%;

    opacity: 12;
    position: relative;
    right: 0;
    z-index: 1000
}


</style>
