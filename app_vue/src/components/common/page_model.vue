<template>
        <div  class="page_model" :style="page_design" >
                <div v-if="page_type==='list'">
                        <div class="page_title" :style="page_title_design">
                                <span>{{page_data.title}}</span>
                        </div>

                        <div class="page_content" :style="page_content_design">

                            <ol>
                                <li v-for="(item,index) in page_data.content">

                                        <label>{{page_data.content[index].label}}</label>
                                        <input v-if="page_data.content[index].type === 'btn'" style="border: none;box-shadow: 0 0 3px #9c9c9c;border-radius: 5px" type="button" v-model="page_data.content[index].value" :readonly="!page_data.content[index].access" />

                                        <input v-if="page_data.content[index].type === 'upfile'" @click="upfile_click" :data-index="index" style="border: none;box-shadow: 0 0 3px #9c9c9c;border-radius: 5px" type="button" v-model="page_data.content[index].value" :readonly="!page_data.content[index].access" />
                                        <input v-if="page_data.content[index].type === 'upfile'" :data-fileindex="index" v-on:change="upfile_change"  style="border: none;box-shadow: 0 0 3px #9c9c9c;border-radius: 5px;display: none" type="file"  />
                                        <div v-if="page_data.content[index].type === 'time'" class="timepicker">
                                            <el-date-picker

                                                             v-model="page_data.content[index].value"
                                                             type="datetime"
                                                             placeholder="选择日期时间"
                                                             align="left"
                                                             :picker-options="pickerOptions">
                                            </el-date-picker>
                                        </div>
                                        <input v-if="page_data.content[index].type !== 'btn' && page_data.content[index].type !== 'upfile' && page_data.content[index].type !== 'time'" :style=" {borderBottom:page_data.content[index].access?'1px solid #f0f0f0':'none',backgroundColor:page_data.content[index].access?'#767676':''}"   v-model="page_data.content[index].value" :readonly="!page_data.content[index].access" />
                                        <label ><input   @click='change_access($event)' :data-index="index" type="button" v-model="page_data.content[index].access"></label>

                                </li>


                            </ol>


                        </div>
                </div>


        </div>

</template>


