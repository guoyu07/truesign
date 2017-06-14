<template>
    <div style="overflow: hidden">
        <transition name="el-zoom-in-top" >
            <div class="table_model" key="tabledata" style="" v-if="table_data[0]" >
                <div id="table_button_bar" style="text-align: left">
                    <el-button>操作</el-button>
                    <el-button >操作</el-button>
                    <el-button >操作</el-button>
                    <el-button >操作</el-button>
                    <el-button >操作</el-button>

                    <el-button type="primary" style="position: absolute;right: 15px"  @click="add_business_info">新增客户</el-button>

                </div>
                <el-table
                        :stripe=false
                    ref="multipleTable"
                    :data="table_data"
                    tooltip-effect="dark"
                    style="width: 100%;overflow: auto"
                    :height="screenHeight-208"
                    :default-sort = "{prop: 'date', order: 'descending'}"
                    :highlight-current-row="true"
                    @selection-change="handleSelectionChange"
                    @row-click="rowClick"
                >
                <el-table-column
                        fixed
                    type="selection"
                    width="55">
                </el-table-column>


                <el-table-column v-for="(item,index) in table_field"  v-if="index !=='tag'"  :key="item"
                         :fixed="index === 'document_id' || index === 'username'"
                        :sortable="index==='date'"
                        :prop="index"
                        :label="item.title "
                        :width="item.width"
                    >

                </el-table-column>
                <el-table-column v-for="(item,index) in table_field" v-if="index==='tag'"  :key="item"
                                 prop="tag" label="标签" width="120" fixed="right"
                                 :filters="[{ text: '家', value: '家' }, { text: '公司', value: '公司' }]"
                                 :filter-method="filterTag"
                                 filter-placement="bottom"
                >
                    <template scope="scope" >
                        <el-tag :type="scope.row.tag === '家' ? 'primary' : 'success'" close-transition>{{scope.row.tag}}</el-tag>
                    </template>
                </el-table-column>


                <el-table-column
                        fixed="right"
                        label="操作"
                        width="100">
                    <template scope="scope">
                        <el-button @click="handleViewDetailClick(scope.$index,table_data)" type="text" size="small">查看</el-button>
                        <el-button type="text" @click="handleDelRowClick(scope.$index,table_data)" size="small">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
                <div class="table_footer" style="overflow: hidden;margin-top: 2px">
                    <el-pagination style="float: right;box-shadow:  0 0 15px black;background-color:#ffffff"

                                   @size-change="handleSizeChange"
                                   @current-change="handleCurrentChange"
                                   :current-page="currentPage4"
                                   :page-sizes="[25, 35, 45, 55]"
                                   :page-size="100"
                                   layout="total, sizes, prev, pager, next, jumper"
                                   :total="table_data.length">
                    </el-pagination>
                </div>
            </div>



            <div v-else="table_data[0]" key="notabledata" style="text-align: center">
                <div class="now_data_show" style="">
                    <span style="margin-top: 50px;display: block">暂无数据</span>
                    <div class="loader" style="position:absolute;width: 200px;height: 300px;overflow: hidden;left: 50%;margin-left: -100px;top:220px;">
                        <div class="loading--1" style="display: none"></div>
                        <div class="loading-0" style="margin-left: -100px;border: none"></div>
                        <div class="loading-1" style="display: none"></div>
                        <!--<div class="loading-2">{{ conn_info }}</div>-->
                    </div>
                </div>

            </div>


        </transition>
        <transition name="fade-up">
            <page_model v-if="show_page_model_ctrl_by_table"
            :final_update_action="info_transfer_action.update"
            :final_update_btn_desc="'提交修改'"
            :page_data="detail_page_model_data"
            style="position:absolute;;width: 98%;z-index:100;text-align: center;bottom: 10px; " ></page_model>
        </transition>

    </div>
</template>

