<template>
    <div style="width: 100%;height: 100%;overflow: hidden">
        <div class="canvas_container" style="">
            <div id="loading_canval_help_div" :style="{height:screenHeight-30+'px',overflow:'auto'}">
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


    export default{

        data() {
            return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                help:''
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

            })


            var drawCanvas = new DrawCanvas('canvas', vm.screenWidth-10, vm.screenHeight-10)
            var tan = vm.screenHeight/vm.screenWidth
            var xy_line = Math.floor(Math.sqrt(Math.pow(vm.screenHeight,2)+Math.pow(vm.screenWidth,2)))
//            drawCanvas.dots = []


            for(let i = 0; i < 1; i++){
                var line = i*0.5
                drawCanvas.initDot(
                    {
                        g:{down:0,right:0,out:0},
                        init_center:
                            {
                                x:0,
                                y:0
                            },
                        z:200,
                        radius:10,


                    })

            }
            vm.help = drawCanvas.dots
            function render() {

                drawCanvas.initWidthHeight(vm.screenWidth,vm.screenHeight)
                drawCanvas.initCtrl()
                drawCanvas.drawDots()
                drawCanvas.move_3D({move_speed:{}})
                drawCanvas.move_line()
            }
            function animate() {
                render();
                requestAnimationFrame( animate );

            }
            animate()
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
