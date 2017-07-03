<template>
    <div style="width: 100%;height: 100%;overflow: hidden">
        <div id="is_form_container" style="">
            <div style="position: absolute;width: 800px;height: 580px;z-index:1;margin-left: 20px;margin-top: 20px;min-width: 10px">
                <common_form formtype="login"  class="login_form_container" circle_flag_pos="right" style=""></common_form>
                <common_form formtype="reg"  class="reg_form_container" circle_flag_pos="left" style=""></common_form>
            </div>
            <!--<transition name="el-zoom-in-top">-->

                <!--<div class="form_mask" v-if="form_status==='login'" :style="{left:0}">-->
                <!--</div>-->
                <!--<div class="form_mask" v-else="form_status==='login'" :style="{right:0}">-->
                <!--</div>-->

            <!--</transition>-->
            <div class="form_mask"  :style="{left:form_status==='login'?430+'px':0}">
                <div  @click="change_form_type"  class="effect_logo_container"
                      style="width:200px;height:200px;position:absolute;cursor:pointer;left: 50%;margin-left: -100px;top:50%;margin-top: -110px">
                    <effectlogo logo_pos="relative_center" logo_width="150"
                                style="position: absolute;margin-top: 10px;margin-left: 10px;"></effectlogo>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import common_form from '../../common/form.vue'
    import effectlogo from '../../loading/effect_logo.vue'

    export default {
        data(){
            return{
                form_status:'login',
                form_data:{}
            }
        },

        components:{
            common_form,
            effectlogo
        },
        computed: {


        },
        created(){
            var vm = this
            this.$root.eventHub.$on('change_form_type',() => {
                vm.change_form_type()
            })
            this.$root.eventHub.$emit('init_navmenu','form')
        },
        mounted(){




        },
        beforeDestroy(){

        },
        methods:{
            change_form_type(){
                var vm = this
                vm.form_status = vm.form_status === 'login' ? 'reg':'login'

            }

        },

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

    #is_form_container{
        width 860px;
        height 640px;
        background-color transparent
        left:50%;
        margin-left -430px;
        position absolute
        top 100px
        z-index 1000
    }
    #is_form_container .login_form_container{
       height: 600px;min-height: 600px;box-shadow: 0 0 20px black
    }
    #is_form_container .reg_form_container{
        margin-left:420px;height: 600px;min-height: 600px;box-shadow: 0 0 20px black
    }
    #is_form_container .form_mask{
        position: absolute;width: 425px;height: 100%;z-index:2;background-color: #DCDCDC

    }
    #is_form_container .form_mask{
        transition all 1.5s
        position absolute
    }
    #is_form_container .effect_logo_container{
        transition all 0.8s
        border-radius 100px;
    }
    #is_form_container .effect_logo_container:hover{
        box-shadow 0 0 55px black
        transition all 0.8s
    }
</style>