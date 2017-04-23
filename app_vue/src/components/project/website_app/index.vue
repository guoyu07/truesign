<template>
    <div class="top_router_view" style="" >


        <transition name="fade-show">
            <effect_line v-if="show_loading" id="loading_page" style="position: absolute;z-index: 13" :effect_line_top="effect_line_top"></effect_line>

        </transition>
        <login  v-if="parseInt(effect_line_top) > 0 || !show_loading"   id="main_page" style="position:absolute;z-index: 12;transition: all 1s"></login>

        <effectlogo id="effectlogo"   logo_pos='center' style="position: absolute;z-index:15"></effectlogo>
    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../../loading/effect_logo.vue'
    import login from './login.vue'
    import effect_line from '../../loading/effect_line.vue'
    import initsocket from '../../communicationModule/initSocket.vue'
    export default {
        data(){
            return{
                effect_line_top:'0',
                show_loading:false,

            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ])
        },
        created(){


        },
        mounted(){
            /*
            网站预加载工作区
             */
            var vm = this
//            var line_per = Math.random()*90
//            var time_random = Math.random()*2000
//            vm.show_loading=true
//            setTimeout(function () {
//
//                vm.effect_line_top=line_per+''
//            },time_random)
            vm.show_loading=true

            var line_per = Math.random()*90
            var time_random = Math.random()*1000
            vm.show_loading=true
            setTimeout(function () {

                vm.effect_line_top=line_per+''
            },time_random)
            setTimeout(function () {
                vm.effect_line_top='100'

            },1000)
            setTimeout(function () {
                vm.show_loading=false
                this.effect_line_top='0'

            },5000)


            this.$root.eventHub.$emit('autoInit',1)
            this.$root.eventHub.$on('laoding',function (data) {
                console.log('loading->',data)
                if(data==='start'){
                    vm.show_loading=true
                    setTimeout(function () {
                        $('#loading_page').css('z-index',13)
                        vm.effect_line_top = '100'

                    },1000)



                }
                else if(data==='over'){
                    $('#loading_page').css('z-index',-1)
                    $('#effectlogo').css('z-index',10)
                    vm.show_loading=false
                    vm.effect_line_top = '0'


                }
            })
            this.$root.eventHub.$on('submit_form_login_response',function (data) {
                if(data.status){
                    vm.$router.push('website_app_square')
                }
            })





        },
        methods:{
            keythis(n,e){
            },


        },
        components:{
            login,
            effect_line,
            initsocket,
            effectlogo

        }
    }
</script>
<style>
    .fade-show-enter-active{
        transition: all 1s;
        opacity: 1;

    }
    .fade-show-enter{
        transition: all 1s;

        opacity: 0;

    }
    .fade-show-leave{
        transition: all 1s;

        opacity: 1;
    }
    .fade-show-leave-active{
        transition: all 1s;

        opacity: 0;


    }
</style>
