<template>
    <div style="overflow: auto">
        <window_resize style="display: none"></window_resize>
        <slidermenu v-if="show_menu" class="top_menu" style="z-index: 99999"></slidermenu>
        <!--<transition name="fade-top-down" mode="out-in">-->
        <div id="route-show" :style="{height:screenHeight+'px'}">
            <router-view style="background-color: red"></router-view>
        </div>
        <!--</transition>-->
        <footerbar  v-if="platform==='pc'"  class="top_footer" :style="{position:'absolute',zIndex:footerZindex,width: '100%',bottom: 0}"></footerbar>
        <transition name="fade-up">
            <footerbar  v-if="show_mobile_footer === true"  class="top_footer" style="position: absolute;width: 100%;z-index:100;bottom: 0"></footerbar>
        </transition>


    </div>
</template>

<script>
require('./api/lib/helper/mouse')
import slidermenu from './components/common/slidermenu.vue'
import footerbar from './components/common/footerbar.vue'
import window_resize from './components/help/window_resize.vue'

import { mapGetters,mapActions } from 'vuex'
import platform from 'platform';
        //        import fullScreen from './utils/fullScreen'
export default {
        data () {
            return {
                show_nav: true,
                show_menu:true,
                msg: 'hello world',
                transitionName: '',
                screenWidth:document.body.clientWidth,
                screenHeight:document.body.clientHeight,
                platform:'pc',
                footerZindex:10,
                footertranslateY:100,
                show_mobile_footer:false
            }
        },
        created(){
          var vm = this
          var os = platform.os
          var os_description = platform.description
          this.updateSysInfo({
              os:os,
              os_description:os_description
          })
            var os_type = os.family.toLocaleLowerCase()
            if(os_type.indexOf('os x') > -1 || os_type.indexOf('window') > -1){
//                    console.log('电脑端',os_type)
            }
            else{
//                    console.log('手机端:',os_type)
                this.platform = 'mobile'

            }
          this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
              var width2height = data.split(",")
              vm.screenWidth = parseInt(width2height[0])
              vm.screenHeight = parseInt(width2height[1])-25

          })
        },
        mounted () {
            var vm = this
                $("#nav").on('dblclick',function () {
                    $("#nav").removeClass('pullin')
                    $("#nav").addClass('pullup')
                })
            this.$root.eventHub.$on('changeFooterZindex',function (data) {
                this.footerZindex = data

            })
            this.$root.eventHub.$on('changeShowMobileFooter',function (data) {
                console.log('data',data)
                vm.show_mobile_footer = data

            })
        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),

        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo',
                'appshow'
            ])
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