<script>


    export default {
        data(){
            return {
                pickerOptions: {
                    shortcuts: [{
                        text: '今天',
                        onClick(picker) {
                            picker.$emit('pick', new Date());
                        }
                    }, {
                        text: '昨天',
                        onClick(picker) {
                            const date = new Date();
                            date.setTime(date.getTime() - 3600 * 1000 * 24);
                            picker.$emit('pick', date);
                        }
                    }, {
                        text: '一周前',
                        onClick(picker) {
                            const date = new Date();
                            date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit('pick', date);
                        }
                    }]
                },
            }
        },
        props: {
            page_type:{
                type: String,
                default: 'list',
                required: false,
            },
            page_design:{
                type: Object,
                default: function () {
                    return {
                        width:'100%',
                        height:'100%',
                        backgroundColor:'#dcdcdc',
                        color:'#000000',
                        overflow:'hidden',
                        paddingBottom:'20px',
                        boxShadow:'0 0 15px gray',

                    }
                },
                required: false,
            },
            page_title_design:{
                type: Object,
                default: function () {
                    return {
                        width:'20%',
                        minWidth:'120px',
                        height:'30px',
                        lineHeight:'30px',
                        backgroundColor:'#b6b6b6',
                        color:'#000000',
                        textAlign:'right',
                        paddingRight:'10px',
                        boxShadow:'0 0 10px #000000'

                    }
                },
                required: false,
            },
            page_content_design:{
                type: Object,
                default: function () {
                    return {
                        width:'100%',
                        height:'100%',
                        backgroundColor:'transparent',
                        color:'#000000',
                        textAlign:'left',
                        marginTop:'20px',



                    }
                },
                required: false,
            },
            page_data:{
                type: Object,
                default: function () {
                    return {
                        title:'列表页标题栏',
                        content:[
                            {
                                type:'num',
                                key:'id',
                                label:'数字',
                                value:1,
                                access:false
                            },
                            {
                                type:'str',
                                key:'id',
                                label:'字符串',
                                value:'2',
                                access:false
                            },
                            {
                                type:'boolen',
                                key:'id',
                                label:'布尔',
                                value:true,
                                access:false
                            },
                            {
                                type:'object',
                                key:'id',
                                label:'对象',
                                value:{
                                    a:'a',
                                    b:'b'
                                },
                                access:false
                            },
                            {
                                type:'array',
                                key:'id',
                                label:'数组',
                                value:[
                                    'a','b'
                                ],
                                access:false
                            },
                            {
                                type:'upfile',
                                key:'id',
                                label:'文件',
                                value:"http://upyun.com/123.zip",
                                access:false
                            },
                            {
                                type:'time',
                                key:'id',
                                label:'时间',
                                value:"",
                                access:false
                            },
                            {
                                type:'btn',
                                key:'id',
                                label:'按钮',
                                value:'提交',
                                action_uri:'http://www.baidu.com'
                            }
                        ]
                    }
                },
                required: false,
            },

            page_title_design_detail:{
                type: Object,
                default: function () {
                    return {
                        width:'20%',
                        minWidth:'120px',
                        height:'30px',
                        lineHeight:'30px',
                        backgroundColor:'#b6b6b6',
                        color:'#000000',
                        textAlign:'right',
                        paddingRight:'10px',
                        boxShadow:'0 0 10px #000000'

                    }
                },
                required: false,
            },
            page_content_design_detail:{
                type: Object,
                default: function () {
                    return {
                        width:'100%',
                        height:'100%',
                        backgroundColor:'transparent',
                        color:'#000000',
                        textAlign:'left',
                        marginTop:'20px',



                    }
                },
                required: false,
            },
            page_data_detail:{
                type: Object,
                default: function () {
                    return {
                        title:'列表页标题栏',
                        content:[

                        ]
                    }
                },
                required: false,
            },





        },
        components: {},
        computed: {
            compute_page_design_style(){
                return {

                }
            }
        },
        created(){

        },
        mounted(){


        },
        beforeDestroy(){

        },
        methods: {
            change_access(e){
                var target_index = e.currentTarget.dataset.index
                this.page_data.content[target_index].access = !this.page_data.content[target_index].access
            },
            handleRemove(file, fileList) {
                console.log(file, fileList);
            },
            handlePreview(file) {
                console.log(file);
            },
            upfile_click(e){
                var target_index = e.currentTarget.dataset.index
                var $target = $(e.currentTarget)
                $target.next().click()
                console.log($target.next())
            },
            upfile_change(e){
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                var filename = files[0].name
                var target_index = e.currentTarget.dataset.fileindex
                console.log(filename,target_index)
                this.page_data.content[target_index].value = filename
            }
        },

    }
</script>
<style>
.page_model .page_content ol{

        width: 100%;
        min-width: 600px;
}
.page_model .page_content li
{

        height: 35px;
        line-height: 35px;
        width: 100%;
}
.page_model .page_content li .timepicker {
    display: inline-block;
    margin-top:5px;
    max-width: 75%;
    width: 70%;
    min-width: 300px;
}
.page_model .page_content li .timepicker div{
    width: 100%;
}
.page_model .page_content li  label{
        background-color: transparent;
        height: 35px;
        line-height: 35px;
        color: black;
        width: 10%;
        min-width: 80px;
        display: inline-block;
        text-align: right;
        padding-left: 10px;
}
.page_model .page_content li label input{
        width: 100%;
        min-width: 60px;
}
.page_model .page_content li input{
        background-color: transparent;
        height: 35px;
        line-height: 35px;
        max-width: 75%;
        width: 70%;
        min-width: 300px;
        text-align: center;
        background: 0 0;
        font-family: 'Graphik Web',sans-serif;
        font-style: normal;
        font-stretch: normal;
        font-size: 1em;
        font-weight:800;
        letter-spacing: 2.9px;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        outline: 0;

}
.page_model .page_content li input:hover{
        transition: all 1.5s;
        background-color: rgba(0, 0, 0, 0.43);


}

.el-row {
        margin-bottom: 20px;
&:last-child {
         margin-bottom: 0;
 }
}
.el-col {
        border-radius: 4px;
}
.bg-purple-dark {
        background: #99a9bf;
}
.bg-purple {
        background: #d3dce6;
}
.bg-purple-light {
        background: #e5e9f2;
}
.grid-content {
        border-radius: 4px;
        min-height: 36px;
}
.row-bg {
        padding: 10px 0;
        background-color: #f9fafc;
}
.el-upload__input{
        display: none !important;
}
.el-input__inner{
    color: black !important;
}
</style>
