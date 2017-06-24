<template lang="html">
    <div :style="{width:editorShow_width+'px',backgroundColor:editorShow_color}">
        <div :id="'wangeditor_'+ random_id.random_key + '_' + random_id.index" class="is_wangeditor" :style="{height: editorShow_height+'px',backgroundColor:editorShow_color}">

        </div>
    </div>
</template>

<script>
require('../../../static/css/reset.css')
require('wangeditor/dist/css/wangEditor.css')
import wangEditor  from 'wangeditor'
import Vue from 'vue'
export default {
    data() {
        return {
            editor:'',
            editor2:'',
            editors:[],
            content: '',
            updateImageParam:
            {
                uri: "http://truesign-app.oss-cn-beijing.aliyuncs.com",
                param:{
                    type:'oss',
                    request_uri:'http://iamsee.com:5001/common/updateimg2ossByClientOneditor?app=o_app'
                },
//                param: {
//                    expire: 180000000001493300000,
//                    name: "10.jpg",
//                    key: "editor/1.gif",
//                    policy: "eyJleHBpcmF0aW9uIjoiMjAxNy0wNC0yN1QyMTo0NDo0NVoiLCJjb25kaXRpb25zIjpbWyJjb250ZW50LWxlbmd0aC1yYW5nZSIsMCwxMDQ4NTc2MDAwXSxbInN0YXJ0cy13aXRoIiwiJGtleSIsImVkaXRvclwvMWNhM2ZmNTkxMzM0YTllZmJlYTEzNzQzODVkZjYyMWQuZ2lmIl1dfQ==",
//                    OSSAccessKeyId: "82risUcDiLqqWZky",
//                    success_action_status: "200",
//                    callback: "eyJjYWxsYmFja1VybCI6Imh0dHA6XC9cL2lhbXNlZS5jb206NTAwMVwvY29tbW9uXC9jYl91cGRhdGVpbWcyb3NzQnlDbGllbnQiLCJjYWxsYmFja0JvZHkiOiJ0b2tlbj0mZmlsZV9uYW1lPWVkaXRvclwvMWNhM2ZmNTkxMzM0YTllZmJlYTEzNzQzODVkZjYyMWQuZ2lmJmltZ193aWR0aD0ke2ltYWdlSW5mby53aWR0aH0ifQ==",
//                    signature: "PZVU6dCrHb10yE2CPp+GsAUNwCk="
//                },
                status: 1,
                sys_msg: "oss"
            },
            alioss_param:{
                AccessKeyId : '82risUcDiLqqWZky',
                AccessKeySecret : 'gAiFRb4JIBEYsb7J1HarWVqiNf0tCm',
                region:'oss-cn-beijing.aliyuncs.com',
                bucket:'truesign-app'

            },

        }
    },
    props:{
        editorShow_width:{
            type: String,
            default: '100%',
            required: false,

        },
        random_id:{

            default: {
                index:'1',
                random_key:Math.random()
            },
            required:false
        },
        editorShow_height:{
            type: String,
            default: '200',
            required: false,

        },
        editorShow_color:{
            type: String,
            default: '#f5f5f5',
            required: false,

        },
        editor_content:{
            type: String,
            default: '等待录入',
            required: false,
        }


    },
    computed: {
    },
    watch:{
        random_id:{
            handler: function (val, oldVal) {
//                console.log('change',val)


            },
            deep: true
        }
    },

    mounted() {
        var vm = this
        Vue.nextTick( () => {
            var editor_id = 'wangeditor_'+this.random_id.random_key +'_'+this.random_id.index

            this.editor =  new wangEditor(editor_id);
            this.initEditorConfig()
            this.createEditor()


            $('.wangEditor-container').css('background-color',this.editorShow_color)
            $('.wangEditor-menu-container').css('background-color',this.editorShow_color)
        })

//        Vue.nextTick( () => {
//            var currect_wangeditor_id = this.editor.$txt[0].getAttribute('id')
////            console.log(currect_wangeditor_id)
//            $('.is_wangeditor').each(function (k,v) {
//                if(v.getAttribute('id') !== currect_wangeditor_id){
//                }
//
//            })
//        })

    },
    methods: {
        initEditorConfig(){
            var vm = this

            this.editor.config.withCredentials = false;

            this.editor.config.uploadImgUrl = this.updateImageParam.uri
            this.editor.config.uploadParams = JSON.stringify(this.updateImageParam.param)
            this.editor.config.uploadImgFileName = 'file'

            this.editor.config.menus =
                [
                    'source',
                    '|',
                    'bold',
                    'underline',
                    'italic',
                    'strikethrough',
                    'eraser',
                    'forecolor',
                    'bgcolor',
                    '|',
                    'quote',
                    'fontfamily',
                    'fontsize',
//                    'head',
                    'unorderlist',
                    'orderlist',
                    'alignleft',
                    'aligncenter',
                    'alignright',
                    '|',
                    'link',
                    'unlink',
                    'table',
//                    'emotion',
                    '|',
                    'img',
                    'video',
                    'insertcode',
                    '|',
                    'undo',
                    'redo',
                    'fullscreen'
                ];
            this.editor.config.fontsizes = {
                // 格式：'value': 'title'
                1: '10px',
                2: '13px',
                3: '16px',
                4: '19px',
                5: '22px',
                6: '25px',
                7: '28px'
            };
            this.editor.config.uploadImgFns.onload = function (resultText, xhr) {
//                console.log('上传完成进行回掉->',this,vm.editor)
                vm.uploadOnLoad(this,resultText, xhr)
            }
            // 配置 onchange 事件
            this.editor.onchange = function () {
                // 编辑区域内容变化时，实时打印出当前内容
                vm.random_id.index = Math.random()
                vm.editorchange()
            };
//            this.editor.config.customUpload = true;  // 设置自定义上传的开关
//            this.editor.config.customUploadInit = this.uploadInit  // 配置自定义上传初始化事件，uploadInit方法在上面定义了
        },
        createEditor() {
            var vm = this
            vm.editor.create();
            vm.editor.$txt.html(vm.editor_content)
        },
        uploadOnLoad(editor,resultText, xhr){
            resultText = JSON.parse(resultText)
            let file_path = resultText.file_path
            let originalName = this.editor.uploadImgOriginalName || '';
            editor.command(null, 'insertHtml', '<div><img src="' + file_path + '" alt="' + originalName + '" style="width:30%;"/></div>');
        },
        editorchange(){
            var editor_content = {
                text:this.editor.$txt.text(),
                html:this.editor.$txt.html()
            }
            this.$root.eventHub.$emit('editor_content',editor_content)

        },
        formatContent(content) {
            // handle
            // ...
            this.content = content
            this.outputContent()
        },
        outputContent() {
            this.$emit('input', this.content)
        }
    },
    components: {},
    beforeDestroy(){

    },
}
</script>

<style>
    p{
        line-height: 100%;
        font-size: 15px;
        margin:0 !important;
    }
    .clearfix input{
        color: black !important;
    }
    .txt-toolbar input{
        color:black !important;
    }
</style>