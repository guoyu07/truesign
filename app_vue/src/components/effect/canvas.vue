<template>
  <div>

    <canvas id="canvas" :width='screenWidth' :height='screenHeight' style=" background:#000;border: 2px solid rgba(26,27,24,0.7);margin:0 auto;"></canvas>

  </div>
</template>



<script>

//    import ArrayCanvas from '../../../api/array_canvas.js'
    import DrawCanvas from '../../api/drawCanvas.js'



  export default{

      data() {
          return {
              screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
              screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
          }
      },

      mounted(){
          var vm = this
          $('h1').css('display','none')
//          var arrayCanvas = new ArrayCanvas('canvas')
          var drawCanvas = new DrawCanvas('canvas')
//          drawCanvas.draw()
//          drawCanvas.drawStats()
          for(let i = 0; i < 100; i++){
              drawCanvas.initDot(
                  {
                      g:{down:-0.3,right:0,out:0},
                      init_center:{x:Math.random()*(Math.random()>0.5?1:-1) * (drawCanvas.width/2) ,
                          y:Math.random()*(Math.random()>0.5?1:-1) * (drawCanvas.height/2) },
                      z:-Math.random()*1000
                  })

          }
          this.$root.eventHub.$on('screenWidth2screenHeight', function (data) {
              console.log('canvas-change_w2h')
              var width2height = data.split(",")
              vm.screenWidth = parseInt(width2height[0])
              vm.screenHeight = parseInt(width2height[1])

          })


          drawCanvas.initCtrl()


//          (function deawFrame(){
//              drawCanvas.drawDots()
//              drawCanvas.move()
//                  window.requestAnimationFrame(deawFrame, drawCanvas.canvas);
//          }())
          setInterval(function () {

              drawCanvas.drawDots()
              drawCanvas.move()
          },20)

      }
  }

</script>
<style>

</style>
