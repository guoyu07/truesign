<template>
  <div class="home">
    <navigation></navigation>
    <webgl-home></webgl-home>
  </div>
</template>

<script>
    import WebglHome  from './webgl/WebglHome';
    import Navigation from './webgl/Navigation';

    export default {
        components: {
            WebglHome,
            Navigation
        },
        vuex: {
            getters: {},
            actions: {}
        },
        data () {
            return {}
        },
        mounted: function() {

        },
        methods:{

        }
    }
</script>

<style >

  .home{
    width: 100%;
    height: 100%;
    margin: 0px;
    padding: 0px;
  }

</style>
