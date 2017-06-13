<template>
    <div class="table_model" style="overflow: auto">
        <div id="table_button_bar" style="text-align: left">
            <el-button>操作</el-button>
            <el-button >操作</el-button>
            <el-button >操作</el-button>
            <el-button >操作</el-button>
            <el-button >操作</el-button>

        </div>
        <el-table
            ref="multipleTable"
            :data="tableData3"
            border
            tooltip-effect="dark"
            style="width: 100%"
            :height="screenHeight-200"
            :default-sort = "{prop: 'date', order: 'descending'}"
            :highlight-current-row="true"
            @selection-change="handleSelectionChange"
            @row-click="rowClick"
        >
        <el-table-column
            type="selection"
            width="55">
        </el-table-column>
        <!--<el-table-column type="expand">-->
            <!--<template scope="props">-->
                <!--<el-form label-position="left" inline class="demo-table-expand">-->
                    <!--<el-form-item label="商品名称">-->
                        <!--<span>{{ props.row.name }}</span>-->
                    <!--</el-form-item>-->
                    <!--<el-form-item label="所属店铺">-->
                        <!--<span>{{ props.row.shop }}</span>-->
                    <!--</el-form-item>-->
                    <!--<el-form-item label="商品 ID">-->
                        <!--<span>{{ props.row.id }}</span>-->
                    <!--</el-form-item>-->
                    <!--<el-form-item label="店铺 ID">-->
                        <!--<span>{{ props.row.shopId }}</span>-->
                    <!--</el-form-item>-->
                    <!--<el-form-item label="商品分类">-->
                        <!--<span>{{ props.row.category }}</span>-->
                    <!--</el-form-item>-->
                    <!--<el-form-item label="店铺地址">-->
                        <!--<span>{{ props.row.address }}</span>-->
                    <!--</el-form-item>-->
                    <!--<el-form-item label="商品描述">-->
                        <!--<span>{{ props.row.desc }}</span>-->
                    <!--</el-form-item>-->
                <!--</el-form>-->
            <!--</template>-->
        <!--</el-table-column>-->

        <el-table-column v-for="(item,index) in tableField" :key="item"
                :sortable="item.key==='date'"
                :prop="item.key"
                :label="item.value"
                :width="item.width">

        </el-table-column>

        <el-table-column prop="tag" label="标签" width="100" :filters="[{ text: '家', value: '家' }, { text: '公司', value: '公司' }]" :filter-method="filterTag" filter-placement="bottom-end">
            <template scope="scope">
                <el-tag :type="scope.row.tag === '家' ? 'primary' : 'success'" close-transition>{{scope.row.tag}}</el-tag>
            </template>
        </el-table-column>
        <el-table-column
                fixed="right"
                label="操作"
                width="100">
            <template scope="scope">
                <el-button @click="handleClick" type="text" size="small">查看</el-button>
                <el-button type="text" size="small">编辑</el-button>
            </template>
        </el-table-column>
    </el-table>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                multipleSelection: [],
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                tableData3: [
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
                tableField:[
                    {
                        key:'date',
                        value:'日期',
                        width:'150'
                    },
                    {
                        key:'name',
                        value:'名称',
                        width:'150'
                    },
                    {
                        key:'province',
                        value:'省份',
                        width:'150'
                    },
                    {
                        key:'city',
                        value:'城市',
                        width:'150'
                    },
                    {
                        key:'address',
                        value:'地址',
                        width:'350'
                    },
                    {
                        key:'zip',
                        value:'编码',
                        width:'150'
                    }
                      ]
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

        },
        mounted(){
            var vm = this
            this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
                var width2height = data.split(",")
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])

            })
        },
        methods: {
            handleClick(row, event, column) {
                console.log(row);
                this.$root.eventHub.$emit('currect_row_index',row)
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
            }
        },
    }
</script>
<style>
    #table_button_bar button{
        margin-left: 0;
    }
    th.is-leaf{
        text-align: center;
    }
    .el-table__row td{
        text-align: center;

    }
</style>