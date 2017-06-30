<template>
    <div class="top_router_view" id="business_client_ctrl" style="overflow: auto" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick"  v-model="defaultTab">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >

                <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='客户数据' && defaultTab==='客户数据'" key="客户数据" :tab_name="item.name">
                    <!--<div class="ctrl_btn" style="position: absolute;right:10px;margin-top: -4px">-->
                        <!--<el-button type="primary" @click="add_business_info">新增客户</el-button>-->
                    <!--</div>-->
                    <!--:search_sort_by="table_search_sort_by"-->
                        <table_model v-loading="isloading"
                                     :currect_select="table_currect_select"
                                     :element-loading-text="loading_text"
                                     :search_sort_by="table_search_sort_by"
                                     :all_data_count="all_data_count"
                                     :table_data="table_model_data"
                                     :table_field="table_model_field"
                                     :info_transfer_action="info_transfer_action"
                                     :new_add_info="'新增客户'"
                        >

                        </table_model>

                </div>
                <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='级别套餐' && defaultTab==='级别套餐'" key="级别套餐" :tab_name="item.name">
                        <table_model v-loading="isloading" :currect_select="table_currect_select"
                                     :element-loading-text="loading_text" :search_sort_by="table_search_sort_by" :all_data_count="all_data_count" :table_data="table_model_data" :table_field="table_model_field"
                                     :new_add_info="'新增套餐'"
                                     :info_transfer_action="info_transfer_action">

                        </table_model>
                </div>
                <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='支付方式'" key="支付方式 && defaultTab==='支付方式'" :tab_name="item.name">
                    <table_model v-loading="isloading"
                                 :currect_select="table_currect_select"
                                 :element-loading-text="loading_text"
                                 :search_sort_by="table_search_sort_by"
                                 :all_data_count="all_data_count"
                                 :table_data="table_model_data"
                                 :table_field="table_model_field"
                                 :info_transfer_action="info_transfer_action"
                                 :new_add_info="'新增支付方式'"
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
                        {
                            name : '支付方式',
                            value : '支付方式',
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
                    add:'BusinessCtrl/Descbusinessinfo',
                    get:'BusinessCtrl/getBusinessInfo',
                    update:'BusinessCtrl/UpdateBusinessInfo',
                    groupdel:'BusinessCtrl/GroupDelBusinessInfo'
                },
                table_search_sort_by:{
                    page_size:20,
                    page:1,
                    search:{},
                    sorter:{},
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
                    console.log('change->',val)
                    this.refresh_table_data(JSON.stringify(this.table_search_sort_by))

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
            this.report_api = this.wechat_marketing_store.apihost+'BusinessCtrl/'
            this.$root.eventHub.$emit('init_navmenu','w_m_b_business_client_ctrl')
            this.$root.eventHub.$off('currect_row_index')
            this.$root.eventHub.$on('currect_row_index',() => {
                this.show_page_model_ctrl_by_table = !this.show_page_model_ctrl_by_table
            })

            this.getBusinessInfo(JSON.stringify(this.table_search_sort_by))
            this.$root.eventHub.$off('refresh_table')
            this.$root.eventHub.$on('refresh_table',function (data) {
                console.log('on->refresh_table')
                if(data === 'resetselect'){
                    vm.reset_search_sort_by()
                }
                else{
                    vm.refresh_table_data(data)
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
//            this.$root.eventHub.$off('refresh_businessinfo')
//            this.$root.eventHub.$off('currect_row_index')

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
                this.reset_search_sort_by()
                if(e.$el.dataset.name === '客户数据'){
                    vm.defaultTab = '客户数据'
//                    vm.getBusinessInfo(JSON.stringify(this.table_search_sort_by))
                }
                else if(e.$el.dataset.name === '级别套餐'){
                    vm.defaultTab = '级别套餐'
//                    vm.getBusinessInfoLevel(JSON.stringify(this.table_search_sort_by))
                }



            },
            getBusinessInfo(search_sort_by){
                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_sort_by = JSON.parse(search_sort_by)
                    search_param.search_sort_by = JSON.stringify(search_sort_by)
                }
                search_param.rules = 1
                axios.post(this.report_api+'Getbusinessinfo',search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
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
                            vm.info_transfer_action = {
                                    add:'BusinessCtrl/Descbusinessinfo',
                                    get:'BusinessCtrl/getBusinessInfo',
                                    update:'BusinessCtrl/UpdateBusinessInfo',
                                    groupdel:'BusinessCtrl/GroupDelBusinessInfo'
                            }
                            vm.table_model_field = analysis_data.rules
                            vm.table_model_data = analysis_data.data
                            vm.all_data_count =analysis_data.count

//                            console.log(vm.table_search_sort_by)
                        }
//                        vm.isloading = false


                    })

            },
            getBusinessInfoLevel(search_sort_by){
                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_sort_by = JSON.parse(search_sort_by)
                    search_param.search_sort_by = JSON.stringify(search_sort_by)
                }
                search_param.rules = 1
                axios.post(this.report_api+'getBusinessInfoLevel',search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
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
                            vm.info_transfer_action = {
                                add:'BusinessCtrl/DescbusinessinfoLevel',
                                get:'BusinessCtrl/getBusinessInfoLevel',
                                update:'BusinessCtrl/UpdateBusinessInfoLevel',
                                groupdel:'BusinessCtrl/GroupDelBusinessInfoLevel'
                            }
                            vm.table_model_field = analysis_data.rules
                            vm.table_model_data = analysis_data.data
                            vm.all_data_count =analysis_data.count

//                            console.log(vm.table_search_sort_by)
                        }
//                        vm.isloading = false


                    })

            },
            getPayInterface(search_sort_by){
                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_sort_by = JSON.parse(search_sort_by)
                    search_param.search_sort_by = JSON.stringify(search_sort_by)
                }
                search_param.rules = 1
                axios.post(this.report_api+'getPayInterface',search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
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
                            vm.info_transfer_action = {
                                add:'BusinessCtrl/DescPayInterface',
                                get:'BusinessCtrl/getPayInterface',
                                update:'BusinessCtrl/UpdatePayInterface',
                                groupdel:false
                            }
                            vm.table_model_field = analysis_data.rules
                            vm.table_model_data = analysis_data.data
                            vm.all_data_count =analysis_data.count

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
            refresh_table_data(data){
                console.log('refresh_table_data')
                if(this.defaultTab === '客户数据'){
                    this.getBusinessInfo(JSON.stringify(this.table_search_sort_by))
                }
                else if(this.defaultTab === '级别套餐'){
                    this.getBusinessInfoLevel(JSON.stringify(this.table_search_sort_by))
                }
                else if(this.defaultTab === '支付方式'){
                    this.getPayInterface(JSON.stringify(this.table_search_sort_by))
                }
            }

        },

    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
    .el-tabs__header{
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }

</style>
