<template>
    <div id="common_form" style="z-index:9999;position: absolute;width: 400px;min-width: 400px;border-radius: 18px;overflow: hidden">
        <div @click="change_form_type" v-if="circle_flag_pos==='left'" class="circle_flag" :style="{cursor:'pointer',left: '0',marginLeft:'-0.15rem'}" ></div>
        <div @click="change_form_type" v-else-if="circle_flag_pos==='right'" class="circle_flag" :style="{cursor:'pointer',right: '0',marginRight:'-0.15rem'}" ></div>
        <div
                style="font-size: 0.1rem;width: 100%;height: 0.2rem;
                background-color: gainsboro;line-height: 0.2rem;
                letter-spacing: 0.03rem;text-align: center;
                box-shadow: 0 0 0 1px #bfbfbf;
                ">
            <span v-if="formtype === 'login'">登陆</span>
            <span v-else-if="formtype === 'reg'">注册</span>
            <span v-else-if="formtype === 'show'" style="">账户信息</span>
        </div>
        <div v-if="formtype==='login'"  class="wechat_marketing_form login_form" style="text-align: center;margin-right: 50px;margin-left: 20px;margin-top: 100px;">
            <el-radio-group @change="radio_login_type_change" size="small"  v-model="radio_login_type" style="position: absolute;left:0;top:50px;">
                <el-radio-button  v-for="(item,index) in login_type_list" :key="index" :label="index"></el-radio-button>

            </el-radio-group>
            <el-form :label-position="labelPosition" ref="login_form_rule" :rules="login_form_rule" label-width="100px" :model="login_form">
                <transition name="el-zoom-in-top">
                    <el-form-item v-if="radio_login_type==='员工'" label="商家识别码" prop="business_code">
                        <el-input v-model="login_form.business_code"></el-input>
                    </el-form-item>
                </transition>

                <el-form-item label="用户名" prop="username">
                    <el-input v-model="login_form.username"></el-input>
                </el-form-item>
                <el-form-item label="密码"  prop="password">
                    <el-input type="password" v-model="login_form.password"></el-input>
                </el-form-item>
                <el-form-item style="margin-left: -80px">
                    <el-button type="primary" @click="submitLoginForm('login_form_rule')">登陆</el-button>
                    <el-button @click="resetForm('login_form_rule')">重置</el-button>
                </el-form-item>
            </el-form>
        </div>
        <div  v-else-if="formtype==='reg'"  class="wechat_marketing_form reg_form" style="text-align: center;margin-right: 50px;margin-left: 20px;margin-top: 100px;">
            <el-radio-group @change="radio_reg_type_change" size="small"  v-model="radio_reg_type" style="position: absolute;left:0;top:50px;">
                <el-radio-button  v-for="(item,index) in reg_type_list" :key="index" :label="index"></el-radio-button>

            </el-radio-group>

                <el-form :label-position="labelPosition"  ref="reg_form_rule" :rules="reg_form_rule" label-width="100px" :model="reg_form">
                    <transition name="el-zoom-in-top">
                        <el-form-item v-if="radio_reg_type==='员工'" label="商家识别码" prop="business_code">
                            <el-input v-model="reg_form.business_code"></el-input>
                        </el-form-item>
                    </transition>
                    <el-form-item label="用户名" prop="username">
                        <el-input v-model="reg_form.username"></el-input>
                    </el-form-item>
                    <el-form-item label="密码"  prop="password">
                        <el-input type="password" v-model="reg_form.password"></el-input>
                    </el-form-item>
                    <el-form-item label="邮箱"  prop="email">
                        <el-input  v-model="reg_form.email"></el-input>
                    </el-form-item>
                    <el-form-item label="手机号" prop="phone_num">
                        <el-input  v-model="reg_form.phone_num">
                            <el-button slot="append"
                                       icon=""  :disabled="btn_send_msg !== '发送验证码'"
                                       :loading="btn_send_msg !== '发送验证码'" type="info"
                                       value="" @click="send_sms">
                                {{ btn_send_msg==='发送验证码'?'发送验证码':btn_send_msg+ '  秒' }}
                            </el-button>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="验证码" prop="phone_num_auth_code">
                        <el-input  v-model="reg_form.phone_num_auth_code">

                        </el-input>
                    </el-form-item>
                    <el-form-item style="margin-left: -80px">
                        <el-button type="primary"  @click="submitRegForm('reg_form_rule')" >注册</el-button>
                        <el-button @click="resetForm('reg_form_rule')">重置</el-button>
                    </el-form-item>
                </el-form>
            </div>
        <div  v-else-if="formtype==='show' && userinfo"  class="wechat_marketing_form show_form" style="text-align: center;margin-left: -6px;margin-top: 30px;">
            <div class="img_div" style="height: 100px;margin: 15px auto">
                <img :src="userinfo.headpic?userinfo.headpic:'http://cdn.iamsee.com/headpic/demo.jpg'"
                     style="width: 100px;height: 100px;border-radius: 50px;box-shadow:  0 0 20px black">
            </div>
            <div class="userinfo_div" style="text-align: center;margin-top:15px;margin-left: 15px">
                <p >{{userinfo.identifier_code}}</p>
                <p style="display: inline-block">{{userinfo.username}}</p>
                <p style="">{{userinfo.phone_num}}</p>
                <div class="is_level_div">
                    <div class="level_div" style="">
                        <label class="label_type"
                                style="">
                            {{userinfo.lable_type}}</label>
                        <label v-if="userinfo.level_tag===''" style="display: inline-block" class="label_type"><b><i>lv0</i></b></label>
                        <div  v-else="userinfo.level_tag===''" style="display: inline-block">
                            <label v-for="item,index in JSON.parse(userinfo.level_tag)" style="display: inline-block">lv{{item==''?0:item}}</label>
                        </div>
                    </div>
                    <div class="level_div_mask" :style="{left:level_mask_left+'px'}">

                    </div>
                </div>
                <fieldset style="">
                    <legend style="padding-left: -20px;font-size: 15px;color: #808080;">登录信息</legend>
                    <p style="text-align: left">
                        <label style="width: 120px;text-align: right;">登录IP:</label> {{userinfo.fg.ip}}
                    </p>
                    <p style="text-align: left">
                        <label style="width: 120px;text-align: right;">登录时间:</label>
                        {{userinfo.fg.create_time}}
                    </p>
                    <p style="text-align: left">
                        <label style="width: 120px;text-align: right;">凭证有效期:</label>
                        {{userinfo.fg.last_login_time}}
                    </p>
                </fieldset>
            </div>
            <div class="ctrl_div" style="position: absolute;bottom: 100px;left:50%;margin-left: -100px">
                <input class="enter_backend" @click="enter_backend" type="button"
                       style="" value="进入后台">
                <input class="exit_login" @click="exit_login" type="button"
                       style="" value="退出登录">
            </div>

        </div>

    </div>

