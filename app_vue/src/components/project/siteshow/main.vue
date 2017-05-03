<template>
    <div class="top_router_view">
        <navmenu style=""></navmenu>
        <div id="content_show" style="position: absolute;height: 100%;width: 100%;z-index:10">
            <transition name="slide-fade-out-in" mode="out-in">
                <div v-if="target_menu === '1'" key="1" style="width: 100%;height: 100%;position: absolute;">
                    <fullpage></fullpage>
                </div>
                <div v-else-if="target_menu === '2'" key="2" style="width: 100%;height: 100%;position: absolute;top:60px;">
                    <div id="select_product" style="position:absolute;width: 15%;height: 100%;top:15px">
                        <div id="search_product" style="">
                            <input v-model="query_card" style="text-align: center;width: 100%;border-bottom: 2px solid rgba(0,216,0,0.36);margin-left: 5px" placeholder="查询">
                        </div>
                        <transition-group style="width: 100%;text-align: center" id="type-list" name="type-list" tag="ul"
                                          v-on:before-enter="beforeEnter"
                                          v-on:enter="enter"
                                          v-on:after-enter="afterEnter"
                                          v-on:enter-cancelled="enterCancelled"
                                          v-on:leave="leave"
                                          v-bind:css="false"
                        >

                            <li style="width: 100%;height: 40px;line-height: 40px" v-for="(item,index) in product_type" :key="item" :data-index="index" :data-id="item.document_id" @click="change_type($event)">{{item.type}}</li>

                        </transition-group>
                    </div>
                    <div id="show_product" style="position:absolute;left:15%;width:85%;height: 100%;overflow-y: scroll;">
                        <transition-group id="card-list" name="card-list" tag="div"
                                          v-on:before-enter="beforeEnter"
                                          v-on:enter="enter"
                                          v-on:after-enter="afterEnter"
                                          v-on:enter-cancelled="enterCancelled"
                                          v-on:leave="leave"
                                          v-bind:css="false"
                        >
                           <card class="card_item"  v-for="(item,index) in buildCards" :key="item" :data-index="index" :item-data="item" ></card>

                        </transition-group>
                    </div>
                </div>
                <div v-else-if="target_menu === '3'" key="3" style="width: 100%;height: 100%;position: absolute;">
                    <aboutus :aboutus="aboutus"></aboutus>
                </div>
                <div v-else-if="target_menu === 'admin'" key="admin" style="width: 100%;height: 100%;position: absolute; ">
                    <div id="form-div"
                         style="width: 20%; height:auto;position: absolute;top:40%;left:50%;transform: translateX(-50%);background-color: rgba(255,255,255,0.47);border-radius:15px">
                        <el-form :model="LoginForm" :rules="LoginFormRules" ref="LoginForm" label-width="100px" class="demo-ruleForm" style="margin-right: 50px;margin-top: 30px">
                            <el-form-item label="用户名" prop="username">
                                <el-input v-model.number="LoginForm.username" auto-complete="off" ></el-input>
                            </el-form-item>
                            <el-form-item label="密码" prop="pass">
                                <el-input type="password" v-model="LoginForm.pass" auto-complete="off"></el-input>
                            </el-form-item>
                            <el-form-item >
                                <el-button type="primary" @click="submitForm('LoginForm')" style="">登录</el-button>
                                <!--<el-button @click="resetForm('LoginForm')" style="position: absolute">重置</el-button>-->
                            </el-form-item>
                            <label v-if="wrong_login_msg" style="width: 100%;text-align: center;color: red;font-size: 18px">{{wrong_login_msg}}</label>
                        </el-form>
                    </div>
                </div>
            </transition>
        </div>
        <transition name="fade-shadow" mode="out-in">
            <div id="content_dimmer" v-if="card_detail" style="position: absolute;height: 100%;width: 100%;z-index:11;background-color: rgba(125,136,146,0.95);top:60px;">
                <i @click="close_content_dimmer" style="font-size: 80px;line-height: 80px;position: fixed;right:30px;cursor: pointer">✘</i>
                <transition name="fade-shadow" mode="out-in">
                <div v-if="card_detail_info" style="height: 80%; width: 80%; padding: 5%;padding-left: 600px; background-color: silver;position: absolute;right:8%;top:6%; border-radius:25px;text-align: center;font-size: 22px; overflow: scroll">
                    <div  v-html="card_detail_info" style="width: 80%;overflow: auto;margin-left: 15%;">
                         {{ card_detail_info }}
                    </div>
                </div>
                </transition>
            </div>
        </transition>

    </div>
