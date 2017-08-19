<template>
  <div id="loading_canvas" style="width: 100%;height: 100%;overflow: hidden">
    <div style="position: absolute;left: 200px">
      {{ cache }} {{ num }}
    </div>
    <canvas id="ball_canvas_init" :width='screenWidth' :height='screenHeight'
            style="margin:0 auto; background-color: transparent"></canvas>
  </div>
</template>
<script>
  //    import ArrayCanvas from '../../../api/array_canvas.js'
  import DrawCanvasBall from '../../api/drawCanvasBall.js'
  import Vue from 'vue'

  const TWEEN = require('@tweenjs/tween.js');


  export default {

    data() {
      return {
        screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
        screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
        help: '',
        drawCanvasBall: {},
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
        pageTimeout: [],
        cache: false,
        num: 1,
      }
    },
    props: {

      is_line_percent: {
        type: String,
        default: '100',
      },
    },
    watch: {
      is_line_percent(curVal, oldVal) {
        console.log(curVal, oldVal)
      }
    },
    mounted() {
      var vm = this
      console.log('$route.query', this.$route.query)
      if (this.$route.query.cache === 'true') {
        this.cache = true
      }
      else {
        this.cache = false
      }
      this.num = parseInt(this.$route.query.num) > 1 ? parseInt(this.$route.query.num) : 1

      this.$root.eventHub.$on('screenWidth2screenHeight', function (data) {
        console.log('canvas-change_w2h')
        var width2height = data.split(",")
        vm.screenWidth = parseInt(width2height[0])
        vm.screenHeight = parseInt(width2height[1])
        Vue.nextTick(() => {
//          vm.start()
        })
      })
      this.drawCanvasBall = new DrawCanvasBall('ball_canvas_init', vm.screenWidth, vm.screenHeight)
      this.start()
    },
    components: {},
    methods: {
      start(){
        this.init()
        this.animate()
      },
      init() {
        var vm = this
        for (var i = 0; i < vm.num; i++) {

          vm.drawCanvasBall.initDot(vm.getRandom(0, vm.screenWidth), vm.getRandom(0, vm.screenHeight), vm.getRandom(-10, 10), vm.getRandom(-10, 10), vm.cache)
        }
      },

      doUpdate(){
        this.drawCanvasBall.doMove()
      },
      doDraw(){
        this.drawCanvasBall.dotDraw()
      },
      render(){
        this.doDraw()
        this.doUpdate()
      },
      animate(){
        var vm = this
        this.render();
        requestAnimationFrame(this.animate);
      },



//      loop: function () {
//        var _this = this;
//        this.update();
//        stats.update();
//        RAF(function () {
//          _this.loop();
//        })
//      },
//
//      start: function () {
//        this.init();
//        this.loop();
//      },
      getRandom(a, b) {
        return Math.random() * (b - a) + a;
      },

      getZ(num) {
        var rounded;
        rounded = (0.5 + num) | 0;
        // A double bitwise not.
        rounded = ~~(0.5 + num);
        // Finally, a left bitwise shift.
        rounded = (0.5 + num) << 0;

        return rounded;
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