<script>
    import page_model from './page_model.vue'
    import { mapGetters,mapActions } from 'vuex'
    import axios from 'axios'
    import {axios_config} from '../../api/axiosApi'
    import {dbResponseAnalysis2WidgetData} from '../../api/lib/helper/dataAnalysis'
    export default {
        data() {
            return {
                apihost:'',
                multipleSelection: [],
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                tableData3:
                    [
                        {
                            date: '2016-05-03',
                            name: '王小虎',
                            province: '上海',
                            city: '普陀区',
                            address: '上海市普陀区金沙江路 1518 弄',
                            zip: 200333
                        }, {
                        date: '2016-05-02',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-04',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-01',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-08',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-06',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-07',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-03',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-02',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-04',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-01',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-08',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-06',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-07',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-03',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-02',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-04',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-01',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-08',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-06',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }, {
                        date: '2016-05-07',
                        name: '王小虎',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1518 弄',
                        zip: 200333
                    }
                    ],
                tableField:
                    {
                    date:{
                        title:'日期',

                    },
                    name:{
                        title:'名称',

                    },
                    province:{
                            title:'省份',
                    },
                    city:{
                        title:'城市',
                    },
                    address:{
                        title:'地址',
                    },
                    zip:{
                        title:'编码',
                    }
        },
                show_page_model_ctrl_by_table:true,
                detail_page_model_data:{},
                search_sort_by:{
                    page_size:25,
                    currect_page:1,
                },



                currentPage1: 5,
                currentPage2: 5,
                currentPage3: 5,
                currentPage4: 4

            }
        },
        props: {
            test_props:'123',
            table_design:{
                type: Object,
                default: function () {
                    return {
                        height:this.screenHeight
                    }
                },
                required: false,
            },
            table_data:{
                type: Array,
                default: function () {
                    return []
                },
                required: false,
            },
            table_field:{
                type: Object,
                default: function () {
                    return {}

                },
                required: false,
            },
            info_transfer_action:{
                type: Object,
                default: function () {
                    return {
                        get:'',
                        update:''
                    }

                },
                required: false,
            },

        },
        computed: {
            ...mapGetters([
                'wechat_marketing_store',
            ])
        },
        created(){
            var vm = this
            this.apihost = this.wechat_marketing_store.apihost
            this.$root.eventHub.$on('page_model_update_response_done',function () {
                vm.show_page_model_ctrl_by_table = false
//                vm.detail_page_model_data = {}
//                vm.add_business_info()
                vm.refresh_table_data()
            })
            this.$root.eventHub.$on('close_page_model',() => {
                this.show_page_model_ctrl_by_table = false
//                vm.detail_page_model_data = {}
            })
        },
        mounted(){
            var vm = this
            this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
                var width2height = data.split(",")
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])

            })
        },
        components: {
            page_model,

        },
        methods: {
            handleViewDetailClick(index, rows) {
                this.show_page_model_ctrl_by_table = false
                var currect_row = rows.slice(index, index+1);

                var document_id = currect_row[0].document_id
                var username = currect_row[0].username
                this.getCurrectBusinessDetail(document_id,username)
//                this.$root.eventHub.$emit('currect_row_index',row)
            },
            handleDelRowClick(index, rows) {
                var currect_row = rows.slice(index, index+1);
                var document_id = currect_row[0].document_id
                var username = currect_row[0].username
                this.updateCurrectBusinessDetail(index,username,true)
//                this.$root.eventHub.$emit('currect_row_index',row)
            },
            rowClick(row, event, column){

            },
            toggleSelection(rows) {
                if (rows) {
                    rows.forEach(row => {
                        this.$refs.multipleTable.toggleRowSelection(row);
                    });
                } else {
                    this.$refs.multipleTable.clearSelection();
                }
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            formatter(row, column) {
                return row.address;
            },
            filterTag(value, row) {
                return row.tag === value;
            },
            add_business_info(){
                var vm = this
                axios.post(this.apihost+this.info_transfer_action.add,{rules:1},axios_config)
                    .then((res) => {
                        console.log(res.data)
                        let  analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        if(analysis_data.code+'' === '0'){
                            var content = analysis_data.widgetdata[0]
                            vm.detail_page_model_data = {
                                title:'新增客户信息',
                                content:content
                            }
                            vm.show_page_model_ctrl_by_table = true



                        }

                    })
            },
            getCurrectBusinessDetail(id,username){
                var vm = this
                var search_param = ''
                var show_title = ''
                if(id){
                    search_param = {document_id:id,rules:1}
                }
                else{
                    search_param = {rules:1}
                }
                if(username){
                    show_title = username + ' 详细信息'
                }
                else{
                    show_title = '详细数据信息'
                }
                axios.post(this.apihost+this.info_transfer_action.get,search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        var content = analysis_data.widgetdata[0]
                        vm.detail_page_model_data = {
                            title:show_title,
                            content:content
                        }
                        vm.show_page_model_ctrl_by_table = true

                    })
            },
            updateCurrectBusinessDetail(index,name,del=false){
                var vm = this

                var update_params  = {}
                if(del){

                    update_params.document_id = this.table_data[index].document_id
                    update_params.if_delete = 1
                }
                else{
                    update_params = this.table_data[index]
                    update_params.rules = 1
                }
                axios.post(this.apihost+this.info_transfer_action.update,update_params,axios_config)
                    .then((res) => {
                        console.log('update_detail->',res.data)
                        if((typeof res.data === 'object' && res.data.statistic.count>=1) || res.data>=1){
                            vm.$notify.success({
                                title: '成功',
                                message: '删除成功',
                                offset: 100,
                                duration:'2000'
                            });
                        }
                        else{
                            vm.$notify.success({
                                title: '失败',
                                message: '删除失败',
                                type:'error',
                                offset: 100,
                                duration:'2000'
                            });

                        }
                        vm.$root.eventHub.$emit('refresh_businessinfo',1)
                    })
            },
            handleSizeChange(val) {
                console.log(`每页 ${val} 条`);
                this.search_sort_by.page_size = val
                this.refresh_table_data()

            },
            handleCurrentChange(val) {
                console.log(`当前页: ${val}`);
                this.search_sort_by.currect_page = val
                this.refresh_table_data()
            },
            refresh_table_data(){
                this.$root.eventHub.$emit('refresh_businessinfo',this.search_sort_by)
            }

        },
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
    #table_button_bar button{
        margin-left: 0;
    }
    th.is-leaf{
        text-align: center;
    }
    .el-table__row td{
        text-align: center;

    }
    .table_footer input {
        color black !important
    }
</style>