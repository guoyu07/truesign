<template>
  <div id="project">
   <h1>project</h1>
    <router-view></router-view>
    <spa></spa>

  </div>
</template>

<script>
    import spa from './spa/spa.vue'
  	module.exports = {
  		data: function () {
  			return {
  			}
  		},
        components:{
            spa
        }
  	}
</script>

<style>

</style>
