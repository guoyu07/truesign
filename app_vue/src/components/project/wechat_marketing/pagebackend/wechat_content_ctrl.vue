<template>
    <div class="top_router_view" style="overflow: scroll" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >
                wechat_content_


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
