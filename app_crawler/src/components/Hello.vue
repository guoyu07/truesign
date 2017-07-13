<template>
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
</template>

<script>
    var time_tools = require('node-datetime');

    export default {
        data () {
            return {
                ping: {},
                info: {},
                currectinfo: {},
                defaultTab: '0',
                activeName2: 'first'
            }
        },
        created(){
            var vm = this
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
        mounted(){

        },
        methods: {
            handleClick(tab, event) {
                this.currectinfo = this.infolist[parseInt(tab.name)]
            },
            getRealImg(imgname){
                console.log('imgname',imgname)
                let img = require('../../static/imgs/'+imgname)
                return img
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    h1, h2 {
        font-weight: normal;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        margin: 0 10px;
    }

    a {
        color: #42b983;
    }
</style>
