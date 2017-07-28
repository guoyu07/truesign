<template>
    <div class="top_router_view" style="background-color: gainsboro;text-align: center;overflow: hidden;">

        <p class="top_router_tip" style="opacity: 0.2">wechat_marketing pagebackend</p>

        <navmenu :logo="logo" :menulist="menulist"
                 :navmenu_theme_color="navmenu_theme_color" :menu_item_color="'#cbcfe4'"
                 :showaccount="false"
                 :account_info="'/wechat_marketing_backend/accountInfo'"
        ></navmenu>


        <div style="min-width: 1200px;height:100%;width: 100%;background-color: transparent;margin: 0 auto;padding-top: 65px;">
            <transition name="el-zoom-in-top">
                <router-view></router-view>
            </transition>
        </div>
    </div>

</template>


<script>
    import navmenu from '../../common/navmenu.vue'
    import breadcrumb from '../../common/breadcrumb .vue'
    import {socket_server_apihost} from '../../../app_config/base_config'
    import {isEmpty} from '../../../api/lib/helper/dataAnalysis'
    import {mapGetters, mapActions} from 'vuex'

    export default {
        data(){
            return {
                logo: 'http://cdn.iamsee.com/logo/websocket.png',
                menulist: {
                    ssm_user: '用户管理',
                    ssm_app: 'app管理',
                    ssm_authlog: '认证日志',
                    ssm_msglog: '消息日志',
                },
                navmenu_theme_color: '#324157',

            }
        },
        props: {},
        components: {
            navmenu,
            breadcrumb

        },
        computed: {
            ...mapGetters([
                'socket_server_store'
            ])

        },
        created(){

            this.updateSocket_server_store({
                apihost: socket_server_apihost
            })
        },
        mounted(){
            var vm = this
//            this.$root.eventHub.$on('changeNavMenu', function (data) {
//                switch (data) {
//                    case 0:
//                        console.log(data)
//                        break;
//                    case 1:
//                        console.log(data)
//
//                        break;
//                    case 2:
//                        console.log(data)
//
//                        break;
//                    case 3:
//                        console.log(data)
//
//                        break;
//                    default:
//                        console.log(data)
//
//                }
//            })

        },
        updated(){

        },
        beforeDestroy(){

        },
        methods: {
            ...mapActions([
                'updateSocket_server_store'
            ]),


        },

    }
</script>
<style>

    .code_matrix_container {
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

    .fade-show-enter-active {
        transition: all 1s;
        opacity: 1;

    }

    .fade-show-enter {
        transition: all 1s;

        opacity: 0;

    }

    .fade-show-leave {
        transition: all 1s;

        opacity: 1;
    }

    .fade-show-leave-active {
        transition: all 1s;

        opacity: 0;

    }

    .el-menu li:hover {
        background-color: #9e9e9e !important;
    }

    .el-tabs__item.is-active {
        background-color: #848484 !important;
    }

    #navmenu li.el-menu-item {
        font-size: 16px !important;
    }
</style>
