<template>
    <div id="loading_canvas" style="width: 100%;height: 100%;overflow: hidden">
         <!--<input v-model="text" style="color: black !important; width: 30%;height: 30px;position: absolute;bottom: 100px;display: block;left:50%;transform: translateX(-50%);text-align: center;padding: 5px 10px;box-shadow: 0 0 15px black">-->

        <div style="position: absolute;left: 200px">
            {{ cache }} {{ num }} {{ ctxFont }}
            <hr>


        </div>
        <canvas id="word-canvas" :width='screenWidth' :height='screenHeight'
                style="margin:0 auto; background-color: transparent"></canvas>
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
                ctxImageData: {},
                ctxFont:{},
                loopSeries:[],
                drawParams: {
                    xy_line: 0,
                    tan: 0,
                    dots_count: 0,
                },
                timeOutEvent: 0,
                color: [
                    {key: 0, value: 'red'},
//                    {key: 0.3, value: 'black'},
//                    {key: 0.8, value: 'black'},

                ],
                textcolor: [
                    {key: 0, value: 'rgba(0,0,0,0.8)'},
                ],
                pageTimeout: [],
                cache: false,
                num: 1,
                text:'hello world',
            }
        },
        props: {

            is_line_percent: {
                type: String,
                default: '100',
            },
        },
        watch: {
            text(curVal, oldVal){
                this.initDots()
                this.initMove()
            }
        },
        mounted(){
            var vm = this
            console.log('$route.query', this.$route.query)
            if (this.$route.query.cache === 'false') {
                this.cache = false
            }
            else {
                this.cache = true
            }
            this.text = this.$route.query.text ? this.$route.query.text : '20000'

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
            var iscount = setInterval(function () {
//                console.log(vm.text)
                if(parseInt(vm.text) <= 0){
                    clearInterval(iscount)
                    return
                }
                vm.text = parseInt(vm.text) -1 + ''
            },20)

        },
        components: {},
        methods: {

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

                this.drawCanvas = new DrawCanvas('word-canvas', vm.screenWidth, vm.screenHeight)



            },
            initDots(){
                var vm = this
                this.drawCanvas.clearCtx()
                this.drawCanvas.dots = []
                this.ctxFont = {
                    fontSize:180,
                    radius:3,
                    step:6,
                    rate:4,
                }
                this.ctxImageData = this.drawCanvas.getimgData(this.text,this.ctxFont.fontSize)
//                console.log('this.ctxImageData', this.ctxImageData)

                this.fixDotsNum(this.ctxFont.fontSize,this.ctxFont.radius,this.ctxFont.step,this.ctxFont.rate)
                this.ctxImageData = this.drawCanvas.getimgData(this.text,this.ctxFont.fontSize)
//                console.log('this.loopSeries',this.loopSeries)
                for (var cid = 1; cid <= this.loopSeries.length; cid++) {
                    let start_x = vm.loopSeries[cid-1].x
                    let start_y = vm.loopSeries[cid-1].y
                    let dot = vm.drawCanvas.initDot(
                        {
                            cid:cid,
                            group:'countdown',
                            g: {down: 0, right: 0, out: 0},
                            init_center: {
                                x: start_x-vm.ctxImageData.width/2-3,
                                y: start_y-vm.ctxImageData.height/2-3,
                            },
                            z: 0,
                            scale_fn_base: 1,
                            radius:vm.ctxFont.radius,
                            colors: vm.textcolor,
                            friction:{
                                x:0.98,
                                y:0.98,
                                z:0.98,
                            },
                            constant_speed:{
                                x:((Math.random()>0.5)?1:-1)*Math.random()*10,
                                y:((Math.random()>0.5)?1:-1)*Math.random()*10,
                                z:0
                            },
                            chaos:{
                                x:Math.random(),
                                y:Math.random(),
                            },
                            move:true,
                            move_way:{
                                type:'countdown',
                                params:{
                                    num:parseInt(vm.text)
                                }
                            },
                            edge_touch_test:true,
                            cache:vm.cache,
                            visible:true
                        }
                    )
//                    setTimeout(function () {
//                        vm.drawCanvas.drawDots(dot)
//
//                    },100)
                }

            },
            fixDotsNum(fontSize=50,radius=2,step = 3,rate= 4){
                var vm = this
                this.num = 0

                this.loopSeries = []
                for (var x = 0; x < this.ctxImageData.width; x += step) {
                    for (var y = 0; y < this.ctxImageData.height; y += step) {

                        var i = (y * this.ctxImageData.width + x) * rate

                        if (this.ctxImageData.data[i] >= 128) {
                            vm.loopSeries.push({x:x,y:y,i:i})
                            this.num += 1
                        }
                    }

                }
                if(this.num > 3000){
                    if(radius-1>0){
                        this.fixDotsNum(fontSize,radius-1,step+1,rate)

                    }
                }
                else{
                    this.ctxFont.fontSize = fontSize
                    this.ctxFont.radius = radius
                    this.ctxFont.step = step
                    this.ctxFont.rate = rate
                }
            },
            initMove(){
                this.drawCanvas.move_3D(0, 0, 0)

            },
            render(){
                this.drawCanvas.initWidthHeight(this.screenWidth, this.screenHeight)
                this.drawCanvas.initCtrl()
                this.drawCanvas.drawDots()
//                this.drawCanvas.move_3D(0, 0, 0)
//                this.drawCanvas.move_3D()
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
