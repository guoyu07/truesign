<template>
  <div style="width: 100%;height: 100%;overflow: hidden">
    <div class="canvas_container" style="">
      <div id="loading_canval_help_div" style="">
        <pre  id="loading_canvas_help_pre" style=" ">{{ help  }}</pre>
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
                help:'',
                drawCanvas:{},
                drawParams:{
                    xy_line:0,
                    tan:0,
                    dots_count:0,
                }
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

            this.start();


        },
        methods:{
            start(){
                console.log('start')
                this.initBase();
                this.initDots();
                this.animate()

            },
            initBase(){
                var vm = this
                this.drawCanvas = new DrawCanvas('canvas', vm.screenWidth-10, vm.screenHeight-10)


            },
            initDots(){
                var vm = this
                for(let i = 0; i < 150; i++){
                    this.drawCanvas.initDot(
                        {
                            g:{down:-0.3,right:0,out:0},
                            init_center:{x:Math.random()*(Math.random()>0.5?1:-1) * (this.drawCanvas.width/2) ,
                                y:Math.random()*(Math.random()>0.5?1:-1) * (this.drawCanvas.height/2) },
                            z:-Math.random()*1000
                        })

                }
            },
            draw(){
                this.drawCanvas.drawDots()

            },

            render(){

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
