<template>
    <div id="product_content">
        <div id="product_type">
            <div  v-for="(item,index) in product_type" style="display: inline-block">
                <i @click="del_product_type($event)" :data-index="index" :data-id="item.document_id"  style="cursor:pointer;position: absolute;color: red;font-size: 18px;margin-left: 152px;margin-top: -2px" >x</i>
                <i @click="change_product_type_name($event)" :data-index="index" :data-id="item.document_id"  style="cursor:pointer;position: absolute;color: #00cc00;font-size: 18px;margin-left: 148px;margin-top: 18px" >✎</i>
                <i v-if="item.isSelected" style="cursor:pointer;position: absolute;color: red;font-size: 50px;color: #00cb00;margin-top: 8px" >·</i>

                <input   class="product_type_items" @click="change_type($event)" :data-index="index" :data-id="item.document_id" v-model="item.type"
                       :readonly="item.isreadonly"
                       @keyup.13="confirm_ok($event)">


            </div>
            <input class="product_type_item" @click="add_product_type" type="text" value="新增类型+"
                   style="padding: 5px 5px;background-color: white;border: white 2px solid;text-align: center;cursor: pointer;color: black !important;margin-left: -7px" readonly>
        </div>
        <hr>
        <div  v-if="currenctId>=0" id="product" style="width: 100%;height: 800px;overflow: auto">
            <input class="product_type_item" @click="add_product($event)" :type-id="currenctId" type="text" value="新增资料+"
                   style="width:15%;padding: 5px 5px;background-color: white;border: white 2px solid;text-align: center;cursor: pointer;color: black !important;" readonly>

            <input type="text" placeholder="检索产品（按标题）" v-model="query_product" style="padding: 5px 15px; color: black !important;border: solid 2px #00b600;width: 84%;text-align: center;cursor: pointer" >

            <hr>
            <ul style="width: 100%;height: 100%;">
                <li v-for="(item,index) in computedProduct" style="width: 100%;height: 55%;border-bottom: solid 3px white;overflow: scroll">
                    <div>
                        <div class="left_product" style="width: 15%; float: left">
                            <input type="file" class="product_file" @change="onFileChange($event)" :type-id="currenctId" :data-id="item.document_id" :data-index="index" style="display: none">
                            <input type="button" value="更换图片" @click="change_product_bg($event)"  :type-id="currenctId" :data-id="item.document_id" :data-index="index" style="padding: 5px 15px;text-align: center;;background-color: red; width: 100%" readonly >
                            <img width="100%" height="15%"  :src="item.img" style="">
                            <input type="text" placeholder="排序" v-model="item.sort_ord" :data-id="item.title" style="margin-top:2%;padding: 5px 15px; color: black !important;border: solid 2px white;width: 100%">
                            <input type="text" placeholder="标题" v-model="item.title" :data-id="item.title" style="margin-top:2%;padding: 5px 15px; color: black !important;border: solid 2px white;width: 100%">
                            <input type="text" placeholder="简介" v-model="item.note" :data-id="item.note" style="margin-top:2%;padding: 5px 15px; color: black !important;border: solid 2px white;width: 100%">
                            <input type="text" v-if="item.document_id" value="更新此资料" @click="update_product($event)" :type-id="currenctId" :data-id="item.document_id" :data-index="index"  style="margin-top:2%;padding: 5px 15px; color: black !important;border: solid 2px #00b600;width: 100%;text-align: center;cursor: pointer"readonly>
                            <input type="text" v-else="item.document_id" value="提交此资料" @click="add_new_product($event)" :type-id="currenctId" :data-id="item.document_id" :data-index="index"  style="margin-top:2%;padding: 5px 15px; color: black !important;border: solid 2px #00b600;width: 100%;text-align: center;cursor: pointer"readonly>
                            <input type="text" v-if="item.document_id" value="删除此资料" @click="del_product($event)" :type-id="currenctId" :data-id="item.document_id" :data-index="index" style="margin-top:2%;padding: 5px 15px; color: black !important;border: solid 2px #e15f2b;width: 100%;text-align: center;cursor: pointer" readonly>
                        </div>
                        <div class="right_product" style="width: 84%;float: right">
                            <quill-editor ref="myTextEditor"
                                          v-model="item.info"

                            >
                            </quill-editor>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</template>



