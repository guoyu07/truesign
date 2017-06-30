<template>
    <div style="width: 100%;height: 100%;overflow: hidden">
        <div class="canvas_container" style="">
            <div id="loading_canval_help_div" :style="{height:screenHeight-30+'px',overflow:'auto'}">
                <input style="" v-model="is_line_percent">
                {{ drawParams }}

                <pre  id="loading_canvas_help_pre" :style="{height:screenHeight-30} ">{{ help  }}</pre>
            </div>
            <canvas id="canvas" :width='screenWidth-10' :height='screenHeight-10' style=" background:#000;margin:0 auto;"></canvas>
        </div>
    </div>
</template>



<script>
    //    import ArrayCanvas from '../../../api/array_canvas.js'
    import DrawCanvas from '../../api/drawCanvas.js'
    import {syntaxHighlightJSON} from '../../api/lib/helper/prettyOnHtml'
    import Vue from 'vue'

    export default{

        data() {
            return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                help:'',
                drawCanvas:{},
                drawParams:{
                    xy_line:0,
                    tan:0,
                    dots_count:0,
                }
            }
        },
        props: {

            is_line_percent:{
                type: String,
                default: '10',
            },
        },
        watch:{
            is_line_percent(curVal,oldVal){
                console.log(curVal,oldVal)
                this.start()
            }
        },
        mounted(){
            var vm = this
            $('h1').css('display','none')
//          var arrayCanvas = new ArrayCanvas('canvas')

            this.$root.eventHub.$on('screenWidth2screenHeight', function (data) {
                console.log('canvas-change_w2h')
                var width2height = data.split(",")
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])
                Vue.nextTick( () => {
                    vm.start()
                })



            })
            this.start()




        },
        methods:{
            start(){
                console.log('start')

            },
            initBase(){
                var vm = this
//                vm.drawParams.xy_line = Math.floor(Math.sqrt(Math.pow(vm.screenHeight,2)+Math.pow(vm.screenWidth,2)))
                vm.drawParams.xy_line = vm.screenWidth/2

                vm.drawCanvas = new DrawCanvas('canvas', vm.screenWidth-10, vm.screenHeight-10)
                vm.drawParams.tan = vm.screenHeight/vm.screenWidth

            },
            initDots(){
                var vm = this
                this.drawCanvas.dots = []
                var is_pixel = 4

//                this.drawParams.dots_count = (parseInt(this.drawParams.xy_line)/is_pixel) * (parseInt(this.is_line_percent)/100)
                this.drawParams.dots_count = vm.drawParams.xy_line * (parseInt(this.is_line_percent)/100)
//            this.help = this.drawParams.dots_count

                for(let i = 0; i <this.drawParams.dots_count; i++){
                    vm.drawCanvas.initDot(
                        {
                            g:{down:0,right:0,out:0},
                            init_center:{x:-vm.screenWidth/2 + i*2,
                                y:vm.screenHeight/2-8 - this.drawParams.tan*i*2},
                            z:400,
                            scale_fn_base:1,
                            radius:is_pixel,
                            colors:[
                                {key:0,value:'#53DFD6'}
                            ]
                        })

                }
                this.help = this.drawCanvas.dots[0]
            },
            draw(){
                this.drawCanvas.drawDots()

            },

            render(){
                this.initBase()
                this.initDots()
                this.draw()

                this.drawCanvas.initWidthHeight(this.screenWidth,this.screenHeight)
                this.drawCanvas.initCtrl()
                this.drawCanvas.drawDots()
                this.drawCanvas.move_3D(0,0,0)
                this.drawCanvas.move_line()
            },
            animate(){
                this.render();
                requestAnimationFrame( this.animate );

            }

        }

    }

</script>
<style>
    .canvas_container{
        border: 5px solid rgba(255, 255, 255, 0.11)
    }
    #loading_canval_help_div{
        position: absolute;text-align: center;color: #00B5AD;padding-left: 10%
    }
    #loading_canvas_help_pre{
        box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;border:none;display: block;width: 500px;background-color: transparent;color: #00d4cb;

    }
</style>
