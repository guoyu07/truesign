<template>
    <div class="top_router_view" style="min-width: 700px" >

        <transition name="fade-show">
            <effect_line v-if="show_loading" id="loading_page" style="position: absolute;z-index: 13" :effect_line_top="effect_line_top"></effect_line>

        </transition>
        <login  v-if="(parseInt(effect_line_top) > 0 || !show_loading) && platform==='pc'"   id="main_page" style="position:absolute;z-index: 12;transition: all 1s"></login>

        <effectlogo id="effectlogo"    style="position: absolute;z-index:15"></effectlogo>
        <div v-if="platform !== 'pc'" style="text-align: center;color: white;left:50%;transform:translateX(-50%);top: 55%;position: absolute;font-size: 26px">
            非PC平台,跳转中
        </div>
        <div v-if="show_login_log && platform ==='pc'" id="loding_log_show" style="width: 50%;height: 100%;position: absolute;z-index:100;background-color: transparent;color:rgba(139,226,255,0.65);font-size: 16px;font-weight: 800;text-align: center;padding-top: 50px ">
               <p>预加载:  {{effect_line_top}}%</p>
               <p>初始化服务器通讯:  {{init_conn}}</p>
               <p>初始化权限连接:  {{bind_app}}</p>
               <p>检测IP:  {{sysinfo.ip}}</p>
               <p>初始化网站状态:  {{website_status}}</p>
               <p>检测账户日志:  {{login_status}}</p>
        </div>
        <div v-if="show_code_matrix && platform ==='pc'"  style="opacity:0.3;width: 50%;left:70%;top:50%;height: 46%;position: absolute;z-index:100;background-color: transparent;color:rgba(139,226,255,0.65);font-size: 16px;font-weight: 800;text-align: center;padding-top: 50px ">
            <p style="text-align: left;font-size: 18px;letter-spacing: 2px">矩阵识别唯一序列码中</p>
            <div id="code_matrix" style="">
                <transition-group name="code_matrix_item" tag="div" class="code_matrix_container"
                                  v-bind:css="false"
                                  v-on:before-enter="beforeEnter"
                                  v-on:enter="enter"
                                  v-on:leave="leave">

                    <div v-for="(item,index) in code_matrix_list" :key="item.id" class="code_matrix_item" :style="{ backgroundColor:item.color}" :data-index="index">
                        {{ item.code }}
                     </div>
                </transition-group>
            </div>
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
    const _ = require('lodash');
    import Vue from 'vue'
    import Velocity from 'velocity-animate'
    var done = ''

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
                show_code_matrix:true,
                chars : 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=!@#$%^&*()-,.?~ ',
