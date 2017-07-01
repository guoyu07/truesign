<template>
    <div id="form" style="z-index:9999;position: absolute;width: 400px;min-width: 400px;border-radius: 18px;overflow: hidden">
        <div @click="change_form_type" v-if="circle_flag_pos==='left'" class="circle_flag" :style="{cursor:'pointer',left: '0',marginLeft:'-0.15rem'}" ></div>
        <div @click="change_form_type" v-else="circle_flag_pos==='left'" class="circle_flag" :style="{cursor:'pointer',right: '0',marginRight:'-0.15rem'}" ></div>
        <div
                style="font-size: 0.1rem;width: 100%;height: 0.2rem;
                background-color: gainsboro;line-height: 0.2rem;
                letter-spacing: 0.03rem;text-align: center;
                box-shadow: 0 0 0 1px #bfbfbf;
                ">
            <span v-if="formtype === 'login'">登陆</span>
            <span v-else="formtype === 'login'">注册</span>
        </div>
        <div v-if="formtype==='login'"  class="wechat_marketing_form login_form" style="text-align: center;margin-right: 50px;margin-left: 20px;margin-top: 100px;">
            <el-radio-group @change="radio_login_type_change" size="small"  v-model="radio_login_type" style="position: absolute;left:0;top:50px;">
                <el-radio-button  v-for="(item,index) in login_type_list" :key="item" :label="item"></el-radio-button>

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
                <el-form-item>
                    <el-button type="primary" @click="submitLoginForm('login_form_rule')">登陆</el-button>
                    <el-button @click="">重置</el-button>
                </el-form-item>
            </el-form>
        </div>
        <div  v-else="formtype==='login'"  class="wechat_marketing_form reg_form" style="text-align: center;margin-right: 50px;margin-left: 20px;margin-top: 100px;">
                <el-form :label-position="labelPosition"  ref="reg_form_rule" :rules="reg_form_rule" label-width="100px" :model="reg_form">
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
                            <el-button slot="append" icon="" value="">发送验证码</el-button>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="验证码" prop="phonenum_code">
                        <el-input  v-model="reg_form.phonenum_code">

                        </el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="">注册</el-button>
                        <el-button @click="">重置</el-button>
                    </el-form-item>
                </el-form>
            </div>

    </div>

</template>



<script>
//    import router_build from '../../app_config/router-build'
    import Velocity from 'velocity-animate'
    import Vue from 'vue'

    export default {
        data() {

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
                    phonenum_code:''
                },
                reg_form_rule:{
                    username: [
                        { required: true, message: '请输入用户名', trigger: 'blur' },
                    ],
                    pass: [
                        { required: true, message: '请输入密码', trigger: 'blur' },
                    ],
                    email: [
                        { required: true, message: '请输入邮箱', trigger: 'blur' },
                    ],
                    phonenum: [
                        { required: true, message: '请输入手机号', trigger: 'blur' },
                    ],
                    phonenum_code: [
                        { required: true, message: '请输入验证码', trigger: 'blur' },
                    ],

                },
                radio_login_type:'管理员',
                login_type_list:{
                    'admin':'管理员',
                    'business':'商家',
                    'worker':'员工'
                }

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
            radio_login_type_change(data){
                this.radio_login_type = data
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
            change_form_type(){
                this.$root.eventHub.$emit('change_form_type',1)
            }
        },
        mounted(){

        },

        computed:{

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

</style>
