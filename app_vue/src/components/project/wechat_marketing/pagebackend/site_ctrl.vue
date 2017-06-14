<template>
    <div class="top_router_view" style="overflow: scroll" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick" v-model="defaultTab" >
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >
                        <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='后台首页'" key="后台首页">

                            <page_model
                                    :final_update_btn_desc="'刷新数据'"
                                    :page_data="siteinfo"
                                    :final_update_action="'SiteInfo/GetEnv'"
                                    style="width: 100%;min-width:600px;display: inline-block;vertical-align: top" ></page_model>


                        </div>
                        <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-else-if="item.name==='站点设置'" key="站点设置">

                            <page_model :page_data="siteinfo" style="width: 100%;min-width:600px;display: inline-block;vertical-align: top" ></page_model>




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
    import {dbResponseAnalysis2WidgetData} from '../../../../api/lib/helper/dataAnalysis'
    export default {
        data(){
            return {
                report_api : '',
                defaultTab:'后台首页',
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

                    ],
                siteinfo:{}

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
            this.report_api = this.wechat_marketing_store.apihost+'siteinfo/'
            this.getBaseInfo()

        },
        mounted(){



        },
        beforeDestroy(){

        },
        methods: {
            tabclick(e){
                var vm = this
                this.siteinfo = {}
                if(e.$el.dataset.name === '站点设置'){
                    this.getSiteCfg()
                }
                if(e.$el.dataset.name === '后台首页'){
                   this.getBaseInfo()
                }


            },
            getBaseInfo(){
                var vm = this
                axios.post(this.report_api+'getenv',{rules:1},axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        if(analysis_data.code+'' === '0'){
                            var siteinfo_content = analysis_data.widgetdata[0]
                            vm.siteinfo = {
                                title:'服务器信息',
                                content:siteinfo_content
                            }
                        }

                    })
            },
            getSiteCfg(){
                var vm = this
                axios.post(this.report_api+'getbasesiteconfig',{rules:1},axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        if(analysis_data.code+'' === '0'){
                            var siteinfo_content = analysis_data.widgetdata[0]
                            vm.siteinfo = {
                                title:'站点配置信息',
                                content:siteinfo_content
                            }
                        }

                    })
            }

        },

    }
</script>
<style>
    .el-tabs__header{
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }

</style>
