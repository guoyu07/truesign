<template>
    <div class="top_router_view" style="overflow: hidden" >
        <p class="third_router_tip">青鸾峰</p>
        <effectlogo   logo_pos='left_top' style="position: absolute;z-index:-1"></effectlogo>
        <div id="searchBar"
             style="
             display: inline-block;
             box-shadow: 0 0 10px #57DCDF;
             width: 30%;
             position: absolute;
             padding: 5px 15px;
             left:50%;transform:translateX(-50%);
             top:15px;
             line-height:12px;
            ">

            <input type="text" v-model="query" placeholder="快速查询" style="width: 100%;">
            <input v-if="parseInt(website.website_level) >= 3" type="button" @click="addapp" value="APP+" style="width:80px;margin-top: -15px;text-align:center;position: absolute;right: 80PX;top:18px; ">
            <input v-if="parseInt(website.website_level) >= 3" type="button" @click="touchctrl" value="CTRL" style="width:80px;margin-top: -15px;text-align:center;position: absolute;right: 0;top:18px; ">
        </div>
        <div id="cards-show"  style="height:90%;overflow-y:auto;position: absolute;margin-top:60px;width:100%;">

            <transition-group id="card-list" name="card-list" tag="div" style="position:absolute;width:1400px;height:auto;left: 50%;margin-left: -700px;"
                              v-on:before-enter="beforeEnter"
                              v-on:enter="enter"
                              v-on:after-enter="afterEnter"
                              v-on:enter-cancelled="enterCancelled"
                              v-on:leave="leave"
                              v-bind:css="false">
                <appCard  style="" class="card_item"  v-for="(item,index) in buildCards" :key="item" :data-index="index" :item-data="item" :level="website.website_level" :ctrl="ctrl"></appCard>
            </transition-group>

        </div>
        <chat></chat>
    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../../loading/effect_logo.vue'
    import login from './login.vue'
    import effect_line from '../../loading/effect_line.vue'
    import initsocket from '../../communicationModule/initSocket.vue'
    import appCard from '../../common/appCard.vue'
    import Velocity from 'velocity-animate'
    import Vue from 'vue'
    import { analysis_socket_response } from '../../../api/lib/helper/dataAnalysis'
    import axios from 'axios'
    import {axios_config} from '../../../api/axiosApi'
