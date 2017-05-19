<template>
  <div class="top_router_view" style="text-align: center">
    <!--<input type="button"  value="emit" @click="doemit">-->
    <!--<p class="top_router_tip">TEST</p>-->
    <!--<router-view></router-view>-->
    <ol>
      <li v-for="ms in msg">
        <router-link :to="'a?id='+ms" v-text="ms"></router-link>

      </li>

    </ol>
  </div>
</template>

<script>

  	export default {
  		data: function () {
  			return {
                msg: ['aaaaa','bbbbbbbbbbb','cccccccccccc']

            }
  		},
        created(){

        },
        methods:{
//            doemit(){
//                this.$root.eventHub.$emit('test',1)
//            }
        }

  	}
</script>

<style>

</style>
