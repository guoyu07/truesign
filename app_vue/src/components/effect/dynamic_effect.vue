<template>
  <div style="display: block;">

      <div id="animated-number-demo">
          <input v-model.number="number" type="number" step="20">
          <p style="font-size: 100px;">{{ animatedNumber }}</p>
      </div>
      <div id="list-complete-demo" class="demo">
          <button v-on:click="shuffle">Shuffle</button>
          <button v-on:click="add">Add</button>
          <button v-on:click="remove">Remove</button>
          <transition-group name="list-complete" tag="div">
    <span
            v-for="item in items"
            v-bind:key="item"
            class="list-complete-item"
    >
      {{ item }}
    </span>
          </transition-group>
      </div>
      <div id="app">
          <svg width="200" height="200">
              <polygon :points="points"></polygon>
              <circle cx="100" cy="100" r="90"></circle>
              <circle cx="100" cy="1" r="2"></circle>
          </svg>
          <label>Sides: {{ sides }}</label>
          <input
                  type="range"
                  min="3"
                  max="500"
                  v-model.number="sides"
          >
          <label>Minimum Radius: {{ minRadius }}%</label>
          <input
                  type="range"
                  min="0"
                  max="90"
                  v-model.number="minRadius"
          >
          <label>Update Interval: {{ updateInterval }} milliseconds</label>
          <input
                  type="range"
                  min="10"
                  max="2000"
                  v-model.number="updateInterval"
          >
      </div>
  </div>
</template>

<script>
    const  TWEEN = require('tween.js/src/tween.js')
    const TweenLite = require('gsap/TweenLite')
    // load the modern build
//    var _ = require('lodash-node');
    var _ = require('lodash');
    // or the compatibility build
//    var _ = require('lodash-node/compat');
    // or a method category

    var array = require('lodash-node/modern/array');
    // or a method
    var chunk = require('lodash-node/compat/array/chunk');
    export default {
        data () {
            var defaultSides = 10
            var stats = Array.apply(null, { length: defaultSides })
                .map(function () { return 100 })
            return {
                number: 0,
                animatedNumber: 0,
                items: [1,2,3,4,5,6,7,8,9],
                nextNum: 10,
                stats: stats,
                points: generatePoints(stats),
                sides: defaultSides,
                minRadius: 50,
                interval: null,
                updateInterval: 500
            }
        },
        watch: {
            number: function(newValue, oldValue) {

                var vm = this

                new TWEEN.Tween({ tweeningNumber: oldValue })
                    .easing(TWEEN.Easing.Quadratic.Out)
                    .to({ tweeningNumber: newValue }, 2000)
                    .onUpdate(function () {
                        var new_animatedNumber = this.tweeningNumber.toFixed(0)

                        vm.animatedNumber = new_animatedNumber
                    })
                    .start()
                requestAnimationFrame(animate);
                function animate (time) {
                    requestAnimationFrame(animate)
                    TWEEN.update(time)
                }

            },
            sides: function (newSides, oldSides) {
                var sidesDifference = newSides - oldSides
                if (sidesDifference > 0) {
                    for (let i = 1; i <= sidesDifference; i++) {
                        this.stats.push(this.newRandomValue())
                    }
                } else {
                    var absoluteSidesDifference = Math.abs(sidesDifference)
                    for (let i = 1; i <= absoluteSidesDifference; i++) {
                        this.stats.shift()
                    }
                }
            },
            stats: function (newStats) {
                TweenLite.to(
                    this.$data,
                    this.updateInterval / 1000,
                    { points: generatePoints(newStats) }
                )
            },
            updateInterval: function () {
                this.resetInterval()
            }

        },
        mounted(){

        },
        methods:{
            randomIndex: function () {
                return Math.floor(Math.random() * this.items.length)
            },
            add: function () {
                this.items.splice(this.randomIndex(), 0, this.nextNum++)
            },
            remove: function () {
                this.items.splice(this.randomIndex(), 1)
            },
            shuffle: function () {
                this.items = _.shuffle(this.items)
            },
            randomizeStats: function () {
                var vm = this
                this.stats = this.stats.map(function () {
                    return vm.newRandomValue()
                })
            },
            newRandomValue: function () {
                return Math.ceil(this.minRadius + Math.random() * (100 - this.minRadius))
            },
            resetInterval: function () {
                var vm = this
                clearInterval(this.interval)
                this.randomizeStats()
                this.interval = setInterval(function () {
                    vm.randomizeStats()
                }, this.updateInterval)
            }
        }
    }
    function valueToPoint (value, index, total) {
        var x     = 0
        var y     = -value * 0.9
        var angle = Math.PI * 2 / total * index
        var cos   = Math.cos(angle)
        var sin   = Math.sin(angle)
        var tx    = x * cos - y * sin + 100
        var ty    = x * sin + y * cos + 100
        return { x: tx, y: ty }
    }

    function generatePoints (stats) {
        var total = stats.length
        return stats.map(function (stat, index) {
            var point = valueToPoint(stat, index, total)
            console.log(point)
            return point.x + ',' + point.y
        }).join(' ')
    }
</script>

<style>
    .list-complete-item {
        transition: all 1s;
        display: inline-block;
        margin-right: 10px;
    }
    .list-complete-enter, .list-complete-leave-active {
        opacity: 0;
        transform: translateY(30px);
    }
    .list-complete-leave-active {
        position: absolute;
    }


    svg { display: block; }
    polygon { fill: #41B883; }
    circle {
        fill: transparent;
        stroke: #35495E;
    }
    input[type="range"] {
        display: block;
        width: 100%;
        margin-bottom: 15px;
    }
</style>
