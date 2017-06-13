<template>
    <div class="top_router_view" style="overflow: auto" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick"  v-model="defaultTab">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >

                <div style="width: 100%;height: auto;min-height: 600px;text-align: left;" v-if="item.name==='客户数据'" key="客户数据">
                    <table_model>

                    </table_model>
                    <transition name="fade-up">
                        <page_model v-if="show_page_model_ctrl_by_table"
                                :final_update_action="'BusinessCtrl/UpdateBusinessInfo'"
                                :final_update_btn_desc="'提交数据'"
                                :page_data="page_model_data"
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
                page_model_data:{},
                show_page_model_ctrl_by_table:false
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
            this.report_api = this.wechat_marketing_store.apihost+'BusinessCtrl/'
            this.$root.eventHub.$emit('init_navmenu','w_m_b_business_client_ctrl')
            this.$root.eventHub.$on('currect_row_index',() => {
                this.show_page_model_ctrl_by_table = !this.show_page_model_ctrl_by_table
            })
            this.getBusinessInfo()

        },
        mounted(){
            var vm = this
            this.$root.eventHub.$on('close_page_model',() => {
                this.show_page_model_ctrl_by_table = false
            })


        },
        beforeDestroy(){

        },
        methods: {

            tabclick(e){
                var vm = this
                this.page_model_data = {}
                this.siteinfo = {}
                if(e.$el.dataset.name === '客户数据'){
                    this.getBusinessInfo()
                }
                if(e.$el.dataset.name === '级别套餐'){
                    this.getBusinessInfo()
                }

            },
            getBusinessInfo(){
                var vm = this
                axios.post(this.report_api+'Getbusinessinfo',{rules:1},axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        if(analysis_data.code+'' === '0'){
                            var content = analysis_data.widgetdata[0]
                            vm.page_model_data = {
                                title:'客户数据',
                                content:content
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
