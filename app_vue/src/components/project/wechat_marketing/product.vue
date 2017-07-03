<template>
    <div class="top_router_view" style="">
        <div style="font-size: 0.1rem">产品</div>
    </div>
</template>


<script>
    export default {
        components: {
            Parallax
        },
        created(){
            this.$root.eventHub.$emit('init_navmenu','product')

        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

</style>