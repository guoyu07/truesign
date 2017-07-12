<template>
  <div class="top_router_view">
    <p>test_child1</p>

  </div>
</template>

<script>

  	export default {
  		data: function () {
  			return {
  			}
  		},
        created(){
  		    console.log('child1->create')

        },
        mounted(){
            this.$root.eventHub.$on('test',function (data) {
                console.log('child1->',data)
            })
        },
        beforeDestroy(){
            console.log('child1->beforeDestory')
            this.$root.eventHub.$off('test')
        },
  	}
</script>

<style>

</style>
