<template>
  <div id="v-time2hope" :style="{width:screenWidth+'px',height:screenHeight+'px'}">
    <router-view></router-view>
  </div>
</template>
<script>
  import {mapGetters, mapActions} from 'vuex'

  export default {
    data() {
      return {
        screenWidth: document.width,
        screenHeight: document.height
      }
    },
    watch: {
      screenWidth: function (a, b) {

      },
      screenHeight: function (a, b) {

      }
    },
    mounted() {
      this.screenWidth = this.sysinfo.screenWidth
      this.screenHeight = this.sysinfo.screenHeight
    },
    computed: {
      ...mapGetters([
          'apprules',
          'website',
          'sysinfo',
          'appshow'
        ])
    },
    methods: {},
    components: {}
  }
</script>
<style lang="stylus" rel="stylesheet/stylus">
#v-time2hope
  background-color transparent
</style>
