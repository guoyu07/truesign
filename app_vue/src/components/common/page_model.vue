<template>
    <transition name="el-zoom-in-top" >
        <div v-if="page_data.title" key="haspagedata" class="page_model" :style="page_design" v-loading="authing"
             :element-loading-text="laoding_text">
            <div v-if="page_type==='list'" style="padding-left: 13%">

                <div class="page_title" :style="page_title_design" >
                    <span>{{page_data.title}}</span>
                </div>
                <div class="fold_bar" @click="fold_content_footer" :style="{left:10+'px'}">

                </div>
                <div class="close_bar" @click="close_page_model" >

                </div>
                <transition name="fade-right">
                    <div class="page_content" v-if="show_content_footer" :style="page_content_design">
                        <ol>
                            <li v-for="(item,index) in page_data.content">
                                <label :style=" {borderBottom:page_data.content[index].access?'1px solid #f0f0f0':'none',backgroundColor:page_data.content[index].access?'rgba(150, 150, 150, 0.49)':'',verticalAlign: 'top'}">{{page_data.content[index].label}}</label>
                                <div v-if="page_data.content[index].type ==='upfile'" class="upfile">
                                    <input v-if="page_data.content[index].type === 'upfile'" @click="upfile_click" :data-index="index" style="border: none;box-shadow: 0 0 3px #9c9c9c;border-radius: 5px" type="button" v-model="page_data.content[index].value" :readonly="!page_data.content[index].access" />
                                    <input v-if="page_data.content[index].type === 'upfile'" :data-fileindex="index" v-on:change="upfile_change"  style="border: none;box-shadow: 0 0 3px #9c9c9c;border-radius: 5px;display: none" type="file"  />
                                    <input
                                            style="display: none"
                                            v-model="page_data.content[index].value"
                                            :name="page_data.content[index].label"
                                            type="text"
                                            v-validate.initial="{ rules: { required:(page_data.content[index].regex && page_data.content[index].modifiable )?true:false,regex:page_data.content[index].modifiable?page_data.content[index].regex:false } }" :class="{'input': true, 'is-danger': errors.has(page_data.content[index].label) }"


                                    />
                                </div>
                                <div v-else-if="page_data.content[index].type ==='upimg'" class="upimg">
                                    <el-upload

                                            class="avatar-uploader"
                                            :data="upload_params.up_param"
                                            :action="upload_params.up_action"

                                            :show-file-list="false"
                                            :drag=false
                                            :multiple=false
                                            :on-success="handleAvatarSuccess"
                                            :on-progress="handleAvatarProgress"
                                            :before-upload="beforeAvatarUpload"

                                    >
                                        <input
                                                style="display: none"
                                                v-model="page_data.content[index].value"
                                                :name="page_data.content[index].label"
                                                type="text"
                                                v-validate.initial="{ rules: { required:(page_data.content[index].regex && page_data.content[index].modifiable )?true:false,regex:page_data.content[index].modifiable?page_data.content[index].regex:false } }" :class="{'input': true, 'is-danger': errors.has(page_data.content[index].label) }"


                                        />
                                        <img  @click="upload_this_index($event,index)" v-if="page_data.content[index].value" :src="page_data.content[index].value" class="avatar">
                                        <i  @click="upload_this_index($event,index)" v-else class="el-icon-plus avatar-uploader-icon"></i>
                                    </el-upload>
                                </div>
                                <div v-else-if="page_data.content[index].type ==='arr' || page_data.content[index].type ==='obj'" class="model_obj">
                                    <ol>
                                        <li v-for="(iitem,iindex) in JSON.parse(page_data.content[index].value)">
                                            <span>{{iindex}} : {{ iitem}}</span>
                                        </li>
                                    </ol>
                                </div>
                                <div v-else-if="page_data.content[index].type === 'time'" class="timepicker" >
                                    <el-date-picker style="display: inline-block;width: 300px"
                                            :readonly="!page_data.content[index].modifiable"
                                            v-model="page_data.content[index].value"
                                            type="datetime"
                                            placeholder="选择日期时间"
                                            align="left"
                                            @change="changetime"
                                            :picker-options="pickerOptions"

                                    >
                                    </el-date-picker>
                                    <input
                                            style="display: none"
                                            v-model="page_data.content[index].value"
                                            :name="page_data.content[index].label"
                                            type="text"
                                            v-validate.initial="{ rules: { required:(page_data.content[index].regex && page_data.content[index].modifiable )?true:false,regex:page_data.content[index].modifiable?page_data.content[index].regex:false } }" :class="{'input': true, 'is-danger': errors.has(page_data.content[index].label) }"


                                    />


                                </div>
                                <input v-if="
                                page_data.content[index].type !== 'btn' &&
                                page_data.content[index].type !== 'upfile' &&
                                page_data.content[index].type !== 'upimg' &&
                                page_data.content[index].type !== 'time' &&
                                page_data.content[index].type !== 'obj' "

                                       :disabled="!page_data.content[index].modifiable"
                                       v-model="page_data.content[index].value"
                                       :readonly="!page_data.content[index].access  || page_data.content[index].key === 'document_id' || !page_data.content[index].modifiable"
                                       :style=" {
                                    borderBottom:page_data.content[index].access?'1px solid #f0f0f0':'none',
                                    backgroundColor:(page_data.content[index].access && page_data.content[index].modifiable)?'rgba(150, 150, 150, 0.49)':''
                                    }"
                                       :name="page_data.content[index].label"
                                       type="text"
                                       v-validate.initial="{ rules: { required:(page_data.content[index].regex && page_data.content[index].modifiable )?true:false,regex:page_data.content[index].modifiable?page_data.content[index].regex:false } }" :class="{'input': true, 'is-danger': errors.has(page_data.content[index].label) }"


                                />
                                <!--<input name="email" v-model="page_data.content[index].value" v-validate.initial="'required|email'" :class="{'input': true, 'is-danger': errors.has('email') }" type="text" placeholder="Email">-->


                                <div style="width: auto;min-width: 180px;vertical-align: top;display: inline-block;padding-left: 10px;height: 32px;line-height: 32px" >
                                    <!--<input   @click='change_access($event)' :data-index="index" type="button" v-model="page_data.content[index].access">-->

                                    <span style="display: inline-block;height: 32px" v-show="errors.has(page_data.content[index].label)" class="help is-danger">
                                    <i style=" color:rgba(205,52,25,0.91);margin-right: 10px" v-show="errors.has(page_data.content[index].label)" class="fa  fa-refresh rotate_aw"></i>{{ errors.first(page_data.content[index].label) }}</span>
                                </div>

                            </li>


                        </ol>


                    </div>
                </transition>

                <div class="page_footer" v-if="show_content_footer">
                    <transition name="shifting-half-fade">
                        <div v-if="update_response !== '-1'  && update_response !== '0' " class="show_db_reponse"  key="update_ok"  style="width: 100px;height: 35px;border-radius: 5px;background-color: rgba(50,65,87,0.53);position: absolute;right: 100px;bottom: 3px;line-height: 35px;text-align: center;color: white;font-size: 18px">更新成功</div>
                        <div v-else-if="update_response === '-1'" class="show_db_reponse"  key="update_err" style="width: 100px;height: 35px;border-radius: 5px;background-color: rgba(182,43,21,0.53);position: absolute;right: 100px;bottom: 3px;line-height: 35px;text-align: center;color: white;font-size: 18px">更新失败</div>
                        <div v-else-if="update_response === '0'" class="show_db_reponse"  key="update_err" style="width: 100px;height: 35px;border-radius: 5px;background-color: rgba(182,82,64,0);position: absolute;right: 100px;bottom: 22px;"></div>
                    </transition>
                    <button @click="final_update_data"  :class="{ui:ClassisActive, inverted:ClassisActive, teal:ClassisActive, basic:ClassisActive,
                   button:ClassisActive,loading:authing,    forminput:ClassisActive}" :disabled="authing"  style="background-color:rgba(132,132,132,0.54) !important;border-radius:3px;margin: 0px;font-size: 20px;padding: 10px;">
                        {{final_update_btn_desc}}</button>
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

    const Waves  = require('node-waves');
    import Vue from 'vue'
    import {resolveWidgetData2FormData}   from '../../api/lib/helper/dataAnalysis'
    import axios from 'axios'
    import {axios_config} from '../../api/axiosApi'
    import { mapGetters,mapActions } from 'vuex'
    import { Message } from 'element-ui';
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
                imageUrl:'',
                ClassisActive: true,
                authing:false,
                laoding_text:'请求处理中',
                update_response:'0',
                report_api:'',
                upload_params:{
                    up_param:{},
                    up_action:'http://truesign-app.oss-cn-beijing.aliyuncs.com/',
                    up_filename:'',
                    tmp_upload_index:''
                },
                show_content_footer:true,
                is_key:'server_help'
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
                        height:'auto',
                        backgroundColor:'#dcdcdc',
                        color:'#000000',
                        overflow:'hidden',

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
                        minWidth:'80px',
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
                        minWidth:'80px',
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
            final_update_action:{
                type: String,
                default: 'SiteInfo/updateSiteBaseConfig',
                required: false,
            },
            final_update_btn_desc:{
                type:String,
                default:'提交数据'
            }
        },
        components: {},
        computed: {
            ...mapGetters([
                'wechat_marketing_store',
            ]),


        },
        created(){
            this.report_api = this.wechat_marketing_store.apihost
        },
        mounted(){





        },
        updated(){

            let config = {
                // How long Waves effect duration
                // when it's clicked (in milliseconds)
                duration: 3000,
                // Delay showing Waves effect on touch
                // and hide the effect if user scrolls
                // (0 to disable delay) (in milliseconds)
                delay: 500
            };
            Waves.init(config)
            Waves.attach('#update_btn', ['waves-button']);
            Waves.attach('.page_model', ['waves-block']);

        },

        methods: {
            changetime(data){
                console.log('time',data,Date.parse(data)/1000)

            },
            upload_this_index(e,data){
                this.upload_params.tmp_upload_index = data
            },
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

            },
            upfile_change(e){
                var vm = this
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                var filename = files[0].name
                var target_index = e.currentTarget.dataset.fileindex
                this.page_data.content[target_index].value = filename
                $.ajaxSetup({async : false});
                $.post(this.report_api+'common/updateimg2ossByClient',{filename: Date.parse(new Date())/1000+'_._._'+target_index+'_._._'+filename,type:'website_file'},function (result) {
                    var pre_up2oss_params = JSON.parse(result)
                    var currect_uri = pre_up2oss_params.uri
                    var currect_param = pre_up2oss_params.param
                    vm.upload_params.up_action = currect_uri
                    vm.upload_params.up_param = currect_param
                    vm.upload_params.up_filename = 'file'

                })
                // xhr 上传
                // 变量声明
                var xhr = new XMLHttpRequest();
                var formData = new FormData();
                xhr.onload = function () {
                    var responseText = JSON.parse(xhr.responseText)
                    console.log(responseText)
                    var currect_data_index = responseText.file_path.match(/_\._\._(.*?)_\._\._/)[1]
                    vm.page_data.content[currect_data_index].value = responseText.file_path
                    console.log(vm.page_data.content[currect_data_index].value)

                }
                // 添加参数
                $.each(vm.upload_params.up_param , function (key, value) {
                    formData.append(key, value);
                });
                // 填充数据
                formData.append('file', files[0]);
                // 开始上传
                xhr.open('POST', vm.upload_params.up_action, true);
                // xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");  // 将参数解析成传统form的方式上传



                // 跨域上传时，传cookie
                xhr.withCredentials =  false;

                // 发送数据
                xhr.send(formData);


            },
            handleAvatarSuccess(res, file) {

                var currect_data_index = res.file_path.match(/_\._\._(.*?)_\._\._/)[1]
                this.page_data.content[currect_data_index].value = file.file_path
                this.page_data.content[parseInt(currect_data_index)].value=res.file_path

//                this.imageUrl = URL.createObjectURL(file.raw);
            },
            handleAvatarProgress(event, file, fileList){
//                console.log(event)
            },
            beforeAvatarUpload(file) {
                var vm = this
                $.ajaxSetup({async : false});
                $.post(this.report_api+'common/updateimg2ossByClient',{filename: Date.parse(new Date())/1000+'_._._'+this.upload_params.tmp_upload_index+'_._._'+file.name,type:'business_logo'},function (result) {
                    var pre_up2oss_params = JSON.parse(result)
                    var currect_uri = pre_up2oss_params.uri
                    var currect_param = pre_up2oss_params.param
                    console.log(currect_uri)
                    vm.upload_params.up_action = currect_uri
                    vm.upload_params.up_param = currect_param
                    vm.upload_params.up_filename = 'file'
                })
                $.ajaxSetup({async : true});

                const isJPGOrPng = (file.type === 'image/jpeg' || file.type === 'image/png');
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPGOrPng) {
                    this.$alert('只允许上传jpeg或png格式图片', '格式錯誤', {
                        confirmButtonText: '确定',
                        callback: action => {
                            this.$message({
                                type: 'info',
                                message:`action: ${action}`
                            });
                        }
                    });
                }
                if (!isLt2M) {

                    this.$alert('上传头像图片大小不能超过 2MB!', '图片大小错误', {
                        confirmButtonText: '确定',
                        callback: action => {
                            this.$message({
                                type: 'info',
                                message:`action: ${action}`
                            });
                        }
                    });
                }
                return isJPGOrPng && isLt2M;
            },
            build_page_data_to_timestamp(flag=true){

                if(flag){

                    for (let index in this.page_data.content){
                        if(this.page_data.content[index].type === 'time'){
                            this.page_data.content[index].value = Date.parse(this.page_data.content[index].value)/1000
                        }

                    }

                }
                else{
                    for (let index in this.page_data.content){
                        if(this.page_data.content[index].type === 'time'){
                            this.page_data.content[index].value =new Date(this.page_data.content[index].value*1000)
                        }

                    }

                }




            },
            final_update_data(){

                this.build_page_data_to_timestamp()
                console.log('this.page_data.content->',this.page_data.content)
                var formdata = resolveWidgetData2FormData(this.page_data.content)
                console.log('formdata->',formdata)
                var vm = this
                this.$validator.validateAll().then(() => {
                    vm.authing = true
                    axios.post(this.wechat_marketing_store.apihost+this.final_update_action,formdata,axios_config)
                        .then((res) => {
                            console.log('final-update->',res.data)
                            if((typeof res.data === 'object' && res.data.statistic.count>=1) || res.data>=1){
                                vm.$notify.success({
                                    title: '成功',
                                    message: '操作成功',
                                    offset: 100,
                                    duration:'1000'
                                });
                            }
                            else{
                                vm.$notify.success({
                                    title: '失败',
                                    message: '操作失败',
                                    type:'error',
                                    offset: 100,
                                    duration:'1000'
                                });

                            }
                            this.authing = false
                            setTimeout(function () {
                                vm.update_response = '0'
                                vm.$root.eventHub.$emit('page_model_update_response_done',res.data)
                            },600)
                            vm.build_page_data_to_timestamp(false)

                        })
                }).catch(() => {
                    // eslint-disable-next-line
                    vm.$notify.error({
                        title: '表单错误',
                        message: '请检查不符合规定的数据录入',
                        offset: document.body.clientHeight-300,
                        duration:'2000'
                    });
                });

            },
            fold_content_footer(){
                this.show_content_footer = !this.show_content_footer
            },
            close_page_model(){
                console.log('emit->close_page_model')
                this.$root.eventHub.$emit('close_page_model',1)
            }
        },
        beforeDestroy(){
//            this.$root.eventHub.$off('page_model_update_response_done')

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

        /*height: 35px;*/
        /*line-height: 35px;*/
        width: 100%;
    }
    .page_model .page_content li .timepicker {
        display: inline-block;
        max-width: 50%;
        width: 100%;
        min-width: 300px;
        margin-top 5px
    }
    .page_model .page_content li .timepicker div{
        width: 300px;

    }
    .page_model .page_content li .timepicker div input{
        border none
        margin-top -20px
        height 25px !important
        padding 0

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
        max-width: 65%;
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
    .upimg{
        width 120px
        height 120px;
        display inline-block

    }
    .upfile{
        width 60%
        height 60px;
        display inline-block

    }
    .upfile input{
        width 100% !important
    }
    .upfile .avatar-uploader{
        width 900px
    }
    .upfile .el-upload.el-upload--text{
        display inline-block
    }
    .upfile .el-upload__tip{
        display inline-block
    }
    .upfile .el-upload-list.el-upload-list--text
        display inline-block
        width 350px
        margin-left 50px

    .upfile .el-upload-list .el-icon-upload-success.el-icon-circle-check
        position absolute
        right: 15px
        top:8px

    .model_obj{
        max-width: 70%;
        width: 60%;
        min-width: 300px;
        display inline-block
        margin 0
        text-align left
        padding-left 100px
    }
    .avatar-uploader{
        width 120px;
        display: inline-block;

    }
    .avatar-uploader .el-upload {
        border: 2px dashed #b6b6b6;
        border-radius: 6px;
        cursor: pointer;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #20a0ff;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 120px;
        height: 120px;
        line-height: 120px;
        text-align: center;
    }
    .avatar {
        width: 120px;
        height: 120px;
        display: inline-block;
    }
    .el-date-table th{
        text-align center
    }
    .fold_bar{
        position absolute
        width 25px
        height 25px
        box-shadow 0 0 10px rgba(53, 53, 53, 0.53)
        border-radius 5px
        top 3px
        margin-left 5px
        background-color #e8e8e8
        transition all 1.5s
    }
    .fold_bar:hover{
        background-color #bcbcbc
    }
    .close_bar{
        position absolute
        width 25px
        height 25px
        box-shadow 0 0 10px rgba(53, 53, 53, 0.53)
        border-radius 5px
        top 3px
        margin-left 5px
        background-color #e8e1b5
        transition all 1.5s
        right 20px
        cursor pointer
    }
    .close_bar:hover{
        background-color rgba(188, 8, 10, 0.54)
    }

</style>
