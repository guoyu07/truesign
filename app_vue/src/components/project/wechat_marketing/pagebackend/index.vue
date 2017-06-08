<template>
    <div class="top_router_view" style="background-color: gainsboro;text-align: center;overflow: hidden;" >
        <p class="top_router_tip" style="opacity: 0.2">wechat_marketing pagebackend</p>

        <navmenu :logo="logo" :menulist="menulist" :navmenu_theme_color="navmenu_theme_color" :menu_item_color="'#cbcfe4'" :user_scope="user_scope"></navmenu>


        <div style="min-width: 1200px;width: 100%;background-color: transparent;height: 100%;margin: 0 auto;padding-top: 65px;">
            <transition name="el-zoom-in-top">
            <router-view> </router-view>
            </transition>
        </div>

    </div>
</template>



<script>
    import navmenu from '../../../common/navmenu.vue'
    import breadcrumb from '../../../common/breadcrumb .vue'

    export default {
        data(){
            return{
                logo:'https://res.wx.qq.com/mpres/htmledition/images/bg/bg_logo318e8e.png',
                menulist:{
                    site_ctrl:'站点管理',
                    business_client_ctrl:'客户管理',
                    weimob_ctrl:'微信公众号管理',
                    wechat_content_ctrl:'内容管理',
                    fun_ctrl:'功能管理',
                    agent_ctrl:'代理商管理',
                    extend_ctrl:'扩展管理'


                },
                navmenu_theme_color:'#324157',
                user_scope:{
                    img:'http://truesign-app.oss-cn-beijing.aliyuncs.com/logo/logo_min.png',
                    name:'IAMSEE'
                },
            }
        },
        props: {

        },
        components:{
            navmenu,
            breadcrumb

        },
        computed: {


        },
        created(){

        },
        mounted(){
            var vm = this
            this.$root.eventHub.$on('changeNavMenu',function (data) {
                switch (data){
                    case 0:
                        console.log(data)
                        break;
                    case 1:
                        console.log(data)

                        break;
                    case 2:
                        console.log(data)

                        break;
                    case 3:
                        console.log(data)

                        break;
                    default:
                        console.log(data)


                }
            })


        },
        beforeDestroy(){

        },
        methods:{


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
