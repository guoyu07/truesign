<template>
  <div class="top_router_view"  style="background-color: rgba(255,255,255,0.68)">

    <el-row class="tac" style="top:10%;position: absolute">
      <el-col :span="2" style=";width: 200px !important;">

        <el-menu default-active="index" class="el-menu-vertical-demo" @select="handleSelect">
          <el-menu-item index="frontend"><i class="el-icon-setting"></i>返回前台</el-menu-item>
          <el-menu-item index="index"><i class="el-icon-setting"></i>首页设定</el-menu-item>
          <el-menu-item index="product"><i class="el-icon-setting"></i>产品设定</el-menu-item>
          <el-menu-item index="aboutus"><i class="el-icon-setting"></i>关于我们设定</el-menu-item>
          <el-menu-item index="account"><i class="el-icon-setting"></i>管理员账号设定</el-menu-item>
          <!--<el-menu-item index="sitearound"><i class="el-icon-setting"></i>访客留言</el-menu-item>-->
        </el-menu>
      </el-col>

    </el-row>
    <div id="content_ctrl" style="position: absolute;width: 80%;left:240px">
      <transition name="slide-fade-out-in" mode="out-in">

        <el-tabs v-if="targetMenu === 'index'" v-model="targetTab" @tab-click="handleClick">
          <el-tab-pane label="首页设定" name="index">
            <native_index></native_index>
          </el-tab-pane>

        </el-tabs>
        <el-tabs v-if="targetMenu === 'product'" v-model="targetTab" @tab-click="handleClick">
          <el-tab-pane label="产品设定" name="index">
            <native_product_type style="z-index:9999"></native_product_type>
          </el-tab-pane>

        </el-tabs>
        <el-tabs v-if="targetMenu === 'aboutus'" v-model="targetTab" @tab-click="handleClick">
          <el-tab-pane label="关于我们设定" name="index">

            <el-form label-position="top" label-width="80px" >
            <el-form-item label="公司">
              <el-input v-model="aboutus.company" style="color: black !important;"></el-input>
            </el-form-item>
            <el-form-item label="地址">
              <el-input v-model="aboutus.address"></el-input>
            </el-form-item>
            <el-form-item label="电话">
              <el-input v-model="aboutus.tel"></el-input>
            </el-form-item>
            <el-form-item label="网址">
              <el-input v-model="aboutus.site"></el-input>
            </el-form-item>
          </el-form>
            <input type="button" id="aboutus_btn" @click="update_aboutus" value="提交" style="padding: 5px 15px; width: 100%;border: 2px solid #00b900;background-color: rgba(255,255,255,0.6);color: black !important;">
          </el-tab-pane>

        </el-tabs>
        <el-tabs v-if="targetMenu === 'account'" v-model="targetTab" @tab-click="handleClick">
          <el-tab-pane label="账号设定" name="index" v-model="targetTab" @tab-click="handleClick">
            <!--<img width="12%" height="12%" style="margin-left: 50%;transform: translateX(-50%)" src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo/demo.jpg" >-->
            <el-form label-position="top" label-width="80px" style="width: 50%;margin-left: 50%;transform: translateX(-50%);margin-top: 2%">
              <el-form-item label="姓名">
                <el-input v-model="account.username" style="color: black !important;"></el-input>
              </el-form-item>
              <el-form-item label="密码">
                <el-input v-model="account.pass" type="password"></el-input>
              </el-form-item>
              <el-form-item label="电话">
                <el-input v-model="account.tel"></el-input>
              </el-form-item>
              <el-form-item label="邮件">
                <el-input v-model="account.email"></el-input>
              </el-form-item>
              <!--<el-form-item label="ip">-->
                <!--<el-input v-model="account.ip"></el-input>-->
              <!--</el-form-item>-->
            </el-form>
            <input type="button" id="account-btn" @click="update_account" value="提交" style="margin-left: 50%;transform: translateX(-50%);padding: 5px 15px; width: 50%;border: 2px solid #00b900;background-color: rgba(255,255,255,0.6);color: black !important;">
          </el-tab-pane>

        </el-tabs>
        <!--<el-tabs v-if="targetMenu === 'sitearound'" v-model="targetTab" @tab-click="handleClick">-->
          <!--<el-tab-pane label="留言查看" name="index">-->

          <!--</el-tab-pane>-->

        <!--</el-tabs>-->
      </transition>
      <transition name="slide-fade-out-in" mode="out-in">
      </transition>

    </div>

  </div>
</template>

<script>
    const ramjet = require('ramjet');
    import particles from './../../effect/particles.vue'
    import native_index from './backend_page/native_index.vue'
    import native_product_type from './backend_page/native_product_type.vue'

    import {apihost} from '../../../app_config/base_config.js'
    import axios from 'axios'
    import {axios_config} from '../../../api/axiosApi'
    const aboutus_api = apihost+'aboutus/'
    const account_api = apihost+'accounts/'
    import localVoucher from '../../../api/localVoucherTools'

    export default {
  		data () {
  			return {
                activeName: 'index',
                targetMenu:'index',
                targetTab:'index',
                aboutus:{
                    document_id:'',
                    company:'',
                    address:'',
                    tel:'',
                    site:''
                },
                account:{
                    document_id:"",
                    username:'',
                    pass:'',
                    img:'',
                    tel:'',
                    email:'',
                    ip:'',
                }
  			}
  		},
        methods:{
            handleSelect(key, keyPath) {
                console.log(key, keyPath);
                if(key === 'frontend'){
//                    this.$router.replace('siteshow_main')
                    window.location.href = '/'

                }
                this.targetMenu = key
            },

            getaccount(){
                console.log('-----------getaccount--------------')
                var vm = this
                axios.post(account_api+'getaccount',[],axios_config)
                    .then((res) => {
                        console.log(res)
                        vm.account = res.data.data[0]

                    })
            },
            update_account(){
                var vm = this
                var params = this.account
                axios.post(account_api+'updateaccount',params,axios_config)
                    .then((res) => {
                        console.log(res)
                        if(res.data){
                            this.$message('更新成功');

                        }
                        vm.getaccount()

                    })
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            update_aboutus(){
                var params = this.aboutus
                axios.post(aboutus_api+'updateAboutUs',params,axios_config)
                    .then((res) => {
                        console.log(res)
                        if(res.data){
                            this.$message('更新成功');

                        }
                    })
                this.getaboutus
            },
            getaboutus(){
                var vm = this
                axios.post(aboutus_api+'getAboutUs',[],axios_config)
                    .then((res) => {
                      vm.aboutus = res.data.data[0]
                        console.log(res)
                    })
            }
        },
        components:{
            native_index,
            native_product_type

        },
        mounted(){
            $('.top_menu').hide()
            localVoucher.checkStorageMode()
          localVoucher.initEngine()
          var auth_key = localVoucher.getValue('auth_key')
          if(auth_key){

          }
          else{
              window.location.href = '/'
          }
          this.getaboutus()
          this.getaccount()
        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">
.el-tabs__item
  color black
.el-input
  input
    color black !important
</style>
