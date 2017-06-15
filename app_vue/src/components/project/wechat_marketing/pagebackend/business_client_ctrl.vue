<template>
    <div class="top_router_view" id="business_client_ctrl" style="overflow: auto" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick"  v-model="defaultTab">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >

                <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='客户数据'" key="客户数据">
                    <!--<div class="ctrl_btn" style="position: absolute;right:10px;margin-top: -4px">-->
                        <!--<el-button type="primary" @click="add_business_info">新增客户</el-button>-->
                    <!--</div>-->
                    <table_model v-loading="isloading" :currect_select="table_currect_select"
                                 :element-loading-text="loading_text" :search_sort_by="table_search_sort_by" :all_data_count="all_data_count" :table_data="table_model_data" :table_field="table_model_field" :info_transfer_action="info_transfer_action">

                    </table_model>
                    <!--<transition name="fade-up">-->
                        <!--<page_model v-if="show_page_model_ctrl_by_table"-->
                                <!--:final_update_action="'BusinessCtrl/UpdateBusinessInfo'"-->
                                <!--:final_update_btn_desc="'提交数据'"-->
                                <!--:page_data="page_model_data"-->
                                <!--style="position:absolute;;width: 98%;z-index:100;text-align: center;bottom: 0px;" ></page_model>-->
                    <!--</transition>-->
                    <transition name="fade-up">
                        <page_model v-if="show_adddata_page_model_ctrl"
                                    :final_update_action="'BusinessCtrl/UpdateBusinessInfo'"
                                    :final_update_btn_desc="'新增数据'"
                                    :page_data="adddata_page_model_data"
                                    style="position:absolute;;width: 98%;z-index:100;text-align: center;bottom: 0px;" ></page_model>
                    </transition>
                </div>
                <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-else="item.name==='级别套餐'" key="级别套餐">
                    级别套餐
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
                defaultTab:'客户数据',
                tab_menu_list:
                    [
                        {
                            name:'客户数据',
                            value:'客户数据',
                        },
                        {
                            name : '级别套餐',
                            value : '级别套餐',
                        },
                    ],
                adddata_page_model_data:{},
                page_model_data:{},
                table_model_data:[],
                table_model_field:{},
                all_data_count:0,
                show_page_model_ctrl_by_table:false,
                show_adddata_page_model_ctrl:false,
                info_transfer_action:{
                    add:'BusinessCtrl/Getbusinesscolsinfo',
                    get:'BusinessCtrl/getBusinessInfo',
                    update:'BusinessCtrl/UpdateBusinessInfo',
                    groupdel:'BusinessCtrl/GroupDelBusinessInfo'
                },
                table_search_sort_by:{
                    page_size:20,
                    page:1,
                },
                table_search_sort_by_status:false,
                isloading:false,
                loading_text:'数据加载中',
                table_currect_select:''

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
            this.report_api = this.wechat_marketing_store.apihost+'BusinessCtrl/'
            this.$root.eventHub.$emit('init_navmenu','w_m_b_business_client_ctrl')
            this.$root.eventHub.$on('currect_row_index',() => {
                this.show_page_model_ctrl_by_table = !this.show_page_model_ctrl_by_table
            })

            this.getBusinessInfo(JSON.stringify(this.table_search_sort_by))
            this.$root.eventHub.$on('refresh_businessinfo',function (data) {
                if(data === 'reset'){
                    vm.table_search_sort_by = {
                        page_size:20,
                            page:1,
                    }
                }
                else{
                    vm.getBusinessInfo(data)
                }
//                console.log(JSON.parse(data).hasOwnProperty('kkk'))
//                console.log(JSON.parse(data).hasOwnProperty('username'))
//                console.log('this->table_search_sort_by',JSON.stringify(vm.table_search_sort_by))
//                console.log('on->refresh_businessinfo',data)
//                data = JSON.parse(data)
//                this.table_currect_select = data.currect_select

            })
        },
        mounted(){
            var vm = this

//            console.log('this.table_search_sort_by',this.table_search_sort_by)



        },
        beforeDestroy(){
            this.$root.eventHub.$off('refresh_businessinfo')
            this.$root.eventHub.$off('currect_row_index')

        },
        methods: {

            tabclick(e){
                var vm = this
                this.page_model_data = {}
                this.table_model_data = []
                if(e.$el.dataset.name === '客户数据'){
                    this.getBusinessInfo(JSON.stringify(this.table_search_sort_by))
                }
                if(e.$el.dataset.name === '级别套餐'){
                    this.getBusinessInfo(JSON.stringify(this.table_search_sort_by))
                }

            },
            getBusinessInfo(search_sort_by){
                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_param.search_sort_by = search_sort_by
                }
                search_param.rules = 1
//                vm.isloading = true
                axios.post(this.report_api+'Getbusinessinfo',search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        if(analysis_data.code+'' === '0'){
//                            var content = analysis_data.widgetdata[0]
//                            console.log(analysis_data)
                            vm.table_model_field = analysis_data.rules
                            vm.table_model_data = analysis_data.data
                            vm.all_data_count =analysis_data.count
                            for (var index in analysis_data.rules){
                                if(analysis_data.rules[index].issearch){
//                                      进行响应式set key
//                                    vm.table_search_sort_by[analysis_data.rules[index].name] = ''
//                                    console.log(vm.table_search_sort_by.hasOwnProperty(analysis_data.rules[index].name))
                                    if(!vm.table_search_sort_by.hasOwnProperty(analysis_data.rules[index].name)){

                                        vm.$set(vm.table_search_sort_by,analysis_data.rules[index].name,'')

                                    }
                                }
                            }

//                            console.log(vm.table_search_sort_by)
                        }
//                        vm.isloading = false


                    })
            },
            add_business_info(){
                var vm = this
                axios.post(this.report_api+'Getbusinesscolsinfo',{rules:1},axios_config)
                    .then((res) => {
//                            console.log(res.data)
                        let  analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        if(analysis_data.code+'' === '0'){
                            var content = analysis_data.widgetdata[0]
                            vm.adddata_page_model_data = {
                                title:'新增客户信息',
                                content:content
                            }
                            vm.show_adddata_page_model_ctrl=true

                        }

                    })
            }
        },

    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
    .el-tabs__header{
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }

</style>
