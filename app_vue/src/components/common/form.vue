<template>
    <div id="common_form" style="z-index:9999;position: absolute;width: 400px;min-width: 400px;border-radius: 18px;overflow: hidden">
        <div @click="change_form_type" v-if="circle_flag_pos==='left'" class="circle_flag" :style="{cursor:'pointer',left: '0',marginLeft:'-0.15rem'}" ></div>
        <div @click="change_form_type" v-else="circle_flag_pos==='left'" class="circle_flag" :style="{cursor:'pointer',right: '0',marginRight:'-0.15rem'}" ></div>
        <div
                style="font-size: 0.1rem;width: 100%;height: 0.2rem;
                background-color: gainsboro;line-height: 0.2rem;
                letter-spacing: 0.03rem;text-align: center;
                box-shadow: 0 0 0 1px #bfbfbf;
                ">
            <span v-if="formtype === 'login'">登陆</span>
            <span v-else="formtype === 'login'">商家注册</span>
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
                <el-form-item label="密码"  prop="pass">
                    <el-input type="password" v-model="login_form.pass"></el-input>
                </el-form-item>
                <el-form-item style="margin-left: -80px">
                    <el-button type="primary" @click="submitLoginForm('login_form_rule')">登陆</el-button>
                    <el-button @click="resetForm('login_form_rule')">重置</el-button>
                </el-form-item>
            </el-form>
        </div>
        <div  v-else="formtype==='login'"  class="wechat_marketing_form reg_form" style="text-align: center;margin-right: 50px;margin-left: 20px;margin-top: 100px;">
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
                    <el-form-item label="密码"  prop="pass">
                        <el-input type="password" v-model="reg_form.pass"></el-input>
                    </el-form-item>
                    <el-form-item label="邮箱"  prop="email">
                        <el-input  v-model="reg_form.email"></el-input>
                    </el-form-item>
                    <el-form-item label="手机号" prop="phonenum">
                        <el-input  v-model="reg_form.phonenum">
                            <el-button slot="append" icon="" value="" @click="send_sms">发送验证码</el-button>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="验证码" prop="phonenum_code">
                        <el-input  v-model="reg_form.phonenum_code">

                        </el-input>
                    </el-form-item>
                    <el-form-item style="margin-left: -80px">
                        <el-button type="primary" @click="submitRegForm('reg_form_rule')">注册</el-button>
                        <el-button @click="resetForm('reg_form_rule')">重置</el-button>
                    </el-form-item>
                </el-form>
            </div>

    </div>

</template>



<script>
//    import router_build from '../../app_config/router-build'
    import Velocity from 'velocity-animate'
    import Vue from 'vue'
    import axios from 'axios'
    import {axios_config} from '../../api/axiosApi'
    import { mapGetters,mapActions } from 'vuex'

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
            }
            var validate_password = (rule, value, callback) => {
                var re = new RegExp(/^([a-zA-Z][\\.\w]{7,20}|(\w){64})$/);

                if (!value) {
                    return callback(new Error('密码不能为空'));
                }
                else if(!re.test(value)){
                    return callback(new Error('字母(开头),长度[8-20]'));
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
            }
            var validate_phone = (rule, value, callback) => {
                var re = new RegExp(/(^(0[0-9]{2,3}\\-)?([2-9][0-9]{6,7})+(\\-[0-9]{1,4})?$)|(^0?[1][3578][0-9]{9}$)/);

                if (!value) {
                    return callback(new Error('手机号名不能为空'));
                }
                else if(!re.test(value)){
                    return callback(new Error('手机号格式不正确'));
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
            }
            return {
                labelPosition: 'right',
                login_form:{
                    business_code:'',
                    username: '',
                    pass: '',
                },
                login_form_rule:{
                    username: [
                        { required: true, message: '请输入用户名', trigger: 'blur' },
                    ],
                    pass: [
                        { required: true, message: '请输入密码', trigger: 'blur' },
                    ],


                },
                reg_form: {
                    username: '',
                    pass: '',
                    email: '',
                    phonenum:'',
                    phonenum_code:'',
                    business_code:''
                },
                reg_form_rule:{
                    username: [
//                        { required: true, message: '请输入用户名', trigger: 'blur' },
                        { validator: validate_username,trigger: 'change' },
                    ],
                    pass: [
//                        { required: true, message: '请输入密码', trigger: 'blur' },
                        { validator: validate_password,  trigger: 'change' },
                    ],
                    email: [
//                        { required: true, message: '请输入邮箱', trigger: 'blur' },
                        { validator: validate_email, trigger: 'change' },
                    ],
                    phonenum: [
//                        { required: true, message: '请输入手机号', trigger: 'blur' },
                        { validator: validate_phone, trigger: 'change' },
                    ],
                    phonenum_code: [
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

            };
        },
        props:{
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
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        alert('submit!');
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            submitRegForm(formName) {

                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        alert('submit!');
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
                var re = new RegExp(/^((?![0-9]+$)(?![a-zA-Z]+$)[a-zA-Z][\\.\w]{7,20}|(\w){64})$/);
                if(!re.test(this.reg_form.phonenum)){

                }
                axios.post(this.wechat_marketing_store.apihost+'/common/sendSms',{account_type:'客户注册',phone:this.reg_form.phonenum},axios_config)
                    .then((res) => {
                        console.log(res)
                    })

            }
        },
        mounted(){

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
</style>
