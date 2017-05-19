<template>

    <div class="top_router_view" style="background-color: transparent;" >
        <!--<p class="sec_router_tip">main</p>-->

        <div v-if="website.login_status && website.website_user.emailstatus" style="" >
            <chat v-if="appshow.chat"></chat>

            <settingbar style="z-index:110"></settingbar>
        </div>
        <div v-if="!server_status" style="line-height:40px;color:black !important; position: absolute;width: 100%;height: 100%;z-index:19;background-color: whitesmoke;text-align: center;font-weight: 800;font-size: 32px">
            <span style="position:absolute;top:20%;transform: translateX(-50%)" v-html="server_error_msg" > </span>
        </div>
        <tipbar :show="show_tip" :content="tipcontent" :time="tiptime" :width="tipwidth" :bgcolor="bgcolor"></tipbar>
        <keep-alive>
            <initsocket   :style="{position:'absolute',zIndex: '20',visibility:show_conn,transition: 'all 1s'}" ></initsocket>
        </keep-alive>
        <transition  name="fade" mode="out-in" >
            <!--暂时找不到方法阻止切换页面后eventhub.$on 多次触发的问题暂时使用keep-alive-->
            <!--已经解决，$off 取消注册事件后新加载的页面$on 同一事件要放到mounted，否则无法重新注册 原因beforeDestroy 在新组建created之后执行-->

            <!--<keep-alive>-->
                <router-view></router-view>
            <!--</keep-alive>-->
        </transition>

    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../../loading/effect_logo.vue'
    import initsocket from '../../communicationModule/initSocket.vue'
    import tipbar from '../../common/tipbar.vue'
    import { analysis_socket_response } from '../../../api/lib/helper/dataAnalysis'
    import chat from './apps/chat.vue'
    import settingbar from '../../common/settingBar.vue'

    export default {
        data(){
            return{
                show_conn:'hidden',
                show_tip:false,
                tipcontent:'',
                tiptime:'1500',
                tipwidth:'300',
                bgcolor:'rgba(73, 166, 169, 0.26)',
                server_status:1,
                server_error_msg:'',
                now_path:'',
            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo',
                'appshow'
            ])
        },
        created(){
            var vm = this



        },
        mounted(){

            var vm = this
            this.$root.eventHub.$on('base_socket_response',function (data) {
                var socket_response = analysis_socket_response(data)
//                if(socket_response.response_type !== 'ping'){
//                    console.log('main->socket_response','*** '+socket_response.response_type+' ***')
//                    console.log(socket_response)
//
//                }
                if(socket_response.response_type === 'checkloginbykey'){
//                    console.log('main->socket_reponse->checkloginbykey',socket_response)

                    if(!socket_response.error_response && socket_response.response_status){

                        var re_check_user = socket_response.response_init_data.user
                        vm.updateWebSite({
                            website_user:re_check_user
                        })

                        vm.$root.eventHub.$emit('init_website_status',1)

                        setTimeout(function () {
                            vm.$root.eventHub.$emit('init_login_status',re_check_user)
                        },1000)

                    }
                    else{
                        vm.$root.eventHub.$emit('init_website_status',1)
                        setTimeout(function () {
                            vm.$root.eventHub.$emit('init_login_status',0)
                        },1000)

                    }
                }
            })
            this.$root.eventHub.$on('checkloginbykey',function (data) {
//                console.log('on->checkloginbykey')
                vm.checkloginbykey()
            })

            this.listen_key_fun()
//            this.$root.eventHub.$emit('autoInit',1)
            this.$root.eventHub.$on('showtip',function (data) {
//                console.log('showtip->',data)
                vm.tipcontent = data.content
                vm.tipwidth = data.tipwidth
                vm.tiptime = data.tiptime
                vm.show_tip = true
                vm.bgcolor = data.bgcolor
                setTimeout(function () {
                    vm.show_tip = false
                    vm.tipwidth = '300'
                    vm.tipcontent = ''
                },vm.tiptime)
            })
            this.$root.eventHub.$on('conn_status',function (data) {
//                console.log('socket_error->',data)
                if(!data){
                    vm.server_status = 0
                    vm.server_error_msg = '连接服务器出错 <br> ' +
                        '-·ERROR_CODE·- 【'+data+'】 <br> ' +
                        '自动修复中<br>' +
                        '如果长时间此页面没反应<br>' +
                        ' 请 -·刷新·- 或<br>' +
                        '联系-·最高权限·-管理人员'
                }
                else{
                    vm.server_status = 1
                    vm.server_error_msg = ''
                }

            })


//            setInterval(function () {
//                vm.loop_check_status()
//            },2000)

        },
        beforeDestroy(){
            console.log('shadowsocks->beforeDestory')
            this.$root.eventHub.$off('socket_response')
        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            checkloginbykey(){
                var vm = this

                var params = {
                    to:null,
                    payload_type:'checkloginbykey',
                    payload_data:{
                        ip:vm.sysinfo.ip
                    },
                    yaf:{
                        module:'index',
                        controller:'website',
                        action:'checkloginbykey'
                    }
                }
                this.$root.eventHub.$emit('socket_send',params)
            },
            listen_key_fun(){
                var vm = this
                $(document).keypress(function(e){
//                    console.log(e.keyCode)
                    if(e.ctrlKey && e.which === 13 || e.which === 10) {
                        //要执行的操作
                        vm.show_conn = (vm.show_conn === 'visible')?'hidden':'visible'
                    }
                });
            },
//            loop_check_status_access(){
//                if(this.$route.path === '/project/website_main/website_index'){
//                }
//                else{
//                    this.$router.push('/project/website_main/website_index')
//                }
//            }

        },
        components:{

            initsocket,
            tipbar,
            chat,
            settingbar

        }
    }
</script>
<style>

</style>
