<template>
  <div>
  <nav id="nav" class="navbar navbar-default" role="navigation" v-show="show_nav">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="#"></a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">下拉选择 <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">



              <li><router-link to="/">首页</router-link></li>
              <li><router-link to="/test">test</router-link></li>
              <li><router-link to="/test/demo">demo</router-link></li>
              <li><router-link to="/test/awesome">awesome</router-link></li>
              <li><router-link to="/test/semantic">semantic</router-link></li>
              <li><router-link to="/test/alert">alert</router-link></li>
              <li><router-link to="/test/vuex">vuex</router-link></li>
              <li><router-link to="/test/vuex2">vuex2</router-link></li>

              <li><router-link to="/test/websocket">websocket</router-link></li>
              <li><router-link to="/test/socket-io">socket-io</router-link></li>

              <li><router-link to="/test/vux">vux</router-link></li>
              <li><router-link to="/test/webgl">webgl</router-link></li>
              <li><router-link to="/test/echat">echat</router-link></li>
              <li><span>初步选定UI</span></li>
              <li><router-link to="/project">project</router-link></a></li>
              <li><router-link to="/project/element">web端：element</router-link></a></li>
              <li><router-link to="/project/mint-ui">mobile端：mint-ui</router-link></li>
              <li><router-link to="/project/gdmap">高德地图</router-link></li>
              <li><router-link to="/project/canvas">canvas</router-link></li>
              <li><router-link to="/project/video">video</router-link></li>
              <hr>
              <li><span>动效</span></li>
              <li><router-link to="/effect">effect</router-link></li>
              <li @click="show_nav=false"><router-link to="/effect/dynamic_effect">dynamic_effect</router-link></li>


            </ul>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
    <transition name="fade" mode="out-in">
      <router-view></router-view>

    </transition>
    <div>



    </div>

  </div>
</template>

<script>
    require('./api/lib/helper/mouse')
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
        }


    }

</script>

<style>
  .fade-enter-active, .fade-leave {
    transition: all 1.5s ease;
    transform: translateY(0);
  }
  .fade-enter, .fade-leave-active {
    opacity: 0;

    transform: translateY(-20%);

  }


  #nav{
    margin-bottom: 0;
    padding-bottom: 0;
    width:  100%;

    opacity:12;position: relative; right:0;z-index:1000
  }



  </style>
