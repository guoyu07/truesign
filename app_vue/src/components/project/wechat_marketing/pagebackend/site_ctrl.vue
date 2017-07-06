<template>
    <div class="top_router_view" style="overflow: scroll" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick" v-model="defaultTab" >
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >
                        <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='后台首页'" key="后台首页">

                            <page_model
                                    :show_phone_model="false"
                                    :final_update_btn_desc="'刷新数据'"
                                    :page_data="siteinfo"
                                    :final_update_action="'SiteInfo/GetEnv'"
                                    style="width: 100%;min-width:600px;display: inline-block;vertical-align: top" ></page_model>


                        </div>
                        <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-else-if="item.name==='站点设置'" key="站点设置">

                            <page_model :page_data="siteinfo" style="width: 100%;min-width:600px;display: inline-block;vertical-align: top" ></page_model>




                        </div>
                        <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-else-if="item.name==='管理员账户管理'" key="管理员账户管理">

                            <table_model v-loading="tableParams.isloading"
                                         :currect_select="tableParams.table_currect_select"
                                         :element-loading-text="tableParams.loading_text"
                                         :search_sort_by="table_search_sort_by"
                                         :all_data_count="tableParams.all_data_count"
                                         :table_data="tableParams.table_model_data"
                                         :table_field="tableParams.table_model_field"
                                         :info_transfer_action="tableParams.info_transfer_action"
                                         :new_add_info="'新增管理员账户'"
                            >

                            </table_model>


                        </div>

            </el-tab-pane>
        </el-tabs>

    </div>
</template>


<script>

    import page_model from '../../../common/page_model.vue'
    import table_model from '../../../common/table_model.vue'
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
                            {
                                name:'管理员账户管理',
                                value:'管理员账户管理'
                            },

                    ],
                siteinfo:{},

                tableParams:{
                    table_model_data:[],
                    table_model_field:{},
                    all_data_count:0,
                    show_page_model_ctrl_by_table:false,
                    show_adddata_page_model_ctrl:false,
                    info_transfer_action:{
                        add:'BusinessCtrl/Descbusinessinfo',
                        get:'BusinessCtrl/getBusinessInfo',
                        update:'BusinessCtrl/UpdateBusinessInfo',
                        groupdel:'BusinessCtrl/GroupDelBusinessInfo'
                    },
                    isloading:false,
                    loading_text:'数据加载中',
                    table_currect_select:'',
                },
                table_search_sort_by:{
                    page_size:20,
                    page:1,
                    search:{},
                    sorter:{},
                },
            }
        },
        props: {
        },
        components: {
            page_model,
            table_model
        },
        computed: {
            ...mapGetters([
                'wechat_marketing_store',
            ])
        },
        created(){
            var vm = this
            this.report_api = this.wechat_marketing_store.apihost+'siteinfo/'
            this.getBaseInfo()
            this.$root.eventHub.$on('refresh_page_model',function () {
                if(vm.defaultTab === '站点设置'){
                    vm.getSiteCfg()
                }
                if(vm.defaultTab === '后台首页'){
                    vm.getBaseInfo()
                }
            })
            this.$root.eventHub.$on('refresh_table',function (data) {
                console.log('on->refresh_table')
                vm.getMaster(JSON.stringify(this.table_search_sort_by))


            })

        },
        mounted(){



        },
        beforeDestroy(){
            this.$root.eventHub.$off('refresh_page_model')

        },
        methods: {
            tabclick(e){
                var vm = this

                this.siteinfo = {}
                this.defaultTab = e.$el.dataset.name

                if(e.$el.dataset.name === '站点设置'){
                    this.getSiteCfg()
                }
                if(e.$el.dataset.name === '后台首页'){
                    this.getBaseInfo()
                }
                if(e.$el.dataset.name === '管理员账户管理'){

                    this.reset_search_sort_by()
                    this.getMaster(JSON.stringify(this.table_search_sort_by))
                }


            },
            getBaseInfo(){
                var vm = this
                axios.post(this.report_api+'getenv',{rules:1},axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data.response)
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
                axios.post(this.report_api+'getsitebaseconfig',{rules:1},axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data.response)
                        console.log('analysis_data.widgetdata',analysis_data.widgetdata)

                        if(analysis_data.code+'' === '0'){

                            var siteinfo_content = analysis_data.widgetdata[0]
                            vm.siteinfo = {
                                title:'站点配置信息',
                                content:siteinfo_content
                            }
                        }

                    })
            },
            getMaster(search_sort_by){
                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_sort_by = JSON.parse(search_sort_by)
                    search_param.search_sort_by = JSON.stringify(search_sort_by)
                }
                search_param.rules = 1
                axios.post(this.report_api+'getMaster',search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data.response)
                        console.log('getBusinessInfoLevel',analysis_data)
                        if(analysis_data.code+'' === '0'){

                            for (let index in analysis_data.searchWidget){
//                                      进行响应式set key
                                //1.0版本
                                if(!vm.table_search_sort_by.search.hasOwnProperty(analysis_data.searchWidget[index].search_key)){

                                    vm.$set(vm.table_search_sort_by.search,analysis_data.searchWidget[index].search_key,analysis_data.searchWidget[index])

                                }
                            }
                            for (let index in analysis_data.sorterWidget){
//                                      进行响应式set key
                                //1.0版本
                                if(!vm.table_search_sort_by.sorter.hasOwnProperty(analysis_data.sorterWidget[index].key)){
                                    vm.$set(vm.table_search_sort_by.sorter,analysis_data.sorterWidget[index].key,analysis_data.sorterWidget[index].way)

                                }
                            }
                            vm.tableParams.info_transfer_action = {
                                add:'siteinfo/DescMaster',
                                get:'siteinfo/GetMaster',
                                update:'siteinfo/UpdateMaster',
                                groupdel:false
                            }
                            vm.tableParams.table_model_field = analysis_data.rules
                            vm.tableParams.table_model_data = analysis_data.data
                            vm.tableParams.all_data_count =analysis_data.count

//                            console.log(vm.table_search_sort_by)
                        }
//                        vm.isloading = false
                    })
            },
            reset_search_sort_by(){
                this.table_search_sort_by = {
                    page_size:20,
                    page:1,
                    search:{},
                    sorter:{},
                }
            },

        },

    }
</script>
<style>
    .el-tabs__header{
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }

</style>