</template>
<script>
    import navmenu from '../../common/navmenu.vue'
    import card from '../../common/card.vue'
    import fullpage from '../../mainpage/fullpage.vue'
    import aboutus from './aboutus.vue'
    import Velocity from 'velocity-animate'
    import Vue from 'vue'

    import {apihost} from '../../../app_config/base_config.js'
    import axios from 'axios'
    import {axios_config} from '../../../api/axiosApi'
    const product_api = apihost+'product/'
    const aboutus_api  = apihost+'aboutus/'
    const account_api = apihost+'accounts/'
    import localVoucher from '../../../api/localVoucherTools'


    export default {
        components:{
            navmenu,
            card,
            fullpage,
            aboutus
        },
        data () {
            var validateUsername = (rule, value, callback) => {
                if (!value) {
                    return callback(new Error('用户名不能为空'));
                }
                else{
                    return callback()
                }

            };
            var validatePass = (rule, value, callback) => {
                if (!value) {
                    return callback(new Error('请输入密码'));
                }
                else{
                    return callback()
                }
            };
            return {
                LoginForm:{
                    username:'',
                    pass:''
                },
                LoginFormRules: {
                    username: [
                        { validator: validateUsername, trigger: 'blur' }
                    ],
                    pass: [
                        { validator: validatePass, trigger: 'blur' }
                    ],

                },
                query_card:'',
                wrong_login_msg:'',
                target_menu:'1',
                currentDate: new Date(),
                product:[

                ],
                product_type:[],
                currenctId:-1,
                cards:[],
                card_detail:true,
                hide_card_dom:{},
                card_detail_info:'',

                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                aboutus:{},

            }
        },
        methods:{
            getaboutus(){
                console.log('----------getaboutus--------------')
                var vm = this
                axios.post(aboutus_api+'getAboutUs',[],axios_config)
                    .then((res) => {
                        vm.aboutus = res.data.data[0]
                        console.log(res)
                    })
            },
            gettype(){
                var vm = this
                axios.post(product_api+'getType',[],axios_config)
                    .then((res) => {
                        vm.product_type=res.data.data

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
            close_content_dimmer(){

                this.card_detail = false
                $('#content_dimmer').empty()
                this.card_detail_info = ''
                console.log(this.hide_card_dom)
                if((this.hide_card_dom)[0]){
                    this.hide_card_dom.css('visibility','visible')

                }
            },
            handleSelect(key, keyPath) {
//                console.log(key, keyPath);
                this.target_menu = key
            },
            beforeEnter(el) {
                console.log('beforeEnter')
                el.style.opacity = 0
                el.style.width = 0

            },
            enter(el,done) {
                console.log('enter')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 1, width: '260px' },
                        { complete: done }
                    )
                }, delay)
            },
            afterEnter(el) {
                console.log('afterenter')

            },
            enterCancelled(el) {
                console.log('enterCancelled')
            },
            beforeLeave(el){
                console.log('befaoreLeave')
            },
            leave(el,done){
                console.log('Leave')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 0, width: 0 },
                        { complete: done }
                    )
                }, delay)
            },
            afterLeave(el){
                console.log('afterLeave')
            },
            leaveCancelled(el){
                console.log('leaveCancelled')
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            },
            submitForm(formName) {
                var vm = this
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        axios.post(account_api+'login',{username:vm.LoginForm.username,pass:vm.LoginForm.pass},axios_config)
                            .then((res) => {
                                 console.log(res)
                                if(res.data.response){
                                     var auth_key = res.data.encryption_msg

                                    localVoucher.setKeyValue('auth_key',auth_key)

                                    this.$message('登录成功');
                                    this.$router.push('siteshow_backend')
                                    setTimeout(function () {
                                        window.location.href='siteshow_backend'

                                    },1000)
                                }
                                else{
                                    this.$message('账号认证失败');

                                }
                            })
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
        },
        mounted(){
            $('.top_menu').hide()
            localVoucher.checkStorageMode()
            localVoucher.initEngine()
            this.getaboutus()
            this.gettype()

            this.card_detail = false

            const vm = this
            window.onresize = () => {
                return (() => {
                    window.screenWidth = document.body.clientWidth
                    window.screenHeight = document.body.clientHeight
                    vm.screenWidth = window.screenWidth
                    vm.screenHeight = window.screenHeight
                })()
            }
            console.log(vm.screenWidth)
            $('#content_show').css('height', vm.screenHeight-60)



            Vue.nextTick(function () {
                vm.cards = vm.product
            })
            this.$root.eventHub.$on('changeNavMenu',function (data) {
                console.log('changeNavMenu')
                console.log(data)
                if(data === '3'){
                    vm.getaboutus()
                }
                vm.target_menu = data
                vm.close_content_dimmer()
            })


            this.$root.eventHub.$on('showCardDetail',function (data) {
                console.log('-----------------------')

                var showtarget = $('.card_item[data-index='+data+']')

                vm.hide_card_dom = showtarget
                let el_rect = showtarget[0].getBoundingClientRect()
//                showtarget.addClass('hidden')

                var clone_showtarget = showtarget.clone()
                clone_showtarget.removeClass('hidden')

                clone_showtarget.css('position','absolute')
                clone_showtarget.css('left',el_rect.left)
                clone_showtarget.css('top',el_rect.top-70)
                clone_showtarget.css('padding',0)
                clone_showtarget.css('margin-left',0)

                console.log(el_rect)
                vm.card_detail = true
                Vue.nextTick(function () {
                    var parment_dom = $('#content_dimmer')
                    parment_dom.append(clone_showtarget)
                    showtarget.css('visibility','hidden')
                    clone_showtarget.animate({
                        top:'100px'
                    },{
                        duration: 500,
                    })
                    .animate({
                        left:'18%',
                    },{
                        duration: 1500,
                        complete:function () {
                            var post_content_dom = clone_showtarget.find(".post-content")
                            var post_module_dom = clone_showtarget.find(".post-module")
                            var type_dom = clone_showtarget.find(".category")
                            var title_dom = clone_showtarget.find(".title")
                            var description_dom = clone_showtarget.find(".description")
                            var img_dom = clone_showtarget.find("img")
                            var thumbnail_dom = clone_showtarget.find(".thumbnail")
                            post_module_dom.css('height',"auto")
                            post_content_dom.css('background','none')
                            thumbnail_dom.animate({
                                width: '200%',
                                height:'200%'
                            })
                            post_content_dom.animate({

                                position:'fixed',
                                top:'400px',
                            },{
                                duration: 1000,
                                complete:function () {

                                    type_dom.animate({
                                        width:'200%',

                                    },{
                                        duration: 500,
                                    })
                                    title_dom.css('color','#ffffff')
                                    title_dom.css('width','200%')
                                    description_dom.css('color','#ffffff')
                                    description_dom.css('width','200%')

                                    vm.card_detail_info = vm.product[data].info
                                }
                            })

                        }
                    })

                })

            })

        },

        watch: {
            screenWidth (val) {
                if (!this.timer) {
                    this.screenWidth = val
                    this.timer = true
                    let that = this
                    setTimeout(function () {
                        // that.screenWidth = that.$store.state.canvasWidth
                        console.log(that.screenWidth)
                        that.timer = false
                    }, 400)
                }
            },
            screenHeight (val) {
                if (!this.timer) {
                    this.screenHeight = val

                    this.timer = true
                    let that = this
                    setTimeout(function () {
                        // that.screenWidth = that.$store.state.canvasWidth
                        console.log(that.screenHeight)
                        that.timer = false
                    }, 400)
                }
            }
        },
        computed:{
            buildCards(){
                var vm = this
                var build_cardlist = []
                this.product.forEach(function (v,k) {

                        build_cardlist.push(v)

                })
                return build_cardlist.filter(function (item) {
                    return item.title.toLowerCase().indexOf(vm.query_card.toLowerCase()) !== -1
                })
//                return build_menulist
            },

        }

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
    input
        background-color transparent!important
    .fade-shadow-enter-active{
        /*transition: all 1s linear;*/
        transition: all 1s linear;

        opacity 1

    }
    .fade-shadow-enter{
        opacity 0


    }
    .fade-shadow-leave{
        opacity 1
    }
    .fade-shadow-leave-active{
        transition: all 1s linear;

        opacity 0
        /*animation:top_view_out 1s 1;*/
        /*transition: all 0.5s linear;*/




    }

    #select_product
        ul
            width 100%;
            height 100%
            margin-top 15%
            margin-left 5px
            li
                height 50px
                box-shadow: 0px 0px 15px #e4e7e1;
                line-height 50px
                font-size 18px
                cursor pointer
                color white
                font-weight 400
                letter-spacing 3px
                transition all 1.5s
            li:hover
                background-color rgba(228, 231, 225, 0.44)

    .slide-fade-out-in-enter{
        /*
        transition: all 1.5s
        opacity: 0
        transform translateX(-20%)
        */
    }
    .slide-fade-out-in-leave-active{
        /*
        transition: all 1.5s
        opacity: 0
        transform translateX(20%)
        */
        animation:content-fade-out 2s 1;

    }
    .slide-fade-out-in-enter-active{
        animation:content-fade-in 2s 1;
    }
    .slide-fade-out-in-leave{
        /*
        transition: all 1.5s
        */
    }

    .time {
        font-size: 13px;
        color: #999;
    }

    .bottom {
        margin-top: 13px;
        line-height: 12px;
    }

    .button {
        padding: 0;
        float: right;
    }

    .image {
        width: 100%;
        display: block;
    }

    .clearfix:before,
    .clearfix:after {
        display: table;
        content: "";
    }

    .clearfix:after {
        clear: both
    }

    @keyframes content-fade-out{
        0%   {
            z-index:10
            transform: scale(1,1);


        }
        50% {
            z-index:10
            transform: scale(0.9,0.9);

        }
        100% {
            z-index:10
            /*transform: scale(0.9,0.9);*/
            transform: translateY(-100%) scale(0.9,0.9);

            /*transform: translateY(-100%);*/
            background-color: #232e3d;
        }
    }
    @keyframes content-fade-in {
        0% {
            transform: translateY(-100%);
            background-color: #232e3d;

        }
        100% {
            transform: translateY(0);
            background-color: #3D4D5C;

        }
    }

</style>
