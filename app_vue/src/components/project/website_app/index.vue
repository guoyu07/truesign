<template>
    <div class="top_router_view" style="" >

        <transition name="fade-show">
            <effect_line v-if="show_loading" id="loading_page" style="position: absolute;z-index: 13" :effect_line_top="effect_line_top"></effect_line>

        </transition>
        <login  v-if="parseInt(effect_line_top) > 0 || !show_loading"   id="main_page" style="position:absolute;z-index: 12;transition: all 1s"></login>

        <effectlogo id="effectlogo"   logo_pos='center' style="position: absolute;z-index:15"></effectlogo>
        <div v-if="show_login_log" id="loding_log_show" style="width: 50%;height: 100%;position: absolute;z-index:100;background-color: transparent;color:rgba(139,226,255,0.65);font-size: 16px;font-weight: 800;text-align: center;padding-top: 50px ">
               <p>预加载:  {{effect_line_top}}%</p>
               <p>初始化服务器通讯:  {{init_conn}}</p>
               <p>初始化权限连接:  {{bind_app}}</p>
               <p>检测IP:  {{sysinfo.ip}}</p>
               <p>初始化网站状态:  {{website_status}}</p>
               <p>检测账户日志:  {{login_status}}</p>
        </div>
    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../../loading/effect_logo.vue'
    import login from './login.vue'
    import effect_line from '../../loading/effect_line.vue'
    import initsocket from '../../communicationModule/initSocket.vue'
    import { analysis_socket_response } from '../../../api/lib/helper/dataAnalysis'
    export default {
        data(){
            return{
                show_login_log:true,
                effect_line_top:'0',
                show_loading:false,
                init_conn:'执行中',
                bind_app:'执行中',
                website_status:'执行中',
                login_status:'鉴定中',


            }
        },
        components:{
            login,
            effect_line,
            initsocket,
            effectlogo

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
            var vm = this
//            vm.show_loading=true
//            vm.show_login_log = true
            this.$root.eventHub.$on('laoding',function (data) {
                console.log('loading->',data)
                if(data==='start'){
                    vm.show_loading=true
                    setTimeout(function () {
                        $('#loading_page').css('z-index',13)
//                        vm.effect_line_top = '100'

                    },1000)



                }
                else if(data==='over'){
                    $('#loading_page').css('z-index',-1)
                    $('#effectlogo').css('z-index',10)
                    vm.show_loading=false
                    vm.effect_line_top = '0'


                }
            })
        },
        mounted(){
            /*
            网站预加载工作区
             */
            var vm = this

            vm.show_loading=true
            vm.show_login_log = true

            if(vm.website.conn_status){
                vm.init_conn = 'done'
                let top_num = (Math.random()*20).toFixed(1)
                vm.effect_line_top=parseInt(vm.effect_line_top)>top_num?vm.effect_line_top:top_num+''
            }
            this.$root.eventHub.$on('conn_status',function (data) {
                if(data){
                    vm.init_conn = 'done'
                    let top_num = (Math.random()*20).toFixed(1)
                    vm.effect_line_top=parseInt(vm.effect_line_top)>top_num?vm.effect_line_top:top_num+''
                }
            })

            if(vm.website.isbindapps.length > 0){
                vm.bind_app = vm.website.access_user.nickname
                let top_num = (Math.random()*10).toFixed(1)
                vm.effect_line_top=parseInt(vm.effect_line_top)>top_num?vm.effect_line_top:top_num+''
            }
            this.$root.eventHub.$on('init_bind_apps',function (data) {
                if(data){
                    vm.bind_app = data.nickname
                    let top_num = (Math.random()*10).toFixed(1)
                    vm.effect_line_top=parseInt(vm.effect_line_top)>top_num?vm.effect_line_top:top_num+''
                }
            })
            this.$root.eventHub.$on('init_website_status',function (data) {
                vm.website_status = 'done'
                let top_num = (Math.random()*85).toFixed(1)
                vm.effect_line_top=parseInt(vm.effect_line_top)>top_num?vm.effect_line_top:top_num+''
            })
            this.$root.eventHub.$on('init_login_status',function (data) {
                if(data){
                    vm.updateWebSite({
                        login_status:1
                    })
                    vm.login_status = data.username +' 即将进入主页面'
                    vm.effect_line_top='100'

                    if(vm.$route.path === '/project/website_main/website_app_square'){

                    }else{
                        setTimeout(function () {
                            vm.effect_line_top='0'

                            vm.$router.push('/project/website_main/website_app_square')
                            vm.show_login_log = false

                        },1200)
                    }

                }
                else{
                    vm.updateWebSite({
                        login_status:0
                    })
                    vm.login_status = '未登录'
                    vm.effect_line_top='100'
                    setTimeout(function () {
                        vm.show_login_log = false
//                        vm.effect_line_top='0'
                    },1500)
                    if(vm.$route.path === '/project/website_main/website_index'){

                    }else{
                        setTimeout(function () {
                            vm.$router.push('/project/website_main/website_index')
                        },1500)
                    }

                }


            })
//            var line_per = Math.random()*90
//            var time_random = Math.random()*1000
//            vm.show_loading=true
//            setTimeout(function () {
//
//                vm.effect_line_top=line_per+''
//            },time_random)
//            setTimeout(function () {
//                vm.effect_line_top='100'
//
//            },1000)
//            setTimeout(function () {
//                vm.show_loading=false
//                this.effect_line_top='0'
//
//            },5000)


            this.$root.eventHub.$emit('autoInit',1)


            this.$root.eventHub.$on('submit_form_login_response',function (data) {
                if(data.status){
                    vm.$router.push('website_app_square')
                }
            })





        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            keythis(n,e){
            },


        },

    }
</script>
<style>
    #loding_log_show p {
        width: 50%;
        text-align: right;
    }

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
