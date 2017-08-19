<template>
    <div id="universe_canvas">


        <div class="canvas_container" style="{height:}" v-tap="{method:test}"  v-longtouch="timeOutEvent">
            <div v-if="help" id="loading_canval_help_div" :style="{height:screenHeight-30+'px',overflow:'auto'}">
                <input style="" v-model="is_line_percent">
                {{ drawParams }}

                <pre v-if="help" id="loading_canvas_help_pre" :style="{height:screenHeight-30} ">{{ help }}</pre>
            </div>
            <canvas id="heartbeat-canvas" :width='screenWidth' :height='screenHeight' style="margin:0 auto; background-color: transparent"></canvas>
        </div>

    </div>
</template>
<script>
    //    import ArrayCanvas from '../../../api/array_canvas.js'
    import DrawCanvas from '../../api/drawCanvas.js'
    import {syntaxHighlightJSON} from '../../api/lib/helper/prettyOnHtml'
    import Vue from 'vue'
    import vue_longpress from 'vue-longpress';
    const TWEEN = require('@tweenjs/tween.js');

    export default{

        data() {
            return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                help: '',
                drawCanvas: {},
                drawParams: {
                    xy_line: 0,
                    tan: 0,
                    dots_count: 0,
                },
                timeOutEvent: 0,
                color:[
                    {key: 0, value: 'rgba(142,142,142,80)'},
                ],
                i_point_color:[
                    {key: 0, value: '#32daee'},
                    {key: 0.3, value: 'black'},
                    {key: 0.8, value: 'black'},
                ],
                i_body_color:[
                    {key: 0, value: '#32daee'},
                    {key: 0.3, value: 'black'},
                    {key: 0.8, value: 'black'},
                    {key: 1, value: '#000000'},
                ],
                pageTimeout:[]
            }
        },
        props: {

            is_line_percent: {
                type: String,
                default: '100',
            },
        },
        watch: {
            is_line_percent(curVal, oldVal){
                console.log(curVal, oldVal)
                this.updateLoadingLineDots()
            }
        },
        mounted(){
            var vm = this
            $('h1').css('display', 'none')
//          var arrayCanvas = new ArrayCanvas('canvas')

            this.$root.eventHub.$on('screenWidth2screenHeight', function (data) {
                console.log('canvas-change_w2h')
                var width2height = data.split(",")
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])
                Vue.nextTick(() => {
                    vm.start()
                })


            })
            this.start()


        },
        components:{
            vue_longpress
        },
        methods: {
            doDelete(){
                alert(1)
            },
            test:function(ee){

            },
            start(){
                console.log('start')
                this.initBase();
                this.initDots();
                this.initMove()
                this.animate()

            },
            initBase(){
                var vm = this
//                vm.drawParams.xy_line = Math.floor(Math.sqrt(Math.pow(vm.screenHeight,2)+Math.pow(vm.screenWidth,2)))


                vm.drawCanvas = new DrawCanvas('heartbeat-canvas', vm.screenWidth - 10, vm.screenHeight - 10)
                vm.drawParams.tan = vm.screenHeight / vm.screenWidth

            },
            initDots(){



                var vm = this
                this.drawCanvas.dots = []
                var is_pixel = 4
                this.drawParams.dots_count = parseInt((parseInt(vm.screenWidth) / (is_pixel*2/2)) * (parseInt(this.is_line_percent) / 100))
                console.log(vm.screenWidth,vm.screenHeight,this.drawParams.dots_count)
//                for (let i = 1; i <=this.drawParams.dots_count; i++) {
//                    vm.drawCanvas.initDot(
//                            {
//                                cid:i,
//                                group:'loading_line',
//                                g: {down: 0, right: 0, out: 0},
//                                init_center: {
//                                    x:-vm.screenWidth/2 ,
//                                    y:vm.screenHeight/2
//                                },
//                                z: 0,
//                                scale_fn_base: 1,
//                                radius: is_pixel,
//                                colors: vm.color,
//                                friction:{
//                                    z:0.6
//                                },
//                                move:true,
//                                move_way:{
//                                    type:'loading_line',
//                                    params:{
//                                        count:vm.drawParams.dots_count,
//                                        percent:parseInt(vm.is_line_percent)
//                                    }
//                                },
//                                visible:true
//                            })
//                }
                var count = 400
                for (let i = 1; i <=count; i++) {
                    vm.drawCanvas.initDot(
                        {
                            cid:i,
                            group:'loading_line',
                            g: {down: 0, right: 0, out: 0},
                            init_center: {
                                x:-vm.screenWidth/2 ,
                                y:vm.screenHeight/4
                            },
                            z: 0,
                            scale_fn_base: 1,
                            radius: is_pixel/1.2,
                            colors: vm.color,
                            friction:{
                                z:0.6
                            },
                            move:true,
                            move_way:{
                                type:'beat_line',
                                params:{
                                    count:count,
                                    percent:parseInt(vm.is_line_percent)
                                }
                            },

                            visible:false,
                          cache:true
                        })
                }

//
//                var cross_count = 60
//                var angle = 360/cross_count
//                var cross_radius = 80
//                for(let i = 1; i <= cross_count; i++){
//                    vm.drawCanvas.initDot(
//                        {
//                            cid:i,
//                            group:'loading_line',
//                            g:{down:0,right:0,out:0},
//                            init_center:{
//                                x:(parseInt(Math.random()*10)%2===1)?-vm.screenWidth/2:vm.screenWidth/2 ,
//                                y:(parseInt(Math.random()*10)%2===1)?-vm.screenHeight/2:vm.screenHeight/2
//                            },
////                                x: 100+cross_radius*Math.cos((2*Math.PI/360 * (angle*i)) ),
////                                y: 1000+-cross_radius*Math.sin(2*Math.PI/360 * (angle*i)) },
//                            z:0,
//                            scale_fn_base:1,
//                            radius:12,
//                            colors:vm.color,
//                            move:true,
//                            move_way:{
//                                type:'clockwise',
//                                params:{
//                                    cross_count:cross_count,
//                                    angle : angle,
//                                    cross_radius:cross_radius,
//                                    base_angle: angle*i
//                                }
//                            }
//                        })
//                }
//                var i_point_count =2
//                var i_point_angle = 360/i_point_count
//                var i_point_radius = 80
//                for(let i = 1; i <= i_point_count; i++){
//                    vm.drawCanvas.initDot(
//                        {
//                            cid:i,
//                            group:'loading_line',
//                            g:{down:0,right:0,out:0},
//                            init_center:{
//                                x:(parseInt(Math.random()*10)%2===1)?-vm.screenWidth/2:vm.screenWidth/2 ,
//                                y:(parseInt(Math.random()*10)%2===1)?-vm.screenHeight/2:vm.screenHeight/2
//                            },
////                                x: 100+cross_radius*Math.cos((2*Math.PI/360 * (angle*i)) ),
////                                y: 1000+-cross_radius*Math.sin(2*Math.PI/360 * (angle*i)) },
//                            z:10,
//                            scale_fn_base:1,
//                            radius:12,
//                            colors:vm.i_point_color,
//                            friction:{
//                                z:0.88
//                            },
//                            move:true,
//                            move_way:{
//                                type:'i_point',
//                                params:{
//                                    cross_count:i_point_count,
//                                    angle : i_point_angle,
//                                    cross_radius:i_point_radius,
//                                    base_angle: i_point_angle*i
//                                }
//                            }
//                        })
//                }
//
//
//                var i_body_count = 40
//                var i_body_length = 80
//                for(let i = 1; i <= i_body_count; i++){
//                    vm.drawCanvas.initDot(
//                        {
//                            cid:i,
//                            group:'loading_line',
//                            g:{down:0,right:0,out:0},
//                            init_center:{
//                                x:(parseInt(Math.random()*10)%2===1)?-vm.screenWidth/2:vm.screenWidth/2 ,
//                                y:(parseInt(Math.random()*10)%2===1)?-vm.screenHeight/2:vm.screenHeight/2
//                            },
//                            z:10,
//                            scale_fn_base:1,
//                            radius:5,
//                            colors:vm.i_body_color,
//                            friction:{
//                                z:0.88
//                            },
//                            move:true,
//                            move_way:{
//                                type:'i_body',
//                                params:{
//                                    cross_count:i_body_count,
//                                    angle : i_point_angle,
//                                    cross_radius:60,
//                                    base_angle: 60*i
//                                }
//                            }
//                        })
//                }
//
//                var LastLoadingLineDots =  this.drawCanvas.dots.filter((dot) => {
//                    return dot.move_way.type === 'loading_line'
//                })





            },
            updateLoadingLineDots(){
                var vm = this
                var tan = vm.screenHeight / vm.screenWidth

                var is_pixel = 8
                var LastLoadingLineDots =  this.drawCanvas.dots.filter((dot) => {
                    return dot.move_way.type === 'loading_line'
                })
                var hasdots = LastLoadingLineDots.length

                var tmp_dots = LastLoadingLineDots.slice()
                tmp_dots.sort((a,b) => {
                    return a.cid - b.cid
                } )
                var lastdot = tmp_dots[LastLoadingLineDots.length-1]
                var update_dots_count = parseInt((parseInt(this.drawParams.xy_line) / (is_pixel/4)) * (parseInt(this.is_line_percent) / 100))-hasdots

                for (let i = 1; i <=update_dots_count; i++) {
                    vm.pageTimeout[lastdot.cid+i] = setTimeout(function () {
                        vm.drawCanvas.initDot(
                            {
                                cid:lastdot.cid+i,
                                group:'loading_line',
                                g: {down: 0, right: 0, out: 0},
                                init_center: {
                                    x: -vm.screenWidth / 2 + (lastdot.cid+i) * 4,
                                    y: vm.screenHeight / 2 - tan * (lastdot.cid+i) * 4
                                },
                                z: 0,
                                scale_fn_base: 1,
                                radius: is_pixel,
                                colors: vm.color,
                                move:true,
                                move_way:{
                                    type:'loading_line',
                                    params:{
                                        count:update_dots_count+hasdots,
                                        percent:parseInt(vm.is_line_percent)
                                    }
                                },
                                visible:true
                            })
                    },parseInt(i+'00')/8)


                }

                setTimeout(function () {
                    if(parseInt(vm.is_line_percent)>=100){
                        vm.pageTimeout.forEach(function (k,v) {
                            clearTimeout(vm.pageTimeout[v])
                        })
                        vm.start()
                    }
                },200)


            },
            initMove(){
                this.drawCanvas.move_3D(0, 0, 0)

            },
            draw(){
                this.drawCanvas.drawDots()
            },

            render(){
                this.drawCanvas.initWidthHeight(this.screenWidth, this.screenHeight)
                this.drawCanvas.initCtrl()
                this.drawCanvas.drawDots()
//                this.drawCanvas.move_line()
            },
            animate(){
                var vm = this
                this.render();
                requestAnimationFrame(this.animate);


//                setInterval(function () {
//                    vm.animate()
//                },50)
            }

        }

    }

</script>
<style>
    .canvas_container {

    }

    #loading_canval_help_div {
        position: absolute;
        text-align: center;
        color: #00B5AD;
        padding-left: 10%
    }

    #loading_canvas_help_pre {
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        border: none;
        display: block;
        width: 500px;
        background-color: transparent;
        color: #00d4cb;

    }
</style>
