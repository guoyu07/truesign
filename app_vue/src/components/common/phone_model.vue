<template>
        <div class="phone_model" >
                <iframe class="demo-page" frameborder="0"   src="http://wap.baidu.com"></iframe>
        </div>

</template>


<script>


    export default {
        data(){
            return {}
        },
        props: {},
        components: {},
        computed: {},
        created(){

        },
        mounted(){


        },
        beforeDestroy(){

        },
        methods: {},

    }
</script>
<style>
    .phone_model{
        margin:20px 20px 0;
        background-image:url(http://cdn.iamsee.com/phone-model/phone_black.png);
        background-repeat:no-repeat;
        background-size:100%;
        height:auto;
        padding:100px 16px;
        box-sizing:border-box;
        width:365px;
        position: absolute;
        left:60%;
    }

    .phone_model .demo-page{
        width:100%;
        height:590px;
        background-color:#fff;
    }

</style>
