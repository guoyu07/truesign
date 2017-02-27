<template>
  <div id="project">
    <index></index>
    <!--<spa></spa>-->
      <!--<router-view></router-view>-->
  </div>
</template>

<script>
    import spa from './spa/spa.vue'
    import index from './spa/index.vue'


  	module.exports = {
  		data: function () {
  			return {
  			}
  		},
        components:{
            index,
            spa
        }
  	}
</script>

<style>

</style>
