<template>
    <transition name="el-zoom-in-top">
        <div v-if="page_data.title" key="haspagedata" class="page_model" :style="page_design" >
                <div v-if="page_type==='list'">

                        <div class="page_title" :style="page_title_design">
                            <span>{{page_data.title}}</span>
                        </div>

                        <div class="page_content" :style="page_content_design">

                            <ol>
                                <li v-for="(item,index) in page_data.content">

                                    <label :style=" {borderBottom:page_data.content[index].access?'1px solid #f0f0f0':'none',backgroundColor:page_data.content[index].access?'rgba(150, 150, 150, 0.49)':''}">{{page_data.content[index].label}}</label>
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
                                    <input v-if="page_data.content[index].type !== 'btn' && page_data.content[index].type !== 'upfile' && page_data.content[index].type !== 'time'" :style=" {borderBottom:page_data.content[index].access?'1px solid #f0f0f0':'none',backgroundColor:page_data.content[index].access?'rgba(150, 150, 150, 0.49)':''}"   v-model="page_data.content[index].value" :readonly="!page_data.content[index].access" />
                                    <label style="width: 10%;min-width: 80px;"><input   @click='change_access($event)' :data-index="index" type="button" v-model="page_data.content[index].access"></label>

                                </li>


                            </ol>


                        </div>
                </div>

        </div>
        <div v-else="page_data.title" key="nopagedata" style="text-align: center">
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
            page_data_demo:{
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
            page_data:{
                type: Object,
                default: function () {
                    return ''
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
<style lang="stylus" rel="stylesheet/stylus">
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
        min-width: 280px;
        display: inline-block;
        text-align: center;
        padding-left: 10px;
        font-size: 13px;
        letter-spacing: inherit;
        color: #727272;
        margin: 0;
}
.page_model .page_content li label input{
        width: 100%;
        min-width: 60px;
}
.page_model .page_content li input{
        color:#727272 !important;
        background-color: transparent;
        height: 35px;
        line-height: 35px;
        max-width: 70%;
        width: 60%;
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
.now_data_show{
    position: absolute;
    left: 50%;
    margin-left: -300px;
    top:100px;
    padding: 30px;
    border-radius: 15px;
    letter-spacing: 5px;font-size: 20px;color: #5e667e;width: 600px;height: 300px;text-align: center;box-shadow: 0 0 5px #cbc8cb
}
.page_model .page_content li input:hover{
        transition: all 1s;
        background-color: rgba(150, 150, 150, 0.49);


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











.loader {
    width: loader_width
    height 50px
    margin: 0 auto;
    position: absolute;
    left 300px
    margin-top -45px
    margin-left 20px


}
.loader .loading-0 {
    display inline-block
    top 20px
    position: absolute;
    width: 100%;
    height: 10px;
    border: 1px solid #b6b6b6;
    border-radius: 10px;
    overflow hidden
}
.loader .loading-0:before {
    content: "";
    border-radius: 10px;
    display: inline-block;
    position: absolute;
    width: 100%;
    height: 100%;
    background: #b6b6b6;
    box-shadow: 10px 0px 15px 0px #b6b6b6;
    animation: load_def 3s linear infinite;
}

.loader .loading--1 {
    display inline-block
    top 35px
    position: absolute;
    width: 100%;
    height: 10px;
    border: 1px solid #b6b6b6;
    background #b6b6b6
    border-radius: 10px;
    overflow hidden
}
.loader .loading--1:before {
    content: "";
    border-radius: -170px;
    display: inline-block;
    position: absolute;
    width: 60%;
    height: 100%;
    background: #525B64;
    box-shadow: 10px 0px 15px 0px #b6b6b6;
    animation: load_def 2s linear infinite;
}
.loader .loading-1 {
    display inline-block

    position: relative;
    width: 100%;
    height: 10px;
    border: 1px solid #b6b6b6;
    border-radius: 10px;
    animation: turn 4s linear 3.75s infinite;
}

.loader .loading-1:before {
    content: "";
    border-radius: 10px;
    display: inline-block;
    position: absolute;
    width: 0%;
    height: 100%;
    background: #b6b6b6;
    box-shadow: 10px 0px 15px 0px #b6b6b6;
    animation: load 2s linear infinite;
}
.loader .loading-2 {
    display inline-block

    width: 100%;
    position: absolute;
    color: #b6b6b6;
    left 100%
    font-size: 20px;
    text-align: center;
    animation: bounce 2s  linear infinite;
}
.loader .loading-3 {
    display inline-block

    position: relative;
    width: 100%;
    height: 10px;
    border: 1px solid #e79977;
    border-radius: 10px;
    animation: turn 4s linear 3.75s infinite;
}
.loader .loading-3:before {
    content: "";
    display: inline-block;
    position: absolute;
    width: 0%;
    height: 100%;
    background: #e79977;
    box-shadow: 10px 0px 15px 0px #e79977;
    animation: load 2s linear infinite;
}
.loader .loading-4 {
    display inline-block

    width: 100%;
    position: absolute;
    color: #e79977;
    left 100%
    font-size: 20px;
    text-align: center;
    animation: bounce 2s  linear infinite;
}
@keyframes load {
    0% {
        transform :translateX(-100%)
        width: 0%;
    }
    87.5%, 100% {
        width: 100%;
    }
}
@keyframes load_def {
    0% {
        left: -300px;

    }
    87.5%, 100% {
        left: 300px;
    }
}

@keyframes turn {
    0% {
        transform: rotateY(0deg);
    }
    6.25%, 50% {
        transform: rotateY(180deg);
    }
    56.25%, 100% {
        transform: rotateY(360deg);
    }
}
@keyframes bounce {
    0%,100% {
        top: -5px;
    }
    12.5% {
        top: 5px;
    }
}
</style>
