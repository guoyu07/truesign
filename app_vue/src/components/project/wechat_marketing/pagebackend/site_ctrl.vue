<template>
    <div class="top_router_view" style="overflow: scroll" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >
                        <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='后台首页'">

                                <page_model style="width: 600px;display: inline-block;vertical-align: top" ></page_model>
                                <page_model style="width: 600px;display: inline-block;vertical-align: top" ></page_model>
                                {{ wechat_marketing_store }}


                        </div>

            </el-tab-pane>
        </el-tabs>

    </div>
</template>


<script>

    import page_model from '../../../common/page_model.vue'
    import { mapGetters,mapActions } from 'vuex'
    import axios from 'axios'
    import {axios_config} from '../../../../api/axiosApi'
    const index_api = apihost+'index/'
    export default {
        data(){
            return {
                tab_menu_list:
                    [
                            {
                                name : '后台首页',
                                value : '后台首页',
                                title:'RW-CMS 管理系统',
                                list_msg:[
                                    {
                                        key:'服务器环境',
                                        value:'[WINNT]Apache/2.4.10 (Win32) OpenSSL/0.9.8zb mod_fcgid/2.3.9 MySql:5.5.53-log php:5.6.27   Zend版本：2.6.0'
                                    },
                                    {
                                        key:'服务器IP',
                                        value:'localhost(127.0.0.1:8081)'
                                    }
                                ],
                                cb_btn:{
                                    des:'',
                                    key:'检查更新',
                                    api:'www.baidu.com'
                                }
                            },
                            {
                                name:'站点设置',
                                value:'站点设置',
                                title:'RW-CMS 管理系统',
                                list_msg:[
                                    {
                                        key:'服务器环境',
                                        value:'[WINNT]Apache/2.4.10 (Win32) OpenSSL/0.9.8zb mod_fcgid/2.3.9 MySql:5.5.53-log php:5.6.27   Zend版本：2.6.0'
                                    },
                                    {
                                        key:'服务器IP',
                                        value:'localhost(127.0.0.1:8081)'
                                    }
                                ],
                                cb_btn:{
                                    key:'检查更新',
                                    api:'www.baidu.com'
                                }
                            },
                            {
                                name:'数据库设定',
                                value:'数据库设定',
                                title:'RW-CMS 管理系统',
                                list_msg:[
                                    {
                                        key:'服务器环境',
                                        value:'[WINNT]Apache/2.4.10 (Win32) OpenSSL/0.9.8zb mod_fcgid/2.3.9 MySql:5.5.53-log php:5.6.27   Zend版本：2.6.0'
                                    },
                                    {
                                        key:'服务器IP',
                                        value:'localhost(127.0.0.1:8081)'
                                    }
                                ],
                                cb_btn:{
                                    key:'检查更新',
                                    api:'www.baidu.com'
                                }
                            }
                    ]

            }
        },
        props: {

        },
        components: {
            page_model
        },
        computed: {
            ...mapGetters([
                'wechat_marketing_store',
            ])
        },
        created(){

        },
        mounted(){


        },
        beforeDestroy(){

        },
        methods: {
            tabclick(e){
                if(e.$el.dataset.name === '后台首页'){

                }
            }
        },

    }
</script>
<style>
    .el-tabs__header{
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }

</style>
