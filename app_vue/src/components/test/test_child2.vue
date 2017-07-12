<template>
  <div class="top_router_view">
    <p>test_child2</p>


  </div>
</template>

<script>

  	export default {
  		data: function () {
  			return {
  			}
  		},
        created(){
            console.log('child2->create')

        },
        mounted(){
            this.$root.eventHub.$on('test',function (data) {
                console.log('child2->',data)
            })
        },
        beforeDestroy(){
            console.log('child2->beforeDestory')
            this.$root.eventHub.$off('test')
        },
  	}
</script>

<style>

</style>
