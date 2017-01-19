<template>
  <div>
  <nav class="navbar navbar-default" role="navigation">
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
        <!--<ul class="nav navbar-nav">-->
        <!---->
          <!--<li class="dropdown">-->
            <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>-->
            <!--<ul class="dropdown-menu" role="menu">-->
              <!--<li><a href="#">Action</a></li>-->
              <!--<li><a href="#">Another action</a></li>-->
              <!--<li><a href="#">Something else here</a></li>-->
              <!--<li class="divider"></li>-->
              <!--<li><a href="#">Separated link</a></li>-->
              <!--<li class="divider"></li>-->
              <!--<li><a href="#">One more separated link</a></li>-->
            <!--</ul>-->
          <!--</li>-->
        <!--</ul>-->

        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">下拉选择 <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <!--<li><router-link :to="home">home</router-link></li>-->
              <!--<li><router-link :to="semantic">semantic</router-link></li>-->

              <li><router-link to="/">首页</router-link></li>
              <li><router-link to="demo">demo</router-link></li>
              <li><router-link to="awesome">awesome</router-link></li>
              <li><router-link to="semantic">semantic</router-link></li>
              <li><router-link to="alert">alert</router-link></li>
              <li><router-link to="vuex">vuex</router-link></li>
              <li><router-link to="vuex2">vuex2</router-link></li>
              <li><router-link to="mint-ui">mint-ui</router-link></li>
              <li><router-link to="websocket">websocket</router-link></li>



            </ul>
          </li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
    <router-view></router-view>
  </div>
</template>

<script>
    // import fullScreen from './utils/fullScreen'
    export default {
        data () {
            return {
                'msg' : 'hello world'
            }
        }
    }

</script>

<style lang="stylus" rel="stylesheet/stylus">
  #app
    color:red

</style>