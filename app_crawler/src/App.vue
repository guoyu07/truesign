<template>
    <div id="app">
        <window_resize style="display: none"></window_resize>
        <keep-alive>
            <initsocket   :style="{position:'absolute',zIndex: '20',visibility:show_conn,transition: 'all 1s'}" ></initsocket>
        </keep-alive>
        {{ ping }}
        <hr>
        {{info }}
    </div>
</template>

<script>
    import Hello from './components/Hello'
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
            Hello,
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
                    vm.info = {time:datetime,info:data.data.response.msg}
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
