<template>
  <div style="width: 100%;height: 100%;overflow: hidden">


  </div>
</template>



<script>

    import DrawCanvas from '../../api/drawCanvas.js'



  export default{

      data() {
          return {
              screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
              screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
          }
      },

      mounted(){
          var vm = this
          this.$root.eventHub.$on('screenWidth2screenHeight', function (data) {
              var width2height = data.split(",")
              vm.screenWidth = parseInt(width2height[0])
              vm.screenHeight = parseInt(width2height[1])

          })

      }
  }

</script>
<style>

</style>
