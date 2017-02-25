<template>
  <div>

    <canvas id="canvas" width=100% height=100% style=" background:#000;border: 2px solid rgba(26,27,24,0.7);margin:0 auto;"></canvas>

  </div>
</template>



<script>

//    import ArrayCanvas from '../../../api/array_canvas.js'
    import DrawCanvas from '../../../api/drawCanvas.js'



  export default{

      mounted(){
//          var arrayCanvas = new ArrayCanvas('canvas')
          var drawCanvas = new DrawCanvas('canvas')
//          drawCanvas.draw()
//          drawCanvas.drawStats()
          for(let i = 0; i < 500; i++){
              drawCanvas.initDot()

          }
//          console.log(drawCanvas.dots)
//                        drawCanvas.drawDots()
//              drawCanvas.move()

//          drawCanvas.drawDots()



              (function deawFrame(){
                  drawCanvas.drawDots()
                  drawCanvas.move()
                  window.requestAnimationFrame(deawFrame, drawCanvas.canvas);
              }())

      }
  }
    
</script>
<style>

</style>