//                code_matrix_list: Array.apply(null, { length: 81 })
//                    .map(function (_, index) {
//                        return {
//                            id: index,
//                            code: index % 9 + 1
//                        }
//                    })
                code_matrix_list:[],
                platform:'pc'
            }
        },

        components:{
            login,
            effect_line,
            initsocket,
            effectlogo,

        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ]),

        },
        created(){
            var vm = this
            var os = this.sysinfo.os
            console.log('os->',os)
            if(os){
                console.log('终端类型不为空，继续执行')
                var os_type = os.family.toLocaleLowerCase()
                if(os_type.indexOf('os x') > -1 || os_type.indexOf('window') > -1){
                    console.log('电脑端',os_type)
                }
                else{
                    console.log('手机端:',os_type)
                    this.platform = 'mobile'

                }
            }
            this.build_code_matrix_list()

        },
        mounted(){

            /*
            网站预加载工作区
             */
            var vm = this

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
                    vm.show_code_matrix = false
                    if(vm.platform !== 'pc'){
                        setTimeout(function () {
                            vm.$router.push('/project/m_website_main')

                        },1500)
                    }




                }
            })


            setTimeout(function () {
                vm.shuffle()
//                vm.changewordscolor()
            },3000)

            vm.show_loading=true
            vm.show_login_log = true

            if(vm.website.conn_status){
                vm.init_conn = 'done'
                vm.init_code_matrix_color(vm.website.unique_auth_code,'rgba(255,10,0,0.2)')
                let top_num = (Math.random()*20).toFixed(1)
                vm.effect_line_top=parseInt(vm.effect_line_top)>top_num?vm.effect_line_top:top_num+''
            }
            this.$root.eventHub.$on('conn_status',function (data) {
                if(data){
                    vm.init_conn = 'done'
                    vm.init_code_matrix_color(vm.website.unique_auth_code,'rgba(194,90,33,0.8)')
                    let top_num = (Math.random()*20).toFixed(1)
                    vm.effect_line_top=parseInt(vm.effect_line_top)>top_num?vm.effect_line_top:top_num+''
                }
            })

            if(vm.website.isbindapps.length > 0){
                vm.bind_app = vm.website.access_user.nickname
                vm.init_code_matrix_color(vm.website.encryption_key,'rgba(194,90,33,0.8)')

                let top_num = (Math.random()*10).toFixed(1)
                vm.effect_line_top=parseInt(vm.effect_line_top)>top_num?vm.effect_line_top:top_num+''
            }
            this.$root.eventHub.$on('init_bind_apps',function (data) {
                if(data){
                    vm.bind_app = data.nickname
                    vm.init_code_matrix_color(vm.website.encryption_key,'rgba(18,255,36,0.2)')
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
                    vm.init_code_matrix_color(vm.website.website_encryption_key,'rgba(255,255,255,0.5)')

                    vm.login_status = data.username +' 即将进入主页面'

                    vm.effect_line_top='100'

                    if(vm.$route.path === '/project/website_main/website_app_square'){

                    }else{
                        setTimeout(function () {
//                            vm.effect_line_top='0'
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
                    if(vm.$route.path === '/project/website_main/website_index' || vm.$route.path.indexOf('m_website_main') > -1){

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
        beforeDestroy(){
            this.$root.eventHub.$off('socket_response')
        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            shuffle: function () {
                this.code_matrix_list = _.shuffle(this.code_matrix_list)
            },
            changewordscolor(word='ABCDEFG',color='black'){
                this.init_code_matrix_color(word,color)
            },
            init_code_matrix_color(datum_key,color='#fff'){
                var vm = this
//                setTimeout(function () {
                    vm.code_matrix_list.forEach(function (item,index) {
                        if(datum_key.indexOf(item.code)>=0){
                            Vue.nextTick(function () {
                                vm.code_matrix_list[index].color = color
                            })

                        }
                        else{
//                            vm.code_matrix_list[index].color = 'transparent'
                        }
                    })
//                },1000)

            },
            build_code_matrix_list(){
                var vm = this
                var new_code_matrix_list = []
                for(let i=0; i<this.chars.length; i++){
                    let new_code_matrix_item = {
                        id:i,
                        code:this.chars.charAt(i),
                    }
//                    new_code_matrix_list.push(new_code_matrix_item)
                    Vue.nextTick(function () {
                        vm.code_matrix_list.push(new_code_matrix_item)

                    })
                }
//                console.log('new_code_matrix_list',new_code_matrix_list)
//                this.code_matrix_list =  new_code_matrix_list
            },

            beforeEnter(el) {
//                console.log('beforeEnter')
                el.style.opacity = 0
                el.style.height = 0
//                el.style.paddingTop= '100px'
            },
            enter(el) {
//                console.log('enter')
                var delay = el.dataset.index * 50
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 1, height: '1.6em', paddingTop:0 },
                        { complete: done }
                    )
                }, delay)
            },
            afterEnter(el) {
                console.log('afterenter')
            },
            enterCancelled(el) {
                console.log('enterCancelled')
            },
            beforeLeave(el){
                console.log('befaoreLeave')
            },
            leave(el){
                console.log('Leave')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 0, height: 0 },
                        { complete: done }
                    )
                }, delay)
            },
            afterLeave(el){
                console.log('afterLeave')
            },
            leaveCancelled(el){
                console.log('leaveCancelled')
            },

        },

    }
</script>
<style>

    .code_matrix_container{
        display: flex;
        flex-wrap: wrap;
        width: 238px;
        margin-top: 10px;
    }
    .code_matrix_item {
        display: flex;
        justify-content: space-around;
        align-items: center;
        width: 25px;
        height: 25px;
        border: 1px solid #aaa;
        margin-right: -1px;
        margin-bottom: -1px;
    }
    .code_matrix_item:nth-child(3n) {
        margin-right: 0;
    }
    .code_matrix_item:nth-child(27n) {
        margin-bottom: 0;
    }
    .code_matrix_item-move {
        transition: transform 1s;
    }
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
