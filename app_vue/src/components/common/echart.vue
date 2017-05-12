<template>
  <div id='echart' class="top_router_view" style="width: 100%;height: 100%;">
  </div>
</template>

<script>
    var echarts = require('echarts');
    import { analysis_socket_response } from '../../api/lib/helper/dataAnalysis'

    export default{
        data (){
            return {
                myChart:'',
                option:{},
                flow_data:[],
                Interval:'',
            }
        },
        props:{
            echart_type:{
                default: 'dynamic_data',
                required: false,
            },
            port:{
                default: '',
                required: false,
            }
        },
        mounted (){
            var vm = this
            this.$root.eventHub.$on('socket_response',function (data) {
                var analysis_response = analysis_socket_response(data)
                if(analysis_response.response_type === 'ping'){
                }
                if(analysis_response.response_type === 'get_flow_data'){
                    console.log(analysis_response)
                    var flow_data = analysis_response.base_response.response.response_data.data
                    vm.flow_data = flow_data
                    vm.myChart.hideLoading();
                    var option = {
                        title: {
                            text: '流量统计'
                        },
                        tooltip: {},
                        legend: {
                            data:['流量(m)']
                        },
                        xAxis: {
                            data:flow_data.data_title
                        },
                        yAxis: {},
                        series: [{
                            name: '流量(m)',
                            type:'line',
                            smooth:true,
                            symbol: 'none',
                            stack: 'a',
                            areaStyle: {
                                normal: {}
                            },
                            data:flow_data.data_value

                        }]
                    };

                    vm.myChart.setOption(option, true);
                }

            })
            if(this.echart_type === 'dynamic_data'){
                this.init_dynamic_data()
            }
            this.Interval = setInterval(function () {
                vm.get_flow_data()

            },10000)
        },
        beforeDestroy(){
            this.$root.eventHub.$off('socket_response')
            clearInterval(this.Interval)
        },
        methods:{
            init_dynamic_data(){
                var vm = this
                var dom = document.getElementById("echart");
                var myChart = echarts.init(dom);
                this.myChart = myChart
                var data = {
                    title:[],
                    value:[]
                }
                var option = {
                    title: {
                        text: '流量统计'
                    },
                    tooltip: {},
                    legend: {
                        data:['流量(m)']
                    },
                    xAxis: {
                        data:vm.flow_data.data_title
                    },
                    yAxis: {},
                    series: [{
                        name: '流量(m)',
                        type:'line',
                        smooth:true,
                        symbol: 'none',
                        stack: 'a',
                        areaStyle: {
                            normal: {}
                        },
                        data:vm.flow_data.data_value

                    }]
                };

                myChart.setOption(option, true);
                myChart.showLoading();


                var value = 0

//                setInterval(function () {
//
//                    value += 1
//                    var title = value
//
//                    data.title.push(title)
//                    data.value.push(value)
//                    if(data.title.length>0){
//                        myChart.hideLoading();
//
//                        if(data.length > 10){
//                            data.title.shift()
//                            data.value.shift()
//                        }
//                        myChart.setOption(option, true);
//                    }
//                },1000)

            },
            get_flow_data(){
                var params = {
                    to:null,
                    payload_type:'get_flow_data',
                    payload_data:{
                        port:this.port
                    },
                    yaf:{
                        module:'index',
                        controller:'shadowsocks',
                        action:'getFlow'
                    }
                }
                this.$root.eventHub.$emit('socket_send',params)
            }
        }

    }
</script>

<style>

</style>
