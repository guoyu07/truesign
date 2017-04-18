<template>
  <div>
    <!--<nav id="nav" class="navbar navbar-default" role="navigation" v-show="show_nav">-->
      <!--<div class="container-fluid">-->
        <!--&lt;!&ndash; Brand and toggle get grouped for better mobile display &ndash;&gt;-->
        <!--<div class="navbar-header">-->
          <!--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">-->
            <!--<span class="sr-only">Toggle navigation</span>-->
            <!--<span class="icon-bar"></span>-->
            <!--<span class="icon-bar"></span>-->
            <!--<span class="icon-bar"></span>-->
          <!--</button>-->

          <!--<a class="navbar-brand" href="#"></a>-->
        <!--</div>-->

        <!--&lt;!&ndash; Collect the nav links, forms, and other content for toggling &ndash;&gt;-->
        <!--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">-->


          <!--<ul class="nav navbar-nav navbar-right">-->
            <!--<li class="dropdown">-->
              <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">下拉选择 <span class="caret"></span></a>-->
              <!--<ul class="dropdown-menu" role="menu">-->



                <!--<li><router-link to="/">首页</router-link></li>-->
                <!--<li><router-link to="/test">test</router-link></li>-->
                <!--<li><router-link to="/test/demo">demo</router-link></li>-->
                <!--<li><router-link to="/test/awesome">awesome</router-link></li>-->
                <!--<li><router-link to="/test/semantic">semantic</router-link></li>-->
                <!--<li><router-link to="/test/alert">alert</router-link></li>-->
                <!--<li><router-link to="/test/vuex">vuex</router-link></li>-->
                <!--<li><router-link to="/test/vuex2">vuex2</router-link></li>-->

                <!--<li><router-link to="/test/websocket">websocket</router-link></li>-->
                <!--<li><router-link to="/test/socket-io">socket-io</router-link></li>-->

                <!--<li><router-link to="/test/vux">vux</router-link></li>-->
                <!--<li><router-link to="/test/webgl">webgl</router-link></li>-->
                <!--<li><router-link to="/test/echat">echat</router-link></li>-->
                <!--<li><span>初步选定UI</span></li>-->
                <!--<li><router-link to="/project">project</router-link></li>-->
                <!--<li><router-link to="/project/element">web端：element</router-link></li>-->
                <!--<li><router-link to="/project/mint-ui">mobile端：mint-ui</router-link></li>-->
                <!--<li><router-link to="/project/gdmap">高德地图</router-link></li>-->
                <!--<li><router-link to="/project/canvas">canvas</router-link></li>-->
                <!--<li><router-link to="/project/video">video</router-link></li>-->
                <!--<li><router-link to="/project/slidermenu">video</router-link></li>-->
                <!--<hr>-->
                <!--<li><span>动效</span></li>-->
                <!--<li><router-link to="/effect">effect</router-link></li>-->
                <!--<li @click="show_nav=false"><router-link to="/effect/dynamic_effect">dynamic_effect</router-link></li>-->
                <!--<li><router-link to="/common">common</router-link></li>-->
                <!--<li><router-link to="/common/slidermenu">common/slidermenu</router-link></li>-->



              <!--</ul>-->
            <!--</li>-->
          <!--</ul>-->
        <!--</div>&lt;!&ndash; /.navbar-collapse &ndash;&gt;-->
      <!--</div>&lt;!&ndash; /.container-fluid &ndash;&gt;-->
    <!--</nav>-->
    <!--help区块-->
    <window_resize style="display: none"></window_resize>
    <slidermenu class="top_menu" style="z-index: 99999"></slidermenu>
    <footerbar class="top_footer" style="z-index: 99999"></footerbar>
    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>
    <div>



    </div>

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
  .fade-enter-active{
    /*transition: all 1s linear;*/
    /*transform: translateX(0);*/
    animation:top_view_in 2s 1;

  }
  .fade-enter{
    /*transition: all 1.5s linear;*/
    /*transform: translateY(-100%);*/

  }
  .fade-leave{
    /*transition: all 1.5s linear;*/
    /*transform: scale(1,1);*/
    /*transform: translateY(0)*/
  }
  .fade-leave-active{
    animation:top_view_out 1s 1;
    /*transition: all 0.5s linear;*/

    /*transform: translateY(-100%);*/
    /*transform: scale(0.8,0.8);*/


  }


  #nav{
    margin-bottom: 0;
    padding-bottom: 0;
    width:  100%;

    opacity:12;position: relative; right:0;z-index:1000
  }
  @keyframes top_view_out{
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
  @keyframes top_view_in {
    0%   {
      transform: translateY(-100%);
      background-color: #232e3d;

    }
    100% {
      transform: translateY(0);
      background-color: #3D4D5C;

    }


    }

  </style>
