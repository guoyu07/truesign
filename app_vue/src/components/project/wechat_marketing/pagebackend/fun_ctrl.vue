<template>
    <div class="top_router_view" id="business_client_ctrl" style="overflow: auto" >

        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick"  v-model="defaultTab">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >

                <div class="tab_content"  v-if="item.name==='关于我们模块'" key="关于我们模块" style="padding-left:80px ">
                    <page_model
                            :show_phone_model="true"
                            :final_update_btn_desc="'更新数据'"
                            :page_data="page_model_data"
                            :final_update_action="'FunCtrl/UpdateFun'"
                            :page_model_padding_left="'0'"

                            style="width: 100%;min-width:600px;display: inline-block;vertical-align: top" ></page_model>
                </div>
                <div class="tab_content" v-if="item.name==='留言板模块'" key="留言板模块" style="padding-left:80px ">
                    <page_model
                                :show_phone_model="true"
                                :final_update_btn_desc="'更新数据'"
                                :page_data="page_model_data"
                                :final_update_action="'FunCtrl/UpdateFun'"
                                :page_model_padding_left="'0'"
                                style="width: 100%;min-width:600px;display: inline-block;vertical-align: top" ></page_model>

                </div>
                <div class="tab_content" style="" v-if="item.name==='其他功能模块'" key="其他功能模块">
                    <table_model v-loading="isloading"
                                 :currect_select="table_currect_select"
                                 :element-loading-text="loading_text"
                                 :search_sort_by="table_search_sort_by"
                                 :all_data_count="all_data_count"
                                 :table_data="table_model_data"
                                 :table_field="table_model_field"
                                 :info_transfer_action="info_transfer_action"
                                 :new_add_info="'新增功能'"
                                 :groupdelable="false"
                                 :show_phone_model="true"
                                 :show_phone_model_key="'fun_uri'"
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
    import phone_model from '../../../common/phone_model.vue'
    export default {
        data(){
            return {
                defaultTab:'关于我们模块',
                tab_menu_list:
                    [
                        {
                            name:'关于我们模块',
                            value:'关于我们模块',
                        },
                        {
                            name : '留言板模块',
                            value : '留言板模块',
                        },
                        {
                            name : '其他功能模块',
                            value : '其他功能模块',
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
                    add:'FunCtrl/DescFun',
                    get:'FunCtrl/getFun',
                    update:'FunCtrl/UpdateFun',
                    groupdel:'WeimobCtrl/GroupDelWeimob'
                },
                table_search_sort_by:{
                    page_size:20,
                    page:1,
                    search:{},
                    sorter:{},
                },
                isloading:false,
                loading_text:'数据加载中',
                table_currect_select:'',
                email: '',


            }
        },
        watch:{
            table_search_sort_by: {
                handler: function (val, oldVal) {

                    this.refresh_data()


                },
                deep: true
            },
        },
        props: {
        },
        components: {
            page_model,
            table_model,
            phone_model
        },
        computed: {
            ...mapGetters([
                'wechat_marketing_store',
            ])
        },
        created(){
            var vm = this
            this.report_api = this.wechat_marketing_store.apihost+'/'
            this.$root.eventHub.$emit('init_navmenu','w_m_b_fun_ctrl')
            this.$root.eventHub.$on('currect_row_index',() => {
                this.show_page_model_ctrl_by_table = !this.show_page_model_ctrl_by_table
            })
            this.getAboutus()
            this.$root.eventHub.$on('refresh_table',function (data) {
//                console.log('on->refresh_table',data)
                if(data === 'resetselect'){
                    vm.reset_search_sort_by()
                }
                else{
                    vm.refresh_data()
                }


            })
            this.$root.eventHub.$on('refresh_page_model',function () {
                vm.refresh_data()
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
                this.reset_search_sort_by()
                this.page_model_data = {}
                vm.defaultTab = e.$el.dataset.name
                this.refresh_data



            },
            getAboutus(search_sort_by){

                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_sort_by = JSON.parse(search_sort_by)

                    search_param.search_sort_by = JSON.stringify(search_sort_by)
                }
                search_param.rules = 1
                search_param.fun_keyword = 'aboutus'
                axios.post(this.report_api+this.info_transfer_action.get,search_param,axios_config)
                    .then((res) => {
                      if(res.data.code === 0){
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data.response)

                                var siteinfo_content = analysis_data.widgetdata[0]
//
                            for (var index in siteinfo_content){
                                    if(siteinfo_content[index].key  === 'fun_keyword'){
                                        siteinfo_content[index].value = 'aboutus'
                                        siteinfo_content[index].able_modify = false
                                    }
                                if(siteinfo_content[index].key  === 'fun_title'){
                                    siteinfo_content[index].value = '关于我们'
                                    siteinfo_content[index].able_modify = false
                                }
                            }
                            console.log('content',siteinfo_content)
                                vm.page_model_data = {
                                    title:'关于我们配置',
                                    content:siteinfo_content
                                }
//                            console.log(vm.table_search_sort_by)
                        }
                      else {
                        vm.$notify.error({
                          title: '失败',
                          message: res.data.code + ' ' + res.data.desc,
                          offset: 100,
                          duration: '2000'
                        });
                      }
//


                    })

            },
            getMessageBoard(search_sort_by){
                var vm = this

                var search_param = {}

                if(search_sort_by){
                    search_param.search_sort_by = search_sort_by
                }
                search_param.rules = 1
                search_param.fun_keyword = 'messageboard'
                axios.post(this.report_api+this.info_transfer_action.get,search_param,axios_config)
                    .then((res) => {
                        if(res.data.code === 0){
                          let analysis_data = dbResponseAnalysis2WidgetData(res.data.response)

                          var siteinfo_content = analysis_data.widgetdata[0]
//
                            for (var index in siteinfo_content){
                                if(siteinfo_content[index].key  === 'fun_keyword'){
                                    siteinfo_content[index].value = 'messageboard'
                                    siteinfo_content[index].able_modify = false
                                }
                                if(siteinfo_content[index].key  === 'fun_title'){
                                    siteinfo_content[index].value = '留言板'
                                    siteinfo_content[index].able_modify = false
                                }
                            }
                            console.log('content',siteinfo_content)
                            vm.page_model_data = {
                                title:'关于我们配置',
                                content:siteinfo_content
                            }
//                            console.log(vm.table_search_sort_by)
                        }else {
                          vm.$notify.error({
                            title: '失败',
                            message: res.data.code + ' ' + res.data.desc,
                            offset: 100,
                            duration: '2000'
                          });
                        }

//                        vm.isloading = false


                    })

            },
            getOtherFun(search_sort_by){
                var vm = this
                var search_param = {}
                if(search_sort_by){
                    search_param.search_sort_by = search_sort_by
                }
                search_param.rules = 1
                axios.post(this.report_api+this.info_transfer_action.get,search_param,axios_config)
                    .then((res) => {
                        if(res.data.code === 0){
                          let analysis_data = dbResponseAnalysis2WidgetData(res.data.response)

//                            console.log('analysis_data.searchWidget',analysis_data.searchWidget)
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
                                add:'FunCtrl/DescFun',
                                get:'FunCtrl/getFun',
                                update:'FunCtrl/UpdateFun',
                                groupdel:'false'
                            }
                            vm.table_model_field = analysis_data.rules
                            vm.table_model_data = analysis_data.data
                            vm.all_data_count =analysis_data.count

//                            console.log(vm.table_search_sort_by)
                        }
                        else {
                          vm.$notify.error({
                            title: '失败',
                            message: res.data.code + ' ' + res.data.desc,
                            offset: 100,
                            duration: '2000'
                          });
                        }

                    })

            },
            refresh_data(){
                var vm = this
                if(vm.defaultTab === '关于我们模块'){
                    this.table_search_sort_by.search['fun_keyword']={ search_value:'aboutus' }
                    vm.getAboutus(JSON.stringify(this.table_search_sort_by))
                }
                else if(vm.defaultTab === '留言板模块'){
                    this.table_search_sort_by.search['fun_keyword']={ search_value:'messageboard' }
                    vm.getMessageBoard(JSON.stringify(this.table_search_sort_by))
                }

                else if(this.defaultTab === '其他功能模块'){
                    vm.getOtherFun(JSON.stringify(this.table_search_sort_by))
                }
            },
            reset_search_sort_by(){
                this.table_search_sort_by = {
                    page_size:20,
                    page:1,
                    search:{},
                    sorter:{},
                }
            }

        },

    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
    .el-tabs__header{
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }
    .tab_content{
        width: 100%;height: auto;min-height: 600px;text-align: left;
        overflow-y scroll
    }

</style>
