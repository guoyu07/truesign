<template>
    <div class="top_router_view">
        <p class="top_router_tip">COMMON</p>
        <!--<slidermenu ></slidermenu>-->
        <router-view></router-view>
    </div>
</template>



<script>
    import slidermenu from './slidermenu.vue'
    export default {
        data(){
            return{
                menu_list:''

            }
        },
        components:{
            slidermenu,

        }
    }
</script>
<style>

</style>
