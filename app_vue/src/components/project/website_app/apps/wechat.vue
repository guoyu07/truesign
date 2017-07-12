<template>
    <div class="top_router_view" style="background-color: transparent;text-align: center;color: white!important;" >
        <div>{{ website.socket_id }}</div>
        <input type="text" v-model="socket_id" style="border:2px solid white;width: 40px ">
        <input type="text" v-model="msg" style="width: 300px;border:2px solid white; ">
        <input type="button" @click="send_msg" value="发送" style="border:2px solid white;">
        <span style="display: block">MSG</span>
        <hr>
        <div style="width: 50%;height: 100%;text-align: center;margin-left: 25%;">
            <ul style="text-align: center">
                <li v-for="(item,index) in get_msg">{{ item }}</li>
            </ul>
        </div>
    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../../../loading/effect_logo.vue'
    import tipbar from '../../../common/tipbar.vue'
    import { analysis_socket_response } from '../../../../api/lib/helper/dataAnalysis'

    export default {
        data(){
            return{
                msg:'',
                socket_id:0+'',
                get_msg:[]
            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ])
        },
        mounted(){
            this.socket_id = this.$route.query.fd
            this.msg = JSON.stringify(this.$route.query)
            this.send_msg()
            var vm =this
            this.$root.eventHub.$on('socket_response',function (data) {
//                console.log('socket_reponse',data)
                var socket_reponse = analysis_socket_response(data)
                if (socket_reponse.response_type === 'c2c_msg') {
                    vm.get_msg.push(socket_reponse.base_response.response)
                }
            })

        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            send_msg(){
                var params = {
                    to:this.socket_id,
                    payload_type:'c2c_msg',
                    msg:this.msg
                }
                //console.log('appCard_changeimg->param:',params)
                this.$root.eventHub.$emit('socket_send',params)
            }


        },
        components:{

        }
    }
</script>
<style>

</style>
