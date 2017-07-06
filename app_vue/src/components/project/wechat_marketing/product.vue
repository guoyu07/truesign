<template>
    <div class="top_router_view" style="padding-top: 60px">
        <div style="font-size: 0.1rem">产品</div>


    </div>
</template>


<script>
    export default {
        components: {

        },
        created(){
          this.$root.eventHub.$emit('init_navmenu','marketing_product')

        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

</style>