<template>
    <div style="width: 100%;height: 100%;overflow: hidden">
        <div id="is_form_container" style="">
            <transition name="el-fade-in-linear">
            <div v-if="wechat_marketing_store.token === ''" style="position: absolute;width: 800px;height: 580px;z-index:1;margin-left: 20px;margin-top: 20px;min-width: 10px">
                <common_form formtype="login"  class="login_form_container" circle_flag_pos="right" style=""></common_form>
                <common_form formtype="reg"  class="reg_form_container" circle_flag_pos="left" style=""></common_form>
            </div>
            </transition>
            <!--<transition name="el-zoom-in-top">-->

                <!--<div class="form_mask" v-if="form_status==='login'" :style="{left:0}">-->
                <!--</div>-->
                <!--<div class="form_mask" v-else="form_status==='login'" :style="{right:0}">-->
                <!--</div>-->

            <!--</transition>-->
            <div class="form_mask"  :style="{left:judge_mask_left(form_status)}">

                <div  @click="change_form_type"  class="effect_logo_container"
                      :style="{opacity:wechat_marketing_store.token?0.5:1}" style="">
                    <effectlogo logo_pos="relative_center" logo_width="150"
                                style="position: absolute;margin-top: 10px;margin-left: 10px;"></effectlogo>

                </div>
                <common_form v-if="wechat_marketing_store.token" formtype="show"  class="" circle_flag_pos="no"
                             style="position:absolute;z-index:20;margin-top: 20px;width: 100%;height: 100% "
                             :userinfo="wechat_marketing_store.userinfo">

                </common_form>


            </div>
        </div>
    </div>
</template>


<script>
    import common_form from '../../common/form.vue'
    import effectlogo from '../../loading/effect_logo.vue'
    import md5 from 'md5'
    import sha256 from 'sha256'
    import axios from 'axios'
    import {axios_config} from 'api/axiosApi'
    import {isEmptyValue} from '../../../api/lib/helper/dataAnalysis'
    import { mapGetters,mapActions } from 'vuex'

    export default {
        data(){
            return{
                form_status:'login',
                form_data:{},
                userinfo:{},
            }
        },

        components:{
            common_form,
            effectlogo
        },
        computed: {
          ...mapGetters([
            'wechat_marketing_store',
          ])
        },
        created(){
            var vm = this
            this.$root.eventHub.$on('change_form_type',() => {
                vm.change_form_type()
            })
            this.$root.eventHub.$emit('init_navmenu','form')

            this.$root.eventHub.$on('login_form_submit',function (formdata) {
                console.log('on->login_form_submit',formdata)
                let new_formdata = JSON.parse(JSON.stringify(formdata))
                new_formdata['password'] = sha256(md5(new_formdata['password']))
                vm.doLogin(new_formdata)
            })
            this.$root.eventHub.$on('reg_form_submit',function (formdata) {
                console.log('on->reg_form_submit',formdata)
                let new_formdata = JSON.parse(JSON.stringify(formdata))
                new_formdata['password'] = sha256(md5(new_formdata['password']))
                vm.doReg(new_formdata)
            })
            this.$root.eventHub.$on('exit_login',function (data) {
                  vm.updateWechat_marketing_store({
                        token:{
                          type:'del'
                        },
                        userinfo:{
                          type:'del'
                        }
                  })
                vm.check_login_status()
            })
            this.check_login_status()



        },
        mounted(){


        },
        beforeDestroy(){
          this.$root.eventHub.$off('reg_form_submit')
          this.$root.eventHub.$off('login_form_submit')
          this.$root.eventHub.$off('exit_login')
        },
        methods:{
            ...mapActions([
            'updateWechat_marketing_store',
            ]),
            change_form_type(){
                var vm = this
                if(isEmptyValue(this.updateWechat_marketing_store.token)){
                  vm.form_status = vm.form_status === 'login' ? 'reg':'login'
                }

            },
            doLogin(formdata){
              var vm = this
              axios.post(this.wechat_marketing_store.apihost+'LoginOrReg/login',formdata,axios_config)
                .then((res) => {
                  if(res.data.code === 0){
                    vm.$message({
                      duration:2000,
                      showClose:true,
                      message: res.data.desc,
                      type: 'success'
                    });
                    vm.updateWechat_marketing_store({
                      token:{
                        type:'update',
                        value:res.data.response.token,
                      },
                      userinfo:{
                        type:'update',
                        value:res.data.response.userinfo
                      }
                    })
                    vm.check_login_status()

                  }
                  else{
                    vm.$message({
                      duration:2000,
                      showClose:true,
                      message: res.data.code + ' ' +res.data.desc,
                      type: 'error'
                    });
                  }


                })
            },
            doReg(formdata){
            var vm = this

            axios.post(this.wechat_marketing_store.apihost+'LoginOrReg/reg',formdata,axios_config)
              .then((res) => {
                if(res.data.code === 0){
                  vm.$message({
                    duration:2000,
                    showClose:true,
                    message: res.data.desc,
                    type: 'success'
                  });

                  vm.updateWechat_marketing_store({
                    token:{
                      type:'update',
                      value:res.data.response.token,
                    },
                    userinfo:{
                      type:'update',
                      value:res.data.response.userinfo
                    }
                  })
                  vm.check_login_status()
                }
                else{
                  vm.$message({
                    duration:2000,
                    showClose:true,
                    message: res.data.code + ' ' +res.data.desc,
                    type: 'error'
                  });
                }


              })
          },
            judge_mask_left(data){
              if(data === 'login'){
                return  430+'px'
              }
              else if(data === 'reg'){
                return 0
              }
              else if(data === 'show'){
                return 215+'px'
              }
            },
            check_login_status(){
                if(this.wechat_marketing_store.token && this.wechat_marketing_store.userinfo){
                    this.form_status = 'show'
                }
                else{
                  this.form_status = 'login'
                }
            }

        },

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

    #is_form_container{
        width 860px;
        height 640px;
        background-color transparent
        left:50%;
        margin-left -430px;
        position absolute
        top 100px
        z-index 1000
    }
    #is_form_container .login_form_container{
       height: 600px;min-height: 600px;box-shadow: 0 0 20px black
    }
    #is_form_container .reg_form_container{
        margin-left:420px;height: 600px;min-height: 600px;box-shadow: 0 0 20px black
    }
    #is_form_container .form_mask{
        position: absolute;width: 425px;height: 100%;z-index:2;background-color: #DCDCDC

    }
    #is_form_container .form_mask{
        transition all 1.5s
        position absolute
    }
    #is_form_container .effect_logo_container{
        transition all 0.8s
        border-radius 100px;
        width:200px;height:200px;position:absolute;cursor:pointer;left: 50%;margin-left: -104px;top:50%;margin-top: -110px;z-index:10

    }
    #is_form_container .effect_logo_container:hover{
        box-shadow 0 0 55px black
        transition all 0.8s
    }
</style>