//    import LocalVoucher from '../../../api/LocalVoucherTools'
    import chat from './apps/chat.vue'


    export default {
        data(){
            return{
                appCards:[

                ],
                ctrl:0,
                query:'',
                imgfile:'',
                target:{
                    target_id:'',
                    target_index:'',
                },
                socket_ready:false,
            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ]),
            buildCards(){
                var vm = this
                var build_cardlist = []

                this.appCards.forEach(function (v,k) {
//                    if(vm.website.website_level<v.applevel){
//                        build_cardlist.unshift(v)
//                    }
//                    else{
//                        build_cardlist.push(v)
//
//                    }
                    build_cardlist.push(v)
                })
                return build_cardlist.filter(function (item) {
                    return item.apptitle.toLowerCase().indexOf(vm.query.toLowerCase()) !== -1
                })
//                return build_menulist
            },
        },
        created(){
            var vm = this

            this.$root.eventHub.$on('socket_ready',function (data) {
                vm.socket_ready = true
            })
            this.$root.eventHub.$on('socket_response',function (data) {
//                console.log('socket_response',data)
                var analysis_response = analysis_socket_response(data)
                if(analysis_response.response_type==='appCard_changeimg'){
                    var uri = analysis_response.response_oss_uri
                    var oss_config = analysis_response.response_oss_config
                    var formData = new FormData();
                    // use oss_config to upload
                    if(oss_config){
                        for(var key in oss_config){
                            formData.append(key,oss_config[key]);
                        }
                    }
//                        oss api 不会根据参数类型进行顺序调用,文件应该放到上传列表key的后面,最好放到最后一位

                    formData.append('file', (vm.imgfile)[0]);
                    vm.oss_lock = true
                    axios.post(uri,formData,axios)
                        .then((res) => {
                            //console.log(res)
                            if(res.data.file_path){
                                //console.log('res->',res.data)
//                            vm.itemData.img = res.data.file_path
                                vm.appCards[vm.target.target_index].appimg = res.data.file_path


                            }
                        })
                }
                else if(analysis_response.response_type==='get_appCards'){
                    console.log('getapprules->',analysis_response.reponse_data)
                    vm.appCards = []
                    analysis_response.reponse_data.forEach(function (item) {
                        vm.appCards.push(item)
                    })
                    vm.updateWebSite({
                        apprules:analysis_response.reponse_data
                    })
                }
                else if(analysis_response.response_type === 'updateAppRule'){
                    if(analysis_response.response_status){
                        vm.$root.eventHub.$emit('showtip',{
                            content:'更新成功',
                            tipwidth:'300',
                            tiptime:'2000'
                        })
                    }
                    else{
                        vm.$root.eventHub.$emit('showtip',{
                            content:'更新失败',
                            tipwidth:'300',
                            tiptime:'2000'
                        })
                    }
                }
                else if(analysis_response.response_type === 'addAppRule'){
                    if(analysis_response.response_status){
                        vm.$root.eventHub.$emit('showtip',{
                            content:'新增成功',
                            tipwidth:'300',
                            tiptime:'2000'
                        })
                    }
                    else{
                        vm.$root.eventHub.$emit('showtip',{
                            content:'新增失败',
                            tipwidth:'300',
                            tiptime:'2000'
                        })
                    }
                    vm.getapprules()
                }
                else if(analysis_response.response_type === 'delAppRule'){
                    if(analysis_response.response_status){
                        vm.$root.eventHub.$emit('showtip',{
                            content:'删除成功',
                            tipwidth:'300',
                            tiptime:'2000'
                        })
                    }
                    else{
                        vm.$root.eventHub.$emit('showtip',{
                            content:'删除失败',
                            tipwidth:'300',
                            tiptime:'2000'
                        })
                    }
                    vm.getapprules()
                }
                else if(analysis_response.response_type === 'getWebSiteLevel'){
                    console.log('getWebSiteLevel->',analysis_response)
                    if(analysis_response.response_status){
                        vm.updateWebSite({
                            website_level:analysis_response.response_website_level
                        })
                    }
                }

            })



        },
        mounted(){
            var vm = this
            this.$root.eventHub.$on('app_img_change_event',function (data) {
                var target_index = data.target_index
                var target_id = data.target_id
                vm.target.target_id = parseInt(target_id)
                vm.target.target_index = parseInt(target_index)
                vm.imgfile = data.imgfile
                var params = {
                    to:null,
                    payload_type:'appCard_changeimg',
                    payload_data:{
                        type:data.type,
                        filename:data.filename,
                    },
                    yaf:{
                        module:'index',
                        controller:'common',
                        action:'updateimg2ossByClient'
                    }
                }
                //console.log('appCard_changeimg->param:',params)
                vm.$root.eventHub.$emit('socket_send',params)
            })



            this.getapprules()
            this.getWebSiteLevel()
        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            touchctrl(){
                this.ctrl  = this.ctrl===0?1:0
            },
            addapp(){
                var new_app = {
                    appname:'demo',
                    apptitle:'demo',
                    apptype:'demo',
                    applevel:'1',
                    appimg:'http://truesign-app.oss-cn-beijing.aliyuncs.com/indexpage/320x180.gif',
                    apptable:'appdemotable'
                }
                this.appCards.push(new_app)

            },
            getapprules(){
                var vm = this
                var params = {
                    to:null,
                    payload_type:'get_appCards',
                    payload_data:{},
                    yaf:{
                        module:'index',
                        controller:'apps',
                        action:'getAppRule'
                    }
                }
                console.log('getapprules')
                vm.$root.eventHub.$emit('socket_send',params)
            },
            getWebSiteLevel(){
                var params = {
                    to:null,
                    payload_type:'getWebSiteLevel',
                    payload_data:{},
                    yaf:{
                        module:'index',
                        controller:'website',
                        action:'getWebSiteLevel'
                    }
                }
                this.$root.eventHub.$emit('socket_send',params)
            },
            beforeEnter(el) {
                //console.log('beforeEnter')
                el.style.opacity = 0
                el.style.width = 0

            },
            enter(el,done) {
                //console.log('enter')
                var delay = el.dataset.index * 80

                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 1, width: '320px' },
                        { complete: done }
                    )
                }, delay)
            },
            afterEnter(el) {
                //console.log('afterenter')

            },
            enterCancelled(el) {
                //console.log('enterCancelled')
            },
            beforeLeave(el){
                //console.log('befaoreLeave')
            },
            leave(el,done){
                //console.log('Leave')
                var delay = el.dataset.index * 50
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 0, width: 0 },
                        { complete: done }
                    )
                }, delay)
            },
            afterLeave(el){
                //console.log('afterLeave')
            },
            leaveCancelled(el){
                //console.log('leaveCancelled')
            },
        },

        components:{
            effectlogo,
            appCard,
            chat
        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
#cards-show
    width 100%
    height 100%
    /*padding-top 60px*/
</style>
