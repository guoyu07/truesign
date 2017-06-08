<template>
    <div id="form" style="z-index:9999;position: absolute;width: 400px;min-width: 400px;border-radius: 0.1rem;overflow: hidden">
        <div v-if="circle_flag_pos==='left'" class="circle_flag" :style="{left: '0',marginLeft:'-0.15rem'}" ></div>
        <div v-else="circle_flag_pos==='left'" class="circle_flag" :style="{right: '0',marginRight:'-0.15rem'}" ></div>
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

            <el-form :label-position="labelPosition" label-width="60px" :model="form_param">
                <el-form-item label="用户名">
                    <el-input v-model="form_param.name"></el-input>
                </el-form-item>
                <el-form-item label="密码"  prop="pass">
                    <el-input type="password" v-model="form_param.pass"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="">登陆</el-button>
                    <el-button @click="">重置</el-button>
                </el-form-item>
            </el-form>
        </div>
        <div  v-else="formtype==='login'"  class="wechat_marketing_form reg_form" style="text-align: center;margin-right: 50px;margin-left: 20px;margin-top: 100px;">
                <el-form :label-position="labelPosition" label-width="60px" :model="form_param">
                    <el-form-item label="用户名">
                        <el-input v-model="form_param.name"></el-input>
                    </el-form-item>
                    <el-form-item label="密码"  prop="pass">
                        <el-input type="password" v-model="form_param.pass"></el-input>
                    </el-form-item>
                    <el-form-item label="邮箱" >
                        <el-input  v-model="form_param.email"></el-input>
                    </el-form-item>
                    <el-form-item label="手机号" >
                        <el-input  v-model="form_param.phonenum"></el-input>
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
                form_param: {
                    name: '',
                    pass: '',
                    email: '',
                    phonenum:'',
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
    /*
.reg_form
    transition  all 3s
    transform-origin:0% 50%;
    transform: rotateY(60deg);
    */
</style>
