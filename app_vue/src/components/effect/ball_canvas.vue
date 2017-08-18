<template>
    <div id="loading_canvas" style="width: 100%;height: 100%;overflow: hidden">
        <div style="position: absolute;left: 200px">
            {{ cache }} {{ num }}
        </div>
        <canvas id="ball-cas" :width='screenWidth' :height='screenHeight'
                style="margin:0 auto; background-color: transparent"></canvas>
    </div>
</template>
<script>


    export default{

        data() {
            return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                help: '',
                drawCanvas: {},
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
                cache:false,
                num:1,
            }
        },
        props: {

            is_line_percent: {
                type: String,
                default: '100',
            },
        },
        watch: {
            is_line_percent(curVal, oldVal){
                console.log(curVal, oldVal)
            }
        },
        mounted(){
            var vm = this
            console.log('$route.query',this.$route.query)
            if(this.$route.query.cache==='true'){
                this.cache = true
            }
            else{
                this.cache = false
            }
            this.num =  parseInt(this.$route.query.num)>1?parseInt(this.$route.query.num):1
            var stats = new Stats();
            stats.setMode(0);
            stats.domElement.style.position = 'absolute';
            stats.domElement.style.left = 800 + 'px';
            stats.domElement.style.top = '0px';
            document.body.appendChild(stats.domElement);

            var testBox = function () {
                var canvas = document.getElementById("ball-cas"),
                    ctx = canvas.getContext('2d'),
                    borderWidth = 2,
                    Balls = [];
                var ball = function (x, y, vx, vy, useCache) {
                    this.x = x;
                    this.y = y;
                    this.vx = vx;
                    this.vy = vy;
                    this.r = 20;
                    this.color = [];
                    this.cacheCanvas = document.createElement("canvas");
                    this.cacheCtx = this.cacheCanvas.getContext("2d");
                    this.cacheCanvas.width = 2 * this.r;
                    this.cacheCanvas.height = 2 * this.r;
                    var num = getZ(this.r / borderWidth);
                    for (var j = 0; j < num; j++) {
                        this.color.push("rgba(" + getZ(getRandom(0, 255)) + "," + getZ(getRandom(0, 255)) + "," + getZ(getRandom(0, 255)) + ",1)");
                    }
                    this.useCache = useCache;
                    if (useCache) {
                        this.cache();
                    }
                }

                function getZ(num) {
                    var rounded;
                    rounded = (0.5 + num) | 0;
                    // A double bitwise not.
                    rounded = ~~(0.5 + num);
                    // Finally, a left bitwise shift.
                    rounded = (0.5 + num) << 0;

                    return rounded;
                }

                ball.prototype = {
                    cache: function () {
//                        this.cacheCtx.save();
//                        var j=0;
//                        this.cacheCtx.lineWidth = borderWidth;
//                        for(var i=1; i<this.r; i+=borderWidth){
//                            this.cacheCtx.beginPath();
//                            this.cacheCtx.strokeStyle = this.color[j];
//                            this.cacheCtx.arc(this.r , this.r , i , 0 , 2*Math.PI);
//                            this.cacheCtx.stroke();
//                            j++;
//                        }
//                        this.cacheCtx.restore();

                        this.cacheCtx.save()
                        var per_style = this.cacheCtx.createRadialGradient(this.r, this.r, this.r * 0.1, this.r, this.r, this.r)
                        var colors = [
                            {key: 0, value: 'rgba(255,255,255,1)'},
                            {key: 0.2, value: 'rgba(0,255,255,1)'},
                            {key: 0.3, value: 'rgba(0,0,100,1)'},
                            {key: 1, value: 'rgba(0,0,0,0.1)'}
                        ]
                        for (var item_style of colors) {
                            per_style.addColorStop(item_style.key, item_style.value)
                        }
                        this.cacheCtx.fillStyle = per_style;
                        this.cacheCtx.beginPath()
                        this.cacheCtx.arc(this.r, this.r, this.r, 0, 2 * Math.PI)
                        this.cacheCtx.closePath()
                        this.cacheCtx.fill()
                        this.cacheCtx.restore();

                    },

                    move: function () {
                        this.x += this.vx;
                        this.y += this.vy;
                        if (this.x > (canvas.width - this.r) || this.x < this.r) {
                            this.x = this.x < this.r ? this.r : (canvas.width - this.r);
                            this.vx = -this.vx;
                        }
                        if (this.y > (canvas.height - this.r) || this.y < this.r) {
                            this.y = this.y < this.r ? this.r : (canvas.height - this.r);
                            this.vy = -this.vy;
                        }

//                        this.paint(ctx);
                    }
                }

                var Game = {
                    init: function () {
                        for (var i = 0; i < vm.num; i++) {
                            var b = new ball(getRandom(0, canvas.width), getRandom(0, canvas.height), getRandom(-10, 10), getRandom(-10, 10), vm.cache)
                            Balls.push(b);
                        }
                    },

                    update: function () {
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        for (var i = 0; i < Balls.length; i++) {
                            Balls[i].move();
                        }
                        this.draw()
                    },
                    draw: function () {
                        var borderWidth = 2

                        for (var bot = 0; bot < Balls.length; bot++) {
                            if (!Balls[bot].useCache) {
//                                ctx.save();
//                                var j = 0;
//                                ctx.lineWidth = borderWidth;
//                                for (var i = 1; i < Balls[bot].r; i += borderWidth) {
//                                    ctx.beginPath();
//                                    ctx.strokeStyle = Balls[bot].color[j];
//                                    ctx.arc(Balls[bot].x, Balls[bot].y, i, 0, 2 * Math.PI);
//                                    ctx.stroke();
//                                    j++;
//                                }
//                                ctx.restore();
                                ctx.save()
                                var per_style = ctx.createRadialGradient(Balls[bot].x, Balls[bot].y, Balls[bot].r * 0.1, Balls[bot].x, Balls[bot].y, Balls[bot].r)
                                var colors = [
                                    {key: 0, value: 'rgba(255,255,255,1)'},
                                    {key: 0.2, value: 'rgba(0,255,255,1)'},
                                    {key: 0.3, value: 'rgba(0,0,100,1)'},
                                    {key: 1, value: 'rgba(0,0,0,0.1)'}
                                ]
                                for (var item_style of colors) {
                                    per_style.addColorStop(item_style.key, item_style.value)
                                }
                                ctx.fillStyle = per_style;
                                ctx.beginPath()
                                ctx.arc(Balls[bot].x, Balls[bot].y, Balls[bot].r, 0, 2 * Math.PI)
                                ctx.closePath()
                                ctx.fill()
                                ctx.restore();
                            } else {
                                ctx.drawImage(Balls[bot].cacheCanvas, Balls[bot].x - Balls[bot].r, Balls[bot].y - Balls[bot].r);
                            }
                        }
                    },
                    loop: function () {
                        var _this = this;
                        this.update();
                        stats.update();
                        RAF(function () {
                            _this.loop();
                        })
                    },

                    start: function () {
                        this.init();
                        this.loop();
                    }
                }

                window.RAF = (function () {
                    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (callback) {
                            window.setTimeout(callback, 1000 / 60);
                        };
                })();

                return Game;
            }();

            function getRandom(a, b) {
                return Math.random() * (b - a) + a;
            }

            window.onload = function () {
                testBox.start();
            }
        },
        components: {},
        methods: {}

    }

</script>
<style>
    #cas {
        display: block;
        margin: auto;
        border: 1px solid;
    }
</style>
