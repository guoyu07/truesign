<template>
    <div style="overflow: auto">
        <!--<pre>-->
            <!--{{table_field}}-->
        <!--</pre>-->
        <div id="table_button_bar" style="text-align: left">
            <transition name="fade-left" >
            <el-button type="danger" v-if="all_data_count && multipleSelection.length>0"   @click="delSelectOption">删除选择项</el-button>
            </transition>
            <div style="display: inline-block">
                <el-input   v-focus:currect_select="currect_select" :name="item.search_title" :data-key="item.search_key" style="display: inline-block;width: 180px;" v-for="(item,index) in search_sort_by.search" :key="item"

                           :placeholder="'请输入'+item.search_title"
                           icon="search"
                           v-model="item.search_value"
                >
                </el-input>
                <el-button @click="resetSelect" v-if="Object.keys(search_sort_by.search).length" size="" type="success">重置检索</el-button>
            </div>

            <el-button v-if="new_add_info" type="primary" style="position: absolute;right: 15px"  @click="add_business_info" >{{ new_add_info }}</el-button>

        </div>
        <transition name="el-zoom-in-top" >
            <div class="table_model" key="tabledata" style="" v-if="all_data_count" >

                <el-table
                    v-loading="isloading"
                    element-loading-text="数据加载中"
                        :stripe=false
                    ref="multipleTable"
                    :data="table_data"
                    tooltip-effect="dark"
                    style="width: 100%;overflow: auto"
                    :header-align="'center'"
                    :height="screenHeight-208"

                    :show-summary="true"
                    :sum-text="'汇总'"
                    :border="true"
                    :resizable="true"
                    @summary-method="summaryfunction"
                    :highlight-current-row="true"
                    @selection-change="handleSelectionChange"
                    @row-click="rowClick"
                    @row-dblclick="rowDblClick"
                    @sort-change="sortChange"
                >
                <el-table-column v-if="groupdelable"

                        fixed
                    type="selection"
                    width="55">
                </el-table-column>


                <el-table-column v-for="(item,index) in table_field"
                                 v-if=""  :key="item"
                         :show-overflow-tooltip="true"
                         :fixed="index === 'document_id' || index === 'username'"
                        :sortable="is_sortable(index)"
                        :prop="index"
                        :label="item.title"
                        :width="item.width" >

                    <template scope="scope">
                        <div v-if="!item.widgetType">{{scope.row[item.name]}}</div>
                        <div v-else-if="item.widgetType[0]==='headpic'">
                            <img :src="scope.row[item.name]" style="width: auto;height: 30px">
                        </div>
                        <div v-else-if="item.widgetType[0]==='password'">{{scope.row[item.name]}}</div>
                        <div v-else-if="item.widgetType[0]==='text'">文本</div>
                        <div v-else-if="item.widgetType[0]==='color'" >
                            <i style="display: block;width: 30px;height: 30px;margin: 0 auto;border-radius: 15px" :style="{backgroundColor:scope.row[item.name]}"></i>
                        </div>
                        <div v-else-if="item.widgetType[0]==='time'">{{ get_timestamp2datetime(scope.row[item.name]) }}</div>
                        <div v-else-if="item.widgetType[0]==='radio'">{{scope.row[item.name]}}</div>
                        <div v-else-if="item.widgetType[0]==='checkbox'">多选</div>
                    </template>


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
                                   :current-page="parseInt(search_sort_by.page)"
                                   :page-size="parseInt(search_sort_by.page_size)"
                                   @size-change="handleSizeChange"
                                   @current-change="handleCurrentChange"
                                   :page-sizes="[20, 35, 55, 70]"
                                   layout="total, sizes, prev, pager, next, jumper"
                                   :total="all_data_count">
                    </el-pagination>
                </div>
            </div>
            <div v-else="all_data_count" key="notabledata" style="text-align: center">
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
        <transition name="fade-up" style="">
            <page_model  :element-loading-text="loading_text"  v-if="show_page_model_ctrl_by_table"
            :final_update_action="info_transfer_action.update"
            :final_update_btn_desc="'提交修改'"
            :page_data="detail_page_model_data"
             source_way="table"
            style="position:absolute;z-index:100;
            width:98.5%;text-align: center;bottom: 10px;height:auto;max-height: 600px;overflow-y: auto;overflow-x: hidden" >

            </page_model>

        </transition>
        <phone_model  v-if="show_phone_model"
                      style="float: right ;margin-bottom: 100px;position: fixed;top:100px;z-index:2000"
                      :mobile_show_uri="show_phone_model_uri"
        ></phone_model>
    </div>
</template>

