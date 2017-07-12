<template>
    <div id="draw_canvas" style="width: 100%;height: 100%;overflow: hidden">

            <canvas id="canvas" :width='screenWidth-10' :height='screenHeight-10' style="margin:0 auto;"></canvas>
        <span class='background'></span>
        <span class='foreground'></span>
    </div>
</template>



<script>
    import DrawCanvas from '../../api/drawCanvas.js'
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
//                this.start()
            }
        },
        mounted(){
            var vm = this
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
            this.animate()

//                var W,H
//                    var canvas = document.getElementById('canvas')
//                    var ctx = canvas.getContext('2d')
//                    var hsl = 0
//                    var angle = 0.01;

//                function size(){
//                    W = vm.screenWidth
//                        H = vm.screenHeight
//                  console.log(W,vm.screenWidth)
//                  console.log(H,vm.screenHeight)
//
//                  canvas.width = W;
//                    canvas.height = H;
//                }

//                function paint(){
//                    angle += 0.03;
//                    hsl <= 360 ? hsl+=0.25 : hsl = 0;
//                    var s = -Math.sin(angle);
//                    var c = Math.cos(angle);
//
//                    ctx.save();
//                    ctx.globalAlpha = 0.5;
//                    ctx.beginPath();
//                    ctx.fillStyle = 'hsla('+hsl+', 100%, 50%, 1)';
//                    ctx.arc(W/2+(s*75),H/2+(c*75),25,0,2*Math.PI);
//                    ctx.fill();
//                    ctx.restore();
//                }


//
//                size();
//                $(window).on('resize', size);


        },
        methods:{
            start(){
                var vm = this
                console.log('start')
                this.initBase();


            },
            initBase(){
                var vm = this
//                vm.drawParams.xy_line = Math.floor(Math.sqrt(Math.pow(vm.screenHeight,2)+Math.pow(vm.screenWidth,2)))

                vm.drawCanvas = new DrawCanvas('canvas', vm.screenWidth, vm.screenHeight)

            },
            initDots(){
                var vm = this
            },
            draw(){
                this.drawCanvas.draw1()
            },

            render(){
              this.drawCanvas.draw_alfa(this.drawCanvas,75,15)
            },
            animate(){
                var vm = this
                this.render();
                requestAnimationFrame( this.animate );
            }

        }

    }

</script>
<style>

    #draw_canvas canvas {
        position: relative;
        z-index: 100;
    }

    #draw_canvas .background, .foreground {
        position: absolute;
        top: 50%;
        left: 50%;
        border-radius: 50%;
    }

    #draw_canvas .background {
        width: 220px;
        height: 220px;
        background: rgba(0, 0, 0, 0.35);
        display: block;
        margin: -110px 0 0 -110px;
        box-shadow: inset 1px 1px 4px rgba(0, 0, 0, 0.5), 1px 1px 4px rgba(255, 255, 255, 0.2);
    }

    #draw_canvas .foreground {
        width: 80px;
        height: 80px;
        margin: -40px 0 0 -40px;
        background: #333336;
        box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
    }



</style>
