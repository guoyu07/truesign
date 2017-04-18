<template>
  <div>

    <canvas id="canvas" width=100% height=100% style=" background:#000;border: 2px solid rgba(26,27,24,0.7);margin:0 auto;"></canvas>

  </div>
</template>



<script>

//    import ArrayCanvas from '../../../api/array_canvas.js'
    import DrawCanvas from '../../api/drawCanvas.js'



  export default{

      mounted(){
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
