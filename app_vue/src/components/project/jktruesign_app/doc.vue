<template>
    <div class="" id="business_client_ctrl" :style="{overflow:'hidden',auto:sysinfo.screenHeight+'px'}">
        <table_model v-loading="isloading"
                     :currect_select="table_currect_select"
                     :element-loading-text="loading_text"
                     :search_sort_by="table_search_sort_by"
                     :all_data_count="all_data_count"
                     :table_data="table_model_data"
                     :table_field="table_model_field"
                     :info_transfer_action="info_transfer_action"
                     :new_add_info="'新增'"
                     groupdelable="true"
                     :param_apihost = "report_api+'?app='+server_app"
        >

        </table_model>

    </div>
</template>


<script>

    import page_model from '../../common/page_model.vue'
    import table_model from '../../common/table_model.vue'
    import Vue from 'vue'
    import {mapGetters, mapActions} from 'vuex'
    import axios from 'axios'
    import {axios_config} from '../../../api/axiosApi'
    import {dbResponseAnalysis2WidgetData} from '../../../api/lib/helper/dataAnalysis'
    export default {
        data(){
            return {

                adddata_page_model_data: {},
                page_model_data: {},
                table_model_data: [],
                table_model_field: {},
                all_data_count: 0,
                show_page_model_ctrl_by_table: false,
                show_adddata_page_model_ctrl: false,
                info_transfer_action: {
                },
                table_search_sort_by: {
                    page_size: 20,
                    page: 1,
                    search: {},
                    sorter: {},
                },
                table_search_sort_by_status: false,
                isloading: false,
                loading_text: '数据加载中',
                table_currect_select: '',
                email: '',
                server_app:'',

            }
        },
        watch: {
            table_search_sort_by: {
                handler: function (val, oldVal) {
                    this.getDoc(JSON.stringify(this.table_search_sort_by))

                },
                deep: true
            },
        },
        props: {},
        components: {
            page_model,
            table_model
        },
        computed: {
            ...mapGetters([
                'socket_server_store',
                'sysinfo'
            ])
        },
        created(){
            var vm = this
            this.server_app = 'jktruesign_app'
            this.report_api = this.socket_server_store.apihost + 'Doc/'
            this.report_api = 'http://localhost:8089/'
            this.$root.eventHub.$emit('init_navmenu', 'Doc')
            this.$root.eventHub.$off('currect_row_index')
            this.$root.eventHub.$on('currect_row_index', () => {
                this.show_page_model_ctrl_by_table = !this.show_page_model_ctrl_by_table
            })

            this.getDoc(JSON.stringify(this.table_search_sort_by))
            this.$root.eventHub.$off('refresh_table')
            this.$root.eventHub.$on('refresh_table', function (data) {
                console.log('on->refresh_table')
                if (data === 'resetselect') {
                    vm.reset_search_sort_by()
                }
                else {
                    vm.getDoc(data)
                }

            })
            this.$root.eventHub.$off('changeNavMenu')
            this.$root.eventHub.$on('changeNavMenu', function (data) {
                if(data  === 'ssm_Doc'){
                    vm.getDoc()
                }
            })
        },
        mounted(){
            var vm = this

//            console.log('this.table_search_sort_by',this.table_search_sort_by)
        },
        beforeDestroy(){

//            this.$root.eventHub.$off('refresh_businessinfo')
//            this.$root.eventHub.$off('currect_row_index')

        },
        methods: {

            getDoc(search_sort_by){
                var vm = this
                var search_param = {}
                if (search_sort_by) {
                    search_sort_by = JSON.parse(search_sort_by)
                    search_param.search_sort_by = JSON.stringify(search_sort_by)
                }
                search_param.rules = 1
//        search_param.token = this.socket_server_store.token
                this.$http.post(this.report_api + 'Doc/getDoc?app='+this.server_app, search_param, this.$http_config)
                //        axios.post(this.report_api + 'getDoc', search_param, axios_config)
                    .then((res) => {
                        if (res.data.code === 0) {

                            let analysis_data = dbResponseAnalysis2WidgetData(res.data.response)
                            for (let index in analysis_data.searchWidget) {
//                                      进行响应式set key
                                //1.0版本
                                if (!vm.table_search_sort_by.search.hasOwnProperty(analysis_data.searchWidget[index].search_key)) {

                                    vm.$set(vm.table_search_sort_by.search, analysis_data.searchWidget[index].search_key, analysis_data.searchWidget[index])

                                }
                            }
                            for (let index in analysis_data.sorterWidget) {
//                                      进行响应式set key
                                //1.0版本
                                if (!vm.table_search_sort_by.sorter.hasOwnProperty(analysis_data.sorterWidget[index].key)) {
                                    vm.$set(vm.table_search_sort_by.sorter, analysis_data.sorterWidget[index].key, analysis_data.sorterWidget[index].way)

                                }
                            }
                            vm.info_transfer_action = {
                                add: 'Doc/DescDoc?app='+vm.server_app,
                                get: 'Doc/GetDoc?app='+vm.server_app,
                                update: 'Doc/UpdateDoc?app='+vm.server_app,
                                groupdel: 'Doc/GroupDelDoc?app='+vm.server_app,
                            }
                            vm.table_model_field = analysis_data.rules
                            vm.table_model_data = analysis_data.data
                            vm.all_data_count = analysis_data.count

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
//                        vm.isloading = false

                    })

            },
            reset_search_sort_by(){
                this.table_search_sort_by = {
                    page_size: 20,
                    page: 1,
                    search: {},
                    sorter: {},
                }
            },
            refresh_table_data(data){
                if (this.defaultTab === '客户数据') {
                    this.getDoc(JSON.stringify(this.table_search_sort_by))
                }
                else if (this.defaultTab === '级别套餐') {
                    this.getDocLevel(JSON.stringify(this.table_search_sort_by))
                }
                else if (this.defaultTab === '支付方式') {
                    this.getPayInterface(JSON.stringify(this.table_search_sort_by))
                }
            }

        },

    }
</script>

<style lang="stylus" rel="stylesheet/stylus">
    .el-tabs__header {
        box-shadow: 0 0 15px rgba(93, 100, 124, 0.38) !important;

    }

</style>