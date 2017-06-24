<template>
    <div class="top_router_view" id="business_client_ctrl" style="overflow: auto" >

        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick"  v-model="defaultTab">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >

                <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='公众号管理'" key="公众号管理">
                    <table_model v-loading="isloading"
                                 :currect_select="table_currect_select"
                                 :element-loading-text="loading_text"
                                 :search_sort_by="table_search_sort_by"
                                 :all_data_count="all_data_count"
                                 :table_data="table_model_data"
                                 :table_field="table_model_field"
                                 :info_transfer_action="info_transfer_action"

                    >

                    </table_model>

                </div>
                <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='内容管理'" key="内容管理">
                    <table_model v-loading="isloading"
                                 :currect_select="table_currect_select"
                                 :element-loading-text="loading_text"
                                 :search_sort_by="table_search_sort_by"
                                 :all_data_count="all_data_count"
                                 :table_data="table_model_data"
                                 :table_field="table_model_field"
                                 :info_transfer_action="info_transfer_action"

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
    import Vue from 'vue'
    import { mapGetters,mapActions } from 'vuex'
    import axios from 'axios'
    import {axios_config} from '../../../../api/axiosApi'
    import {dbResponseAnalysis2WidgetData} from '../../../../api/lib/helper/dataAnalysis'
    export default {
        data(){
            return {
                defaultTab:'公众号管理',
                tab_menu_list:
                    [
                        {
                            name:'公众号管理',
                            value:'公众号管理',
                        },
                        {
                            name : '内容管理',
                            value : '内容管理',
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
                    add:'WeimobCtrl/DescWeimob',
                    get:'WeimobCtrl/getWeimob',
                    update:'WeimobCtrl/updateweimob',
                    groupdel:'WeimobCtrl/GroupDelWeimob'
                },
                table_search_sort_by:{
                    page_size:20,
                    page:1,
                },
                table_search_sort_by_status:false,
                isloading:false,
                loading_text:'数据加载中',
                table_currect_select:'',
                email: ''

            }
        },
        watch:{
            table_search_sort_by: {
                handler: function (val, oldVal) {
                    if(this.defaultTab === '公众号数据'){
                        this.getTableInfo(JSON.stringify(this.table_search_sort_by))
                    }
                    else if(this.defaultTab === '内容管理'){
                        this.getTableInfoLevel(JSON.stringify(this.table_search_sort_by))
                    }


                },
                deep: true
            },
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
            this.report_api = this.wechat_marketing_store.apihost+'/'
            this.$root.eventHub.$emit('init_navmenu','w_m_b_weimob_ctrl')
            this.$root.eventHub.$on('currect_row_index',() => {
                this.show_page_model_ctrl_by_table = !this.show_page_model_ctrl_by_table
            })

            this.getTableInfo(JSON.stringify(this.table_search_sort_by))
            this.$root.eventHub.$on('refresh_table',function (data) {
//                console.log('on->refresh_table',data)
                if(data === 'resetselect'){
                    vm.table_search_sort_by = {
                        page_size:20,
                        page:1,
                    }
                }
                else{
                    if(vm.defaultTab === '客户数据'){
                        vm.getTableInfo(data)
                    }
                    else if(vm.defaultTab === '级别套餐'){
                        vm.getTableInfoLevel(data)
                    }
                }


            })
        },
        mounted(){
            var vm = this
            setTimeout(function () {
                vm.msg = 'hello world!!'
            }, 2000)
//            console.log('this.table_search_sort_by',this.table_search_sort_by)
        },
        beforeDestroy(){
            this.$root.eventHub.$off('refresh_businessinfo')
            this.$root.eventHub.$off('currect_row_index')

        },
        methods: {
            handleValidate: function (e) {
                var self = this
                // get validity instance
                var $validity = e.target.$validity
                // run validate method !!
                $validity.validate(function () {
                    // keep validation result from result property of validity instance
                    self.result = $validity.result
                })
            },
            tabclick(e){
                var vm = this
                this.page_model_data = {}
                this.table_model_data = []
                this.table_model_field =  {}
                this.all_data_count = 0
                this.table_search_sort_by = {
                    page_size:20,
                    page:1,
                }
                console.log(e.$el.dataset.name)
                if(e.$el.dataset.name === '公众号数据'){
                    vm.defaultTab = '公众号数据'
                    vm.getTableInfo(JSON.stringify(this.table_search_sort_by))
                }
                else if(e.$el.dataset.name === '内容管理'){
                    vm.defaultTab = '内容管理'
                    vm.getTableInfoLevel(JSON.stringify(this.table_search_sort_by))
                }



            },
            getTableInfo(search_sort_by){

                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_sort_by = JSON.parse(search_sort_by)

                    search_param.search_sort_by = JSON.stringify(search_sort_by)
                }
                search_param.rules = 1
                axios.post(this.report_api+this.info_transfer_action.get,search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        if(analysis_data.code+'' === '0'){
                            vm.table_model_field = analysis_data.rules
                            vm.table_model_data = analysis_data.data
                            vm.all_data_count =analysis_data.count
                            for (var index in analysis_data.rules){
                                if(analysis_data.rules[index].issearch){
//                                      进行响应式set key
//                                    console.log(vm.table_search_sort_by.hasOwnProperty(analysis_data.rules[index].name))
                                    if(!vm.table_search_sort_by.hasOwnProperty(analysis_data.rules[index].name)){

                                        vm.$set(vm.table_search_sort_by,analysis_data.rules[index].name,'')

                                    }
                                }
                            }
                            vm.info_transfer_action = {
                                add:'WeimobCtrl/DescWeimob',
                                get:'WeimobCtrl/getWeimob',
                                update:'WeimobCtrl/updateweimob',
                                groupdel:'WeimobCtrl/GroupDelWeimob'
                            }

//                            console.log(vm.table_search_sort_by)
                        }
//                        vm.isloading = false


                    })

            },
            getTableInfoLevel(search_sort_by){
                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_param.search_sort_by = search_sort_by
                }
                search_param.rules = 1
                axios.post(this.report_api+'getTableInfoLevel',search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        if(analysis_data.code+'' === '0'){
                            vm.table_model_field = analysis_data.rules
                            vm.table_model_data = analysis_data.data
                            vm.all_data_count =analysis_data.count
                            for (var index in analysis_data.rules){
                                if(analysis_data.rules[index].issearch){
//                                      进行响应式set key
//                                    console.log(vm.table_search_sort_by.hasOwnProperty(analysis_data.rules[index].name))
                                    if(!vm.table_search_sort_by.hasOwnProperty(analysis_data.rules[index].name)){

                                        vm.$set(vm.table_search_sort_by,analysis_data.rules[index].name,'')

                                    }
                                }
                            }
                            vm.info_transfer_action = {
                                add:'BusinessCtrl/DescbusinessinfoLevel',
                                get:'BusinessCtrl/getTableInfoLevel',
                                update:'BusinessCtrl/UpdateBusinessInfoLevel',
                                groupdel:'BusinessCtrl/GroupDelBusinessInfoLevel'
                            }

//                            console.log(vm.table_search_sort_by)
                        }
//                        vm.isloading = false


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
