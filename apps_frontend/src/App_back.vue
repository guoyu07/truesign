<template lang="pug">
  div#app
    div#hello
    div#hi {{msg}}
    router-view
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