</template>



<script>
//    import router_build from '../../app_config/router-build'
    import Velocity from 'velocity-animate'
    import Vue from 'vue'
    import axios from 'axios'
    import {axios_config} from 'api/axiosApi'
    import { mapGetters,mapActions } from 'vuex'
    import {dbResponseAnalysis2WidgetData,countdownPrettyTimeByTimestmp} from 'api/lib/helper/dataAnalysis'
    import LocalVoucher from '../../api/localVoucherTools.js'

export default {

        data() {
            var validate_username = (rule, value, callback) => {
                var re = new RegExp(/^[a-zA-Z](\w){5,20}$/);
                if (!value) {
                    return callback(new Error('用户名不能为空'));
                }
                else if(!re.test(value)){
                    return callback(new Error('字母开头,长度[6-20]'));
                }
                else{
                  callback();
                }
            }
            var validate_password = (rule, value, callback) => {
                var re = new RegExp(/^([a-zA-Z][\\.\w]{7,20}|(\w){64})$/);

                if (!value) {
                    return callback(new Error('密码不能为空'));
                }
                else if(!re.test(value)){
                    return callback(new Error('字母(开头),长度[8-20]'));
                }
                else{
                  callback();
                }
            }
            var validate_email = (rule, value, callback) => {
                var re = new RegExp(/^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/);

                if (!value) {
                    return callback(new Error('邮箱不能为空'));
                }
                else if(!re.test(value)){
                    return callback(new Error('邮箱格式不正确'));
                }
                else{
                  callback();
                }
            }
            var validate_phone = (rule, value, callback) => {
                var re = new RegExp(/(^(0[0-9]{2,3}\\-)?([2-9][0-9]{6,7})+(\\-[0-9]{1,4})?$)|(^0?[1][3578][0-9]{9}$)/);

                if (!value) {
                    return callback(new Error('手机号名不能为空'));
                }
                else if(!re.test(value)){
                    return callback(new Error('手机号格式不正确'));
                }
                else{
                  callback();
                }

            }
            var validate_phone_code = (rule, value, callback) => {
                var re = new RegExp(/^\d{6}$/);
                if (!value) {
                    return callback(new Error('验证码不能为空'));
                }
                else if(!re.test(value)){
                    return callback(new Error('长度[6]位'));
                }
                else{
                  callback();
                }
            }
            return {
                labelPosition: 'right',
                login_form:{
                    business_code:'',
                    username: '',
                    password: '',
                },
                login_form_rule:{
                    username: [
                        { validator: validate_username,trigger: 'change' },
                    ],
                    password: [
                        { validator: validate_password,  trigger: 'change' },
                    ],
                },
                reg_form: {
                    username: '',
                    password: '',
                    email: '',
                    phone_num:'',
                    phone_num_auth_code:'',
                    business_code:''
                },
                reg_form_rule:{
                    username: [
//                        { required: true, message: '请输入用户名', trigger: 'blur' },
                        { validator: validate_username,trigger: 'change' },
                    ],
                    password: [
//                        { required: true, message: '请输入密码', trigger: 'blur' },
                        { validator: validate_password,  trigger: 'change' },
                    ],
                    email: [
//                        { required: true, message: '请输入邮箱', trigger: 'blur' },
                        { validator: validate_email, trigger: 'change' },
                    ],
                    phone_num: [
//                        { required: true, message: '请输入手机号', trigger: 'blur' },
                        { validator: validate_phone, trigger: 'change' },
                    ],
                    phone_num_auth_code: [
//                        { required: true, message: '请输入验证码', trigger: 'blur' },
                        { validator: validate_phone_code, trigger: 'change' },
                    ],

                },
                radio_login_type:'商家',
                login_type_list:{
                    '商家':'business',
                    '员工':'worker',
                    '管理员':'master',
                },
                radio_reg_type:'商家',
                reg_type_list:{
                    '商家':'business',
                    '员工':'worker',
                },
                btn_send_msg:'发送验证码',
              level_mask_left:-20,
              compute_last_login_time:{}

            };
        },
        props:{
              userinfo:{
                default: function () {
                  return {}
                }, //left_top
                required: false,

              },
            circle_flag_pos:{
                type: String,
                default: 'left', //left_top
                required: false,

            },

            formtype:{
                type: String,
                default: 'login', //left_top
                required: false,
            }

        },
        methods: {
            ...mapActions([
                'updateWechat_marketing_store',
            ]),
            radio_reg_type_change(data){
                console.log(data)
                this.radio_reg_type = data
                if(data === '员工'){
//                    this.reg_form_rule.business_code =  [{ required: true, message: '商家识别码', trigger: 'blur' }]
                    this.$set(this.reg_form_rule,'business_code',[{ required: true, message: '商家识别码', trigger: 'blur' }])
                }
                else{
                   if(this.reg_form_rule.hasOwnProperty('business_code')){
                       this.$delete(this.reg_form_rule,'business_code')
                   }

                }
                console.log(this.reg_form_rule)
            },
            radio_login_type_change(data){
                this.radio_login_type = data
                if(data === '员工'){
                    this.login_form_rule = {
                        business_code: [
                            { required: true, message: '商家识别码', trigger: 'blur' },
                        ],
                        username: [
                            { required: true, message: '请输入用户名', trigger: 'blur' },
                        ],
                            pass: [
                            { required: true, message: '请输入密码', trigger: 'blur' },
                        ],


                    }
                }
                else{
                    this.login_form_rule = {

                        username: [
                            { required: true, message: '请输入用户名', trigger: 'blur' },
                        ],
                        pass: [
                            { required: true, message: '请输入密码', trigger: 'blur' },
                        ],


                    }
                }
            },
            submitLoginForm(formName) {
                var vm = this
                this.$refs[formName].validate((valid) => {
                if (valid) {
                    console.log('emit->login_form_submit')
                    vm.login_form.account_type = vm.login_type_list[vm.radio_login_type]
                    vm.$root.eventHub.$emit('login_form_submit',vm.login_form)
                } else {
                    console.log('error submit!!');
                    return false;
                }
                });
            },
            submitRegForm(formName) {
              console.log('submitRegForm',formName)

              var vm = this
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        console.log('emit->reg_form_submit')
                        vm.reg_form.account_type = vm.reg_type_list[vm.radio_reg_type]
                        vm.$root.eventHub.$emit('reg_form_submit',vm.reg_form)
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            },
            change_form_type(){
                this.$root.eventHub.$emit('change_form_type',1)
            },
            send_sms(){
                var vm = this
                var re = new RegExp(/(^(0[0-9]{2,3}\\-)?([2-9][0-9]{6,7})+(\\-[0-9]{1,4})?$)|(^0?[1][3578][0-9]{9}$)/);
                if(!re.test(this.reg_form.phone_num)){
                    this.$message({
                        duration:2000,
                        showClose:true,
                        message: '手机号格不正确',
                        type: 'warning'
                    });
                }
                else{
                    axios.post(this.wechat_marketing_store.apihost+'loginorreg/sendSms',{account_type:this.reg_type_list[this.radio_reg_type],phone_num:this.reg_form.phone_num},axios_config)
                        .then((res) => {
                            if(res.data.code === 0){
                                vm.$message({
                                    duration:2000,
                                    showClose:true,
                                    message: res.data.desc,
                                    type: 'success'
                                });
                            }
                            else{
                                vm.$message({
                                    duration:2000,
                                    showClose:true,
                                    message: res.data.code + ' ' +res.data.desc,
                                    type: 'error'
                                });
                            }
                            vm.btn_send_msg = 10
                            let count_down_send_msg = setInterval(function () {
                                    if(vm.btn_send_msg <= 0){
                                      clearInterval(count_down_send_msg)
                                      vm.btn_send_msg = '发送验证码'
                                    }
                                    else{
                                      vm.btn_send_msg -= 1
                                    }
                            },1000)

                        })

                }

            },
            exit_login(){
              clearInterval(this.compute_last_login_time)



              this.$root.eventHub.$emit('exit_login',1)

            },
          enter_backend(){}
        },
        created(){
          LocalVoucher.checkStorageMode()
          LocalVoucher.initEngine()
        },
        mounted(){
          var vm = this
          this.level_mask_interval = setInterval(function () {

            if(vm.level_mask_left<0){
              vm.level_mask_left = 100
            }
            else{
              vm.level_mask_left = -20
            }

          },2000)

          this.compute_last_login_time = setInterval(function () {
                if(vm.userinfo.hasOwnProperty('fg')){
                  let last_time = countdownPrettyTimeByTimestmp(vm.userinfo.fg.create_time)
                  vm.$set(vm.userinfo.fg,'last_login_time',last_time)
                }
                else{
                  clearInterval(vm.compute_last_login_time)
                }

          })


        },
        updated(){

        },


        computed:{
            ...mapGetters([
                'wechat_marketing_store',
            ]),
        }

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
.circle_flag
    background: rgba(85, 85, 85, 0.51);
    width: 0.3rem;height: 0.3rem;border-radius: 0.1rem;position: absolute;
    top:50%;margin-top: -0.2rem;


.wechat_marketing_form input{
    background-color transparent
    border-color #929292

}

/*.reg_form:hover
    transition  all 3s
    transform-origin:0% 50%;
    transform: rotateY(60deg);
    */
#common_form .el-radio-button__inner{
    background-color transparent
}
#common_form .el-input__inner{
    color black !important
}
.el-message{
    top:65px !important
}enter_backend
#common_form .enter_backend{
     transition all 0.8s
     padding: 5px 20px;background-color: #57DCDF;
     border-radius 3px
 }
