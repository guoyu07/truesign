<template>
    <div id="app">
        <window_resize style="display: none"></window_resize>
        <keep-alive>
            <initsocket   :style="{position:'absolute',zIndex: '20',visibility:show_conn,transition: 'all 1s'}" ></initsocket>
        </keep-alive>
        <div class="hello">
            {{ ping }}
            <hr>
            <div v-if="info.hasOwnProperty('data')">
                {{ info.data.time }}
                <hr>
                <div style="text-align: center">
                    <img :src="getRealImg(info.data.img)">
                    <div
                            style="position: absolute;top:0;
                    background-color:rgba(75,75,75,0.45);
                    width: 100%;color: #ffffff;font-size: 15px;padding:  100px 0;border-radius: 5px">
                        {{info.data.info}}
                    </div>
                </div>
            </div>


            <!--<el-tabs v-model="defaultTab" type="card" @tab-click="handleClick">-->
            <!--<el-tab-pane v-for="item,index in infolist" :label="''+index" :name="''+index" :key="item">-->

            <!--<div style="text-align: center">-->
            <!--<img :src="item.img">-->
            <!--<div-->
            <!--style="position: absolute;top:0;-->
            <!--background-color:rgba(75,75,75,0.79);-->
            <!--width: 100%;color: #ffffff;font-size: 15px;padding:  100px 0;border-radius: 5px">-->
            <!--{{item.msg}}-->
            <!--</div>-->
            <!--</div>-->
            <!--</el-tab-pane>-->
            <!--</el-tabs>-->
        </div>
    </div>
</template>

<script>
    import window_resize from './components/window_resize.vue'
    import initsocket from './components/initSocket.vue'
    var time_tools = require('node-datetime');
    export default {
        data(){
            return{
                show_conn:'hidden',
                ping:{},
                info:{}
            }
        },
        components: {
            initsocket,
            window_resize
        },
        created(){
            var vm = this
            this.listen_key_fun()
            this.$root.eventHub.$on('base_socket_response',function (data) {
                console.log('base_socket_response',data)
                let datetime = time_tools.create().format('Y-m-d H:M:S')
                if(data.type === 'ping'){
                    vm.ping = {time:datetime,info:data }

                }
                else if(data.type === 'debug_for_spider'){
                    let datetime = time_tools.create().format('Y-m-d H:M:S')
                    vm.info = {time:datetime,data:data.data.response.msg}
                }

            })
        },
        methods: {
            listen_key_fun(){
                var vm = this
                $(document).keypress(function (e) {
//                    console.log(e.keyCode)
                    if (e.ctrlKey && e.which === 13 || e.which === 10) {
                        //要执行的操作
                        vm.show_conn = (vm.show_conn === 'visible') ? 'hidden' : 'visible'
                    }
                });
            },
            getRealImg(imgname){
                console.log('imgname',imgname)
                let img = require('../static/imgs/'+imgname)
                return img
            }
        }
    }
</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;

    }
</style>
