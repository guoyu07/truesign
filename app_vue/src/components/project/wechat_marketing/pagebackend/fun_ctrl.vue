<template>
    <div class="top_router_view" style="overflow: scroll" >
        <el-tabs type="border-card" style="background-color: #dcdcdc;box-shadow: none" @tab-click="tabclick" v-model="defaultTab">
            <el-tab-pane v-for="(item,index) in tab_menu_list" :key="item" :label="item.value" :data-name="item.name" :name="item.name" >
                fun_ctrl


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