#common_form .enter_backend:hover{
    transition all 0.8s

    padding: 5px 20px;background-color: #50cbce;
    border-radius 3px
}
#common_form .exit_login{
    transition all 0.8s
    padding: 5px 20px;background-color: gray;

    border-radius 3px
}
#common_form .exit_login:hover{
    transition all 0.8s
    background-color #1e242b
    box-shadow  0 0 10px #ffffff
}
#common_form .userinfo_div p{
    display block
    text-align: center;
    text-transform: uppercase;
    background: 0 0;
    font-family: 'Graphik Web',sans-serif;
    font-style: normal;
    font-stretch: normal;
    font-size: 1em;
    color: #00B5AD !important
    font-weight 800
    letter-spacing: 2.9px;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    outline: 0;
    padding 10px 10px
}
#common_form .userinfo_div .is_level_div{
    display:block;position: absolute;right: 10px;top:60px;width: 100px;height: 20px;
    overflow hidden
}
#common_form .userinfo_div .level_div{
    font-size 16px
    position absolute
}


#common_form .userinfo_div .label_type{
    color: #00b5ad;padding: 0px 5px;background-color: #cdcdcd;border: 1px solid gainsboro;letter-spacing: 2px;border-radius: 2px
}
#common_form .userinfo_div .level_div_mask{
    height 20px
    width 20px
    position absolute
    font-size 16px
    background:-webkit-linear-gradient(0deg,rgba(255, 255, 255, 0),rgba(255, 255, 255, 0.7),rgba(255, 255, 255, 0));
    -webkit-transform:skewx(-25deg);
    -webkit-transition:all .5s;
    transition all 0.8s
    left -20px
}
#common_form .userinfo_div .level_div_mask:hover{
    transition all 0.8s
    left 40px
}
fieldset{padding:15px .305em ;margin:0 2px;border:1px solid silver}

legend{padding:.5em;border:0;width:auto}
</style>
