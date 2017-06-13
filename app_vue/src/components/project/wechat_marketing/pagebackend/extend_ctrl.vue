<template>
    <div class="top_router_view" style="overflow: scroll" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick"  v-model="defaultTab">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >
                extend_ctrl


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
                defaultTab:'平台充值日志',
                tab_menu_list:
                    [
                        {
                            name:'平台充值日志',
                            value:'平台充值日志',
                        },
                        {
                            name : '功能数据统计',
                            value : '功能数据统计',
                        },
                        {
                            name : '公众号数据统计',
                            value : '公众号数据统计',
                        },
                        {
                            name : '内容审核',
                            value : '内容审核',
                        },
                        {
                            name : '图文审核',
                            value : '图文审核',
                        },
                        {
                            name : '敏感词过滤',
                            value : '敏感词过滤',
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
            this.report_api = this.wechat_marketing_store.apihost+'businessInfo/'

        },
        mounted(){



        },
        beforeDestroy(){

        },
        methods: {
            tabclick(e){
                var vm = this
                this.siteinfo = {}

            }
        },

    }
</script>
<style>
    .el-tabs__header{
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }

</style>
