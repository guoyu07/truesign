<template>
    <div style="width: 100%;height: 100%;overflow: hidden">
        <effectlogo style="position: fixed;left:0;top:0" logo_pos="center" logo_width="150" custom_margin_left="400" custom_margin_top="0"></effectlogo>
        <common_form formtype="login" class="login_form" circle_flag_pos="right" style="margin-left:500px;margin-top:5%;height: 600px;min-height: 600px;box-shadow: 0 0 20px black"></common_form>
        <common_form formtype="reg" class="reg_form" circle_flag_pos="left" style="margin-left:910px;margin-top:5%;height: 600px;min-height: 600px;box-shadow: 0 0 20px black"></common_form>
    </div>
</template>


<script>
    import common_form from '../../common/form.vue'
    import effectlogo from '../../loading/effect_logo.vue'

    export default {
        data(){
            return{

            }
        },

        components:{
            common_form,
            effectlogo
        },
        computed: {


        },
        created(){

        },
        mounted(){




        },
        beforeDestroy(){

        },
        methods:{


        },

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">



</style>