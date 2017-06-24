<template>
    <div class="top_router_view" style="background-color: transparent;text-align: center;color: white" >
        <div id="app-shadowsocks">
            <div id="app-shadowsocks-header" style="padding-left:50px;color:#18b584;text-align:left;width: 100%;height: 60px;background-color:white;box-shadow: 0 0 20px black;font-family: 'Graphik Web', sans-serif !important;font-size: 25px;line-height: 60px">
                SHADOWSOCKS
                <!--<input type="button" value="fresh" @click="get_shadowsocks_status">-->
                <el-switch
                        v-model="appswitch"
                        on-color="#13ce66"
                        off-color="#ff4949"
                        style="position: absolute;top: 20px;right: 10px"
                        @change="change_shadowsocks_status"
                >
                </el-switch>
            </div>
            <div id="app-shadowsocks-node" style="box-shadow: 0 0 10px black;margin-top:15px;width: 100%;height: 280px;overflow: auto;background-color: transparent">
                <li style="border-radius: 5px;background-color: rgba(255,255,255,0.83);border: 1px dashed rgba(128,128,128,0.27);color: black" >
                    <label style="width: 120px">服务器IP</label>
                    <label style="width: 120px">区域</label>
                    <label style="width: 120px">加密方式</label>
                    <label style="width: 120px">
                        服务器状态
                    </label>
                    <label style="width: 80px"></label>
                </li>
                <transition-group name="flip-list" tag="ol" style="margin: 0">
                    <li style="border-radius: 5px;background-color: rgba(255,255,255,0.83);border: 1px dashed rgba(128,128,128,0.24);color: black" v-for="(item,index) in ssnode" :key="item">
                        <label style="width: 120px"><input style="text-align: center" v-model="item.server_ip" :readonly="item.readonly"> </label>
                        <label style="width: 120px"><input style="text-align: center" v-model="item.location" :readonly="item.readonly"></label>
                        <label style="width: 120px"><input style="text-align: center" v-model="item.encryption_way" :readonly="item.readonly"></label>
                        <label style="width: 120px" v-if="item.readonly">
                            <p v-if="item.server_status">正常</p>
                            <p v-else="item.server_status">关闭</p>
                        </label>
                        <label style="width: 120px" v-else="item.readonly">
                            <el-switch
                                    v-model="item.server_status"
                                    on-color="#13ce66"
                                    off-color="#ff4949"
                                    style=""
                            >
                            </el-switch>
                        </label>
                        <label v-if="item.readonly" style="width: 40px;background-color: gray;border-radius: 8px;cursor: pointer;line-height: 15px" ></label>
                        <label v-else="item.readonly" @click="set_ssnode(1,$event)" :data-index="index" :data-id="item.document_id" style="width: 30px;background-color: gray;border-radius: 8px;cursor: pointer;line-height: 15px" >保存</label>
                        <label v-if="item.readonly" style="width: 40px;background-color: gray;border-radius: 8px;cursor: pointer;line-height: 15px" ></label>
                        <label v-else="item.readonly" @click="set_ssnode(0,$event)" :data-index="index" :data-id="item.document_id" style="width: 30px;background-color: gray;border-radius: 8px;cursor: pointer;line-height: 15px" >删除</label>
                    </li>
                </transition-group>
                <li v-if="check_level" @click="add_ssnode" style="cursor:pointer;color:black;font-weight: 800;font-size: 20px;line-height: 28px;background-color: white;border-radius: 5px;border: 1px solid black">[ add ]</li>
            </div>
            <div id="app-shadowsocks-passandport"
                 style="margin-top: 20px;text-align: left;background-color: whitesmoke;box-shadow: 0 0 10px black;;color: black;padding-top: 10px;padding-bottom: 10px">
                <div v-if="port || check_level">
                    <label style="width: 200px;text-align: right;margin-right: 50px">端口</label>
                    <input  style="" v-model="port" :readonly="!check_level" />
                    <!--<label v-if="check_level && port" style="margin-left: 50px">修改</label>-->

                </div>
                <hr>
                <div style="line-height: 20px;vertical-align: middle">
                    <label style="width: 200px;text-align: right;margin-right: 50px">连接密码</label>
                    <input  style="border-bottom: 1px solid black;color: black;" v-model="conn_pass">
                    <label v-if="conn_pass" @click="change_shadowsocks_status"  style="margin-left: 50px;cursor: pointer;border: 2px solid gray;text-align: center;">修改</label>

                </div>
            </div>
            <div id="app-shadowsocks-flow" style="height: 400px;background-color: whitesmoke;box-shadow: 0 0 10px black;margin-top: 30px;color:black">
                <echart :port="port"></echart>
            </div>
        </div>

    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../../../loading/effect_logo.vue'
    import tipbar from '../../../common/tipbar.vue'
    import { analysis_socket_response } from '../../../../api/lib/helper/dataAnalysis'
    import echart from '../../../common/echart.vue'
    /*
    之后用一下接口适配各种平台分辨率
     */
    import UAParser from 'ua-parser-js'
    var uaparser = new UAParser();
    export default {
        data(){
            return{
                ua:'',
                appswitch:false,
                ssnode:[],
                isapp:'shadowsocks',
                conn_pass:'',
                port:''
            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ]),
            check_level(){
                var vm = this
                let app_level = 1;
                this.website.apprules.forEach(function (v,k) {
                    if(v.appname === vm.isapp){
                        app_level = v.applevel
                    }
                })
                return parseInt(this.website.website_user.website_level) >=  parseInt(app_level) + 2

            },

        },
        created(){

            var vm = this
            this.ua = uaparser.getOS()

        },
        mounted(){

            var vm = this
            this.$root.eventHub.$on('socket_response',function (data) {
                var analysis_response = analysis_socket_response(data)
                if(analysis_response.response_type === 'get_shadowsocks_node'){
                    let reponse_ssnode = analysis_response.reponse_data
                    vm.build_ssnode(reponse_ssnode)
                    vm.ssnode = reponse_ssnode
                    console.log('shadowsocks->get_shadowsocks_node')
                }
                if(analysis_response.response_type === 'set_shadowsocks_node'){

                    var msg = analysis_response.response_init_data.msg
                    if(analysis_response.response_init_data.status){

                    }
                    else{

                    }
                    vm.get_ssnode()

                }
                if(analysis_response.response_type === 'get_shadowsocks_status'){
                    console.log(analysis_response)
                    if(analysis_response.response_status === 1){
                        vm.updateWebSite({type:'add',appstatus:'shadowsocks'})
                        vm.appswitch = true
                    }
                    if(analysis_response.response_status === 1 || analysis_response.response_status === 0){
                        vm.port = analysis_response.response_init_data.sysmsg.port
                        vm.conn_pass = analysis_response.response_init_data.sysmsg.conn_pass
                    }
                }
                if(analysis_response.response_type === 'change_shadowsocks_status'){
                    console.log(analysis_response)
                    vm.$root.eventHub.$emit('showtip',{
                        content:analysis_response.response_msg,
                        tipwidth:'800',
                        tiptime:'3000',
                        bgcolor:'black'
                    })
                    vm.get_shadowsocks_status()
                }
            })
            this.get_ssnode();
            this.get_shadowsocks_status();

        },

        beforeDestroy(){
            this.$root.eventHub.$off('socket_response')
        },

        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            get_shadowsocks_status(){
                var params = {
                    to:null,
                    payload_type:'get_shadowsocks_status',
                    payload_data:{

                    },
                    yaf:{
                        module:'index',
                        controller:'shadowsocks',
                        action:'getSSStatus'
                    }
                }
                this.$root.eventHub.$emit('socket_send',params)
            },
            change_shadowsocks_status(){
                if(!this.conn_pass){
                    this.$root.eventHub.$emit('showtip',{
                        content:'连接密码不得为空',
                        tipwidth:'800',
                        tiptime:'3000',
                        bgcolor:'black'
                    })
                    this.appswitch = false
                }
                else{
                    var type = 0
                    if(this.appswitch===true){
                        type = 1
                    }
                    var params = {
                        to:null,
                        payload_type:'change_shadowsocks_status',
                        payload_data:{
                            type:type,
                            conn_pass:this.conn_pass
                        },
                        yaf:{
                            module:'index',
                            controller:'shadowsocks',
                            action:'changeSSStatus'
                        }
                    }
                    this.$root.eventHub.$emit('socket_send',params)
                }
            },
            build_ssnode(reponse_ssnode){
//                var new_ssnode = []
                var vm = this
                reponse_ssnode.forEach(function (v,k) {
                    let ss_node = v
                    if(ss_node['server_status'] === true || ss_node['server_status']==='1'){
                        ss_node['server_status'] = true
                    }
                    else{
                        ss_node['server_status'] = false

                    }
                    if(!vm.check_level){
                        ss_node['readonly'] = true
                    }
//                    new_ssnode.push(ss_node)
                })
//                return new_ssnode
                return;
//                return [
//                    {
//                        server_ip:'127.0.0.1',
//                        location:'日本',
//                        server_status:false
//
//                    },
//                    {
//                        server_ip:'127.0.0.1',
//                        location:'日本',
//                        server_status:true
//
//                    }
//                ]

            },
            get_ssnode(){
                var params = {
                    to:null,
                    payload_type:'get_shadowsocks_node',
                    payload_data:{
                    },
                    yaf:{
                        module:'index',
                        controller:'shadowsocks',
                        action:'getSSNode'
                    }
                }
                this.$root.eventHub.$emit('socket_send',params)
            },
            add_ssnode(){
                let new_node={
                    server_ip:'0.0.0.0',
                    location:'中国香港',
                    encryption_way:'rc4-md5',
                    server_status:0,
                    readonly:false
                }
                this.ssnode.push(new_node)
            },
            set_ssnode(type,e){
                var vm = this
                var target = $(e.currentTarget)
                var target_index = target.attr('data-index')
                var target_id = target.attr('data-id')
                var ss_node=this.ssnode[target_index]
                console.log('ss_node',ss_node)
                ss_node['type'] =  type
                var params = {
                    to:null,
                    payload_type:'set_shadowsocks_node',
                    payload_data:ss_node,
                    yaf:{
                        module:'index',
                        controller:'shadowsocks',
                        action:'changeSSNode'
                    }
                }
                this.$root.eventHub.$emit('socket_send',params)
            }


        },
        components:{
            echart
        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

#app-shadowsocks
    position absolute
    width 600px;
    height 96%;
    overflow scroll
    background-color rgba(245, 245, 245, 0.62)
    left 50%
    margin-left -300px
    box-shadow 0 0 20px black
#app-shadowsocks-node li
    width 100%
    height 25px;
    background-color transparent
    box-shadow 0 0 2px rgba(0, 0, 0, 0.05)
#app-shadowsocks-passandport lable
    width 160px !important
#app-shadowsocks-passandport input:hover
    color black !important
</style>