<script>
    import page_model from './page_model.vue'
    import phone_model from './phone_model.vue'
    import { mapGetters,mapActions } from 'vuex'
    import axios from 'axios'
    import {axios_config} from '../../api/axiosApi'
    import {dbResponseAnalysis2WidgetData,deleteEmptyObj,deleteEmptyString,timestamp2datetime} from '../../api/lib/helper/dataAnalysis'
    import Vue from 'vue'
    import { focus } from 'vue-focus';

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
                show_page_model_ctrl_by_table:false,
                detail_page_model_data:{},
                isloading:false,
                loading_text:'数据加载中',
                currect_select:'',

                options4: [],
                value9: [],
                list: [],
                loading: false,
                states: ["Alabama", "Alaska", "Arizona",
                    "Arkansas", "California", "Colorado",
                    "Connecticut", "Delaware", "Florida",
                    "Georgia", "Hawaii", "Idaho", "Illinois",
                    "Indiana", "Iowa", "Kansas", "Kentucky",
                    "Louisiana", "Maine", "Maryland",
                    "Massachusetts", "Michigan", "Minnesota",
                    "Mississippi", "Missouri", "Montana",
                    "Nebraska", "Nevada", "New Hampshire",
                    "New Jersey", "New Mexico", "New York",
                    "North Carolina", "North Dakota", "Ohio",
                    "Oklahoma", "Oregon", "Pennsylvania",
                    "Rhode Island", "South Carolina",
                    "South Dakota", "Tennessee", "Texas",
                    "Utah", "Vermont", "Virginia",
                    "Washington", "West Virginia", "Wisconsin",
                    "Wyoming"],
                focused: false,
                ready_refresh:0,
                show_phone_model_uri:'http://wap.baidu.com'
            }


        },
        props: {
            show_phone_model_key:{
                defult:'uri'
            },
            groupdelable:{
                default:true
            },
            show_phone_model:{
                default:false
            },
            test_props:'123',
            new_add_info:{
                type:String,
                default:''
            },
            search_sort_by:{
                type:Object,
                default:function() {
                    return {
                        vue_search_way:'self',
                        page:1,
                        page_size:20,
                    }
                }
            },
            table_design:{
                type: Object,
                default: function () {
                    return {
                        height:this.screenHeight
                    }
                },
                required: false,
            },
            all_data_count:{
                default: 0

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
        watch:{
            search_sort_by: {
                handler: function (val, oldVal) {
                    this.isloading = true

                },
                deep: true
            },
            table_data: {
                handler: function (val, oldVal) {
                    this.isloading = false

                },
                deep: true
            },
            multipleSelection(val,oldVal){
                console.log('multipleSelection change')
                console.log(val.length,oldVal.length)


            }
        },
        computed: {
            ...mapGetters([
                'wechat_marketing_store',
            ]),
            get_sorter_arr(){
                var sorter_arr =  []
                for (var index in this.search_sort_by.sorter){
                    sorter_arr.push(index)
                }
                return sorter_arr
            },


        },
        created(){
            var vm = this
            this.apihost = this.wechat_marketing_store.apihost
            this.$root.eventHub.$on('close_page_model',(data) => {
                console.log('on->close_page_model',data)
                this.show_page_model_ctrl_by_table = false
                if(data){
                    this.refresh_table_data()
                }
            })
        },
        updated(){

//            console.log('updated->props->search_sort_by->',this.search_sort_by)

            Vue.nextTick( () => {
//                console.log('this.table_data',this.table_data)
            })
            $(".table_select_bar>div.el-input>input").focus((e) => {

                this.currect_select = e.currentTarget.parentNode.parentNode.dataset.key

            })
            $("#table_button_bar .el-input>input").focus((e) => {

                var $div_node = e.currentTarget.parentNode
                this.currect_select = $div_node.dataset.key
            })

        },
        mounted(){

//            console.log('mounted->props->search_sort_by->',this.search_sort_by)
//            this.watchSearchSortBy()


            this.list = this.states.map(item => {
                return { value: item, label: item };
            });
            var vm = this
            this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
                var width2height = data.split(",")
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])
            })

        },
        components: {
            page_model,
            phone_model,
        },
        methods: {
            handleViewDetailClick(index, rows) {
                this.show_page_model_ctrl_by_table = false
                var currect_row = rows[index]
                var document_id = currect_row.document_id
                var username = ''
                if(currect_row.hasOwnProperty('username')){
                    username = currect_row.username
                }
                console.log(rows)
                console.log(index)
                console.log(currect_row)
                this.getCurrectBusinessDetail(document_id,username)
//                this.$root.eventHub.$emit('currect_row_index',row)
            },
            handleDelRowClick(index, rows) {
                var currect_row = rows.slice(index, index+1);
                var document_id = currect_row.document_id
                var username = currect_row.username
                this.updateCurrectBusinessDetail(index,username,true)
//                this.$root.eventHub.$emit('currect_row_index',row)
            },
            rowClick(row, event, column){
                if(this.show_phone_model){
                    this.show_phone_model_uri = 'http://'+row[this.show_phone_model_key]
                }
            },
            rowDblClick(row, event, column){
                this.show_page_model_ctrl_by_table=false
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
//                        console.log(res.data)
                        let  analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        console.log('analysis_data',analysis_data)
                        if(analysis_data.code+'' === '0'){
                            var content = analysis_data.widgetdata[0]
                            vm.detail_page_model_data = {
                                title:vm.new_add_info+'信息',
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
                vm.isloading = true
                axios.post(this.apihost+this.info_transfer_action.get,search_param,axios_config)
                    .then((res) => {
                        let analysis_data = dbResponseAnalysis2WidgetData(res.data)
                        var content = analysis_data.widgetdata[0]
                        vm.detail_page_model_data = {
                            title:show_title,
                            content:content
                        }
                        vm.show_page_model_ctrl_by_table = true
                        vm.isloading = false
                    })
                    .catch((error) => {
                        vm.isloading = error
                    });
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
                vm.isloading = true
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
                        vm.isloading = false

                        vm.refresh_table_data()
                    })
                    .catch((error) => {
                        vm.isloading = error
                    });
            },
            handleSizeChange(val) {
                this.isloading = true
                console.log(`每页 ${val} 条`);
                this.search_sort_by.vue_search_way = 'self'
                this.search_sort_by.page_size = val
                this.handleCurrentChange(1)



            },
            handleCurrentChange(val) {
                this.isloading = true
                this.search_sort_by.vue_search_way = 'self'
                this.search_sort_by.page = val


            },
            refresh_table_data(add_note){
                var vm = this
                if(add_note === 'resetselect'){
                    this.$root.eventHub.$emit('refresh_table','resetselect')
                }
                else{

                    console.log('emit->refresh_table')
                    this.$root.eventHub.$emit('refresh_table',JSON.stringify(vm.search_sort_by))
                }

            },
            clickCurrectSelect(e){
//                alert(1)
//                console.log(e.currentTarget.dataset.key)
            },
            remoteSearchMethod(query) {
                this.search_sort_by[this.currect_select] = query
//                console.log(this.search_sort_by)
            },
            focusCurrectSelect(e){
//                console.log(e)
            },
            delSelectOption(){
                var vm = this
                var del_ids = []
                for (var item of this.multipleSelection){
                    del_ids.push(item.document_id)
                }
                console.log(del_ids)
                axios.post(this.apihost+this.info_transfer_action.groupdel,{ids:del_ids},axios_config)
                    .then((res) => {
//                        console.log('groupdel->',res.data)
                            vm.$notify.success({
                                title: '成功',
                                message: '删除成功',
                                offset: 100,
                                duration:'2000'
                            });
                            vm.refresh_table_data()
//                        if((typeof res.data === 'object' && res.data.statistic.count>=1) || res.data>=1){
//                            vm.$notify.success({
//                                title: '成功',
//                                message: '删除成功',
//                                offset: 100,
//                                duration:'2000'
//                            });
//                        }
//                        else{
//                            vm.$notify.success({
//                                title: '失败',
//                                message: '删除失败',
//                                type:'error',
//                                offset: 100,
//                                duration:'2000'
//                            });
//
//                        }
//                        vm.$root.eventHub.$emit('refresh_businessinfo',JSON.stringify(vm.search_sort_by))
//                        vm.isloading = false
                    })
                    .catch((error) => {
                        vm.isloading = error
                    });

            },
            resetSelect(){
                this.handleCurrentChange(1)
//                $('li.number').eq(0).click()
//                this.$root.eventHub.$emit('refresh_table','resetselect')
                this.refresh_table_data('resetselect')

            },
            sortChange(column){
                var default_sorter = this.search_sort_by.sorter
                for (var index in default_sorter){
                    default_sorter[index] = ''
                }
                default_sorter[column.prop] = column.order
                console.log(default_sorter)

            },
            is_sortable(key,e){

                if(this.search_sort_by.sorter.hasOwnProperty(key)){
                    return 'custom'
                }

            },
            summaryfunction(columns,data){
                console.log(columns,data)
            },
            get_timestamp2datetime(timestamp){
                return timestamp2datetime(timestamp)
            }

        },
        beforeDestroy(){
//            console.log('table_model->beforedestroy')
//            this.$root.eventHub.$off('refresh_table')
//            this.$root.eventHub.$off('page_model_update_response_done')

        },
        destroyed(){
//            console.log('table_model->destroyed')

        }
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
    .el-loading-mask{
        background-color rgba(247, 250, 255, 0.79)
    }
    #table_button_bar input{
        color black !important
    }
    .el-pagination__jump input.el-pagination__editor{
        width 80px !important
    }
    .el-table__footer tbody tr td div{
        text-align center
    }
</style>