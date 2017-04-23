<template>
  <div class="top_router_view">
        <!--<input id="query_top" v-model="query" style="color: black !important;background-color: white;position: absolute;z-index: 100000;left:300px">-->
       <!--<effect_line :effect_line_top="query" style=""></effect_line>-->
        <router-view></router-view>
  </div>
</template>

<script>
    import effect_line from './effect_line.vue'
  	module.exports = {
  		data: function () {
  			return {
                query:'0'
  			}
  		},
        methods:{

        },
        components:{
            effect_line

        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">

</style>
