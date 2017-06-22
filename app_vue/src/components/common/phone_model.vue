<template>
        <div class="phone_model" >
                <iframe class="demo-page" frameborder="0"   :src="mobile_show_uri"></iframe>
        </div>

</template>


<script>


    export default {
        data(){
            return {}
        },
        props: {
            mobile_show_uri:{
                type: String,
                default: 'http://wap.baidu.com',
            },

        },
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
                margin:0px 20px 0;
                background-image:url(http://cdn.iamsee.com/phone-model/phone_black.png);
                background-repeat:no-repeat;
                background-size:100% 100%;
                height:620px;
                padding:62px 16px;
                box-sizing:border-box;
                width:313px;
                position: absolute;
                left:70%
        }
        .phone_model .demo-page{
                width:100%;
                height:480px;
                background-color:#fff;
        }

</style>
