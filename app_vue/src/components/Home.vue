<template>
    <div class="top_router_view" style="" >
        <p class="top_router_tip">HOME</p>
        <input @click="clickme" value="点击我" style="margin-left: 50%">
    </div>
</template>



<script>

    export default {
        data(){
            return{

            }
        },
        mounted(){
//            this.$router.push('/project/website_main/website_index')
        },
        methods:{
            clickme(e){
                console.log(e)
            }
        },
        components:{


        }
    }
</script>
<style>

</style>
