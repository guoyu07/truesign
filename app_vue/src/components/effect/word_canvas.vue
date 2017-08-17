<template>
    <div id="loading_canvas" style="width: 100%;height: 100%;overflow: hidden">
        <canvas id="word-canvas" :width='screenWidth' :height='screenHeight' style="margin:0 auto; background-color: transparent"></canvas>
    </div>
</template>
<script>
    //    import ArrayCanvas from '../../../api/array_canvas.js'
    import DrawCanvas from '../../api/drawCanvas.js'
    import {syntaxHighlightJSON} from '../../api/lib/helper/prettyOnHtml'
    import Vue from 'vue'
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
            }
        },
        mounted(){
            var vm = this

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

                vm.drawCanvas = new DrawCanvas('word-canvas', vm.screenWidth - 10, vm.screenHeight - 10)
                vm.drawParams.tan = vm.screenHeight / vm.screenWidth

            },
            initDots(){
                var vm = this
                this.drawCanvas.dots = []
                var radius = 8
                for(let i=1; i<=3000; i++){
                    vm.drawCanvas.initDot(
                        {
                            cid:1,
                            group:'word',
                            g: {down: 0, right: 0, out: 0},
                            init_center: {
                                x:vm.screenWidth/2*Math.random()*((Math.random()>0.5)?1:-1),
                                y:vm.screenHeight/2*((Math.random()>0.5)?1:-1)*Math.random()
                            },
                            z: 0,
                            scale_fn_base: 1,
                            radius: radius,
                            colors: vm.color,
                            friction:{
                                x:0.92,
                                y:0.92,
                                z:0.92,
                            },
                            move:true,
                            move_way:{
                                type:'word',
                                params:{
                                    count:vm.drawParams.dots_count,
                                    percent:parseInt(vm.is_line_percent)
                                }
                            },
                            visible:true
                        })
                }





            },

            initMove(){

            },
            draw(){
                this.drawCanvas.drawDots()
            },

            render(){
                this.drawCanvas.initWidthHeight(this.screenWidth, this.screenHeight)
                this.drawCanvas.initCtrl()
                this.drawCanvas.drawDots()
                this.drawCanvas.move_3D(0, 0, 0)

//                this.drawCanvas.move_line()
            },
            animate(){
                var vm = this
                this.render();
                requestAnimationFrame(this.animate);

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
