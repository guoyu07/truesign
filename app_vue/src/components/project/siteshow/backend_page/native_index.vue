<template>
  <div class="top_router_view" >
      <div id="page_ctrl" style="width: 100%; height: 85%">
        <button class="add_page_btn" @click="addpage($event)" style="background-color: transparent;padding: 10px;border-radius:5px">新增主页屏</button>
        <div id="page_content" class="top_router_view" style="width: 80%;height:90%; overflow: auto;border: solid 2px snow;text-align: center" >
          <div class="div-scroll-block" v-for="(item,index) in page_list" :key="item" :data-index="index">

            <div class="ctrl_content">
              <input type="button" value="删除此屏"  @click="removepage($event)" :data-index="index" style="padding: 10px;position: absolute;background-color: red;color: white !important;left:5px"/>
              <input type="file" class="file" name="file" :data-index="index"  style="display: none"  @change="onFileChange">
              <input type="button" id="changebg" @click="changebg($event)" :data-index="index" value="修改背景图片" ::data-index="index"  style="padding: 10px;position: absolute;background-color: green;color: white !important;left:80px">
              <input v-if="item.document_id" type="button" value="更新此屏修改" @click="updatechange($event)" :data-index="index"  style="padding: 10px;position: absolute;background-color: green;color: white !important;right:2px"/>
              <input v-else="item.document_id" type="button" value="提交此屏修改" @click="updatechange($event)" :data-index="index"  style="padding: 10px;position: absolute;background-color: green;color: white !important;right:2px"/>

            </div>
            <img width="100%" height="100%" :src="item.bg" />
            <div class="div-scroll-info">


              <div class="textinfo">

                  <quill-editor style="" ref="myTextEditor"
                                v-model="item.info"

              >
                  </quill-editor>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
    import { quillEditor } from 'vue-quill-editor'

    import {apihost} from '../../../../app_config/base_config.js'
    import axios from 'axios'
    import {axios_config} from '../../../../api/axiosApi'
    const index_api = apihost+'index/'
  	module.exports = {
  		data: function () {
  			return {
                page_list : [

                ],
                dialogImageUrl: '',
                dialogVisible: false,
                imgfile:'',
  			}
  		},
        props:[
            'itemData',
        ],
        methods:{


  		    refrshindex(){
                axios.post(index_api+'getIndex')
                    .then((res) => {
                        console.log(res)
                        this.page_list = res.data.data
                    })
            },
            removepage(e){
              var target = $(e.currentTarget)
              var target_index = target.attr('data-index')
              var target_id = this.page_list[target_index].document_id

              if(target_id){

                console.log(apihost)
                axios.post(index_api+'delIndex',this.page_list[target_index],axios_config)
                    .then((res) => {
                        console.log(res)
                        if(res.data){
                            this.$message('删除成功');
                            this.refrshindex()
                        }
                    })

              }
              this.page_list.splice(target_index,1)

//
            },
            addpage(e){

                var new_page = {

                        bg:'123',
                        info:'123'

                    }
                this.page_list.unshift(new_page)
            },
            updatechange(e){
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var update_params = this.page_list[target_index]
                console.log(update_params)
                var target_value = target.val()
                if(target_value === '更新此屏修改'){
                    axios.post(index_api+'updateIndex',update_params,axios_config)
                        .then((res) => {
                            console.log(res)
                            if(res.data){
                                this.$message('更新成功');
                                this.refrshindex()
                            }

                        })
                }
                else if(target_value === '提交此屏修改'){
                    axios.post(index_api+'addIndex',update_params,axios_config)
                        .then((res) => {
                            console.log(res)
                            if(res.data){
                                this.$message('提交成功');
                                this.refrshindex()
                            }

                        })
                }


            },
            changebg(e){
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                console.log(target_index)
                $('.file[data-index='+target_index+']').click()
            },
            onFileChange(e) {
                var vm = this
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length){
                    return;
                }
                this.imgfile = files
                var imgfilename = files[0].name
                axios.post(apihost+'common/updateimg2ossByClient',{filename:imgfilename,type:'indexpage'},axios_config)
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

                        formData.append('file', (vm.imgfile)[0]);
                        axios.post(url,formData,axios_config)
                            .then((res) => {
                              console.log(res)
                              if(res.data.file_path){
                                  (vm.page_list)[target_index].bg = res.data.file_path
                              }
                            })

                    })
            },
        },
        components:{
            quillEditor

        },
        mounted(){
          this.refrshindex()
        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .div-scroll-block
    transition: all 1.5s;
    width 100%
    height 80%
    border 3px solid snow
    .div-scroll-info
      /*transition: all 1.5s;*/

      position absolute
      z-index 5
      width 100%
      right 0
      height 35%

      margin-top -30%
      background: hsla(0,0%,100%,.3);
      text-align center

    /*animation:parallax_up_down 1s 10*/
    .div-scroll-info::before
      content: '';
      position: absolute;
      top: 0; right: 0; bottom: 0; left: 0;
      filter: blur(20px);
    .textinfo

      color black
      font-size 100px
      margin 30px auto
</style>