<script>
    import Velocity from 'velocity-animate'
    import Vue from 'vue'
    import {apihost} from '../../../../app_config/base_config.js'
    import axios from 'axios'
    import {axios_config} from '../../../../api/axiosApi'
    const product_api = apihost+'product/'
    import { quillEditor } from 'vue-quill-editor'
    export default {
        data() {
            return{
                product_type:[],
                product:[],
                currenctId:-1,
                product_img_file:'',
                query_product:''
            }
        },
        mounted(){
            this.gettype()




        },
        methods:{
            gettype(){
                var vm = this
                axios.post(product_api+'getType',[],axios_config)
                    .then((res) => {
                        vm.product_type=[]

                        res.data.data.forEach(function (item) {
                            item.isreadonly = true
                            item.isSelected = false
                            vm.product_type.push(item)

                        })
                        vm.change_type()

                    })
            },
            getproduct(type_id){
                var vm = this
                var params = {type_id:type_id}
                axios.post(product_api+'getProduct',params,axios_config)
                    .then((res) => {
//                        console.log(res)
                        vm.product = res.data.data
                    })
            },
            add_product(e){
                var target = $(e.currentTarget)
                var target_id = target.attr('type-id')
                var new_product = {
                    img:'http://truesign-app.oss-cn-beijing.aliyuncs.com/demo/demo.jpg',
                    title:'demo',
                    note:'demo',
                    info:'demo'
                }

                this.product.unshift(new_product)

            },
            add_new_product(e){
                var vm = this
                var target = $(e.currentTarget)
                var target_id = target.attr('data-id')
                var target_index = target.attr('data-index')
                var target_type_id = target.attr('type-id')
                var params = this.product[target_index]
                params.type_id = target_type_id
                axios.post(product_api+'addProduct',params,axios_config)
                    .then((res) => {
                        console.log(res)
                        if(res.data){
                            this.$message('提交成功');
                            vm.getproduct(target_type_id)
                        }
                    })

            },
            update_product(e){
                var vm = this
                var target = $(e.currentTarget)
                var target_id = target.attr('data-id')
                var target_index = target.attr('data-index')
                var target_type_id = target.attr('type-id')
                var params = this.product[target_index]
                axios.post(product_api+'updateProduct',params,axios_config)
                    .then((res) => {
                        console.log(res)
                        if(res.data){
                            this.$message('更新成功');
                            vm.getproduct(target_type_id)

                        }
                    })
            },
            del_product(e){
                var vm =this
                var target = $(e.currentTarget)
                var target_type_id = target.attr('type-id')
                var target_id = target.attr('data-id')
                var target_index = target.attr('data-index')
                var params = this.product[target_index]
                axios.post(product_api+'delProduct',params,axios_config)
                    .then((res) => {
                        console.log(res)
                        if(res.data){
                            this.$message('删除成功');
                            vm.getproduct(target_type_id)

                        }
                    })
                this.product.splice(target_index,1)
            },
            add_product_type(e){
                var new_product_type = {
                    sort_ord:0,
                    type:'',
                    isreadonly:false
                }
                this.product_type.push(new_product_type)
            },
            change_type(e){
                console.log(this.product)
                if(e){
                    var target = $(e.currentTarget)
                    var target_index = target.attr('data-index')
                    var target_id = target.attr('data-id')
                    console.log(target_index,target_id)
                    if(target_index){
                        this.product_type.forEach(function (item) {
                            item.isSelected = false
                        })
                        this.product_type[target_index].isSelected = true
                        this.currentIndex = target_index
                    }
                }

                else{
                    console.log(this.product_type)

                    if(this.product_type.length > 0){
                        this.product_type[0].isSelected = true
                        this.currentIndex = 0
                        target_id =  this.product_type[0].document_id
                    }

                }
                this.currenctId = target_id
                this.getproduct(target_id)


            },
            del_product_type(e){
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var target_id = target.attr('data-id')
                if(target_id){
                    axios.post(product_api+'delType',{document_id:target_id},axios_config)
                        .then((res) => {



                        })
                }
                this.product_type.splice(target_index,1)
                this.gettype()

            },
            confirm_ok(e){
                var target = $(e.currentTarget)
                var target_value = target.val()
                var target_index = target.attr('data-index')

                if(target_value){

                    this.product_type[target_index].type = target_value
                    this.product_type[target_index].isreadonly = true
                    console.log( (this.product_type)[target_index])
                    var params = (this.product_type)[target_index]
                    if(this.product_type[target_index].document_id){
                        axios.post(product_api+'updateType',params,axios_config)
                            .then((res) => {
                                console.log(res)


                            })
                    }
                    else{
                        axios.post(product_api+'addType',params,axios_config)
                            .then((res) => {
                                console.log(res)


                            })
                    }


                }
                this.gettype()
            },
            change_product_type_name(e){
                var target = $(e.currentTarget)
                var target_value = target.val()
                var target_index = target.attr('data-index')

                this.product_type[target_index].isreadonly = false
            },
            change_product_bg(e){
                var target = $(e.currentTarget)
                var target_value = target.val()
                var target_index = target.attr('data-index')
                var target_type_id = target.attr('type_id')
                $('.product_file[data-index='+target_index+']').click()
            },
            onFileChange(e){
                var vm = this
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var target_id = target.attr('data-id')
                var target_type_id = target.attr('type-id')
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                this.product_img_file = files
                var imgfilename = files[0].name
                axios.post(apihost+'common/updateimg2ossByClient',{filename:imgfilename,type:'product'},axios_config)
                    .then((res) => {

                        var url = res.data.uri
                        var newparam = res.data.param
                        var formData = new FormData();
                        // use FormData to upload
                        if(newparam){
                            for(var key in newparam){
                                formData.append(key,newparam[key]);
                            }
                        }
//                        oss api 不会根据参数类型进行顺序调用,文件应该放到上传列表key的后面,最好放到最后一位

                        formData.append('file', (vm.product_img_file)[0]);
                        axios.post(url,formData,axios)
                            .then((res) => {
                                console.log(res)
                                if(res.data.file_path){
                                    vm.product[target_index].img = res.data.file_path
                                }
                            })

                    })
            }

        },
        computed:{
            computedProduct: function () {
                var vm = this
                return this.product.filter(function (item) {
                    return item.title.toLowerCase().indexOf(vm.query_product.toLowerCase()) !== -1
                })

            }
        },
        components: {
            quillEditor
        }

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
.product_type_items
    padding: 5px 5px;background-color: slategray;border: white 2px solid;text-align: center;cursor: pointer;


</style>
