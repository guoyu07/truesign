<template>
  <div class="top_router_view" style="text-align: center">
    <input type="button"  value="emit" @click="doemit">
    <p class="top_router_tip">TEST</p>
    <router-view></router-view>
  </div>
</template>

<script>

  	export default {
  		data: function () {
  			return {
  			}
  		},
        created(){

        },
        methods:{
            doemit(){
                this.$root.eventHub.$emit('test',1)
            }
        }

  	}
</script>

<style>

</style>
