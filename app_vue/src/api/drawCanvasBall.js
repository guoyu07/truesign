require("./lib/canvas/utils")
const TWEEN = require('@tweenjs/tween.js');
const utils = window.utils
const requestAnimationFrame = window.requestAnimationFrame
class DrawCanvasBall {
    constructor(el, width, height) {
        this.el = el
        this.canvas = document.getElementById(this.el)
        // this.CANVAS_Width = document.getElementById(this.el).offsetWidth
        // this.CANVAS_Height = document.getElementById(this.el).offsetHeight()
        // this.width = window.innerWidth;
        // this.height = window.innerHeight
        this.width = width
        this.height = height
        this.canvas.width = this.width
        this.canvas.height = this.height
        this.ctx = this.canvas.getContext('2d')

        this.ctrl_mode = {
            mode_x: 0,
            mode_y: 0,
            mode_z: 0
        }
        this.ctx_center = {
            x: 0,
            y: 0
        }
        this.dots = []
        this.dot = {}
        this.angle = 0.01
        this.hsl = 0
        this.stats = new Stats()
        this.stats.setMode(0);
        this.stats.domElement.style.position = 'fixed';
        this.stats.domElement.style.left = this.width / 2 - 100 + 'px';
        this.stats.domElement.style.top = '0px';
        this.stats.domElement.style.width = '300px';
        this.stats.domElement.style.height = '100px';
        document.body.appendChild(this.stats.domElement);

    }

    initWidthHeight(width, height) {
        this.width = width
        this.height = height
    }

    initDot(x, y, vx, vy, useCache) {
        this.dot = {}
        this.dot.borderWidth = 2;
        this.dot.x = x;
        this.dot.y = y;
        this.dot.vx = vx;
        this.dot.vy = vy;
        this.dot.r = 20;
        this.dot.color = [];
        this.dot.cacheCanvas = document.createElement("canvas");
        this.dot.cacheCtx = this.dot.cacheCanvas.getContext("2d");
        this.dot.cacheCanvas.width = 2 * this.dot.r;
        this.dot.cacheCanvas.height = 2 * this.dot.r;
        var num = this.getZ(this.dot.r / this.dot.borderWidth);
        for (var j = 0; j < num; j++) {
            this.dot.color.push("rgba(" + this.getZ(this.getRandom(0, 255)) + "," + this.getZ(this.getRandom(0, 255)) + "," + this.getZ(this.getRandom(0, 255)) + ",1)");
        }
        this.dot.useCache = useCache;
        if (useCache) {
            this.cache();
        }

    }
    getZ(num) {
        var rounded;
        rounded = (0.5 + num) | 0;
        // A double bitwise not.
        rounded = ~~(0.5 + num);
        // Finally, a left bitwise shift.
        rounded = (0.5 + num) << 0;

        return rounded;
    }
    getRandom(a, b) {
        return Math.random() * (b - a) + a;
    }
    cache() {
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

        this.dot.cacheCtx.save()
        var per_style = this.dot.cacheCtx.createRadialGradient(this.dot.r, this.dot.r, this.dot.r * 0.1, this.dot.r, this.dot.r, this.dot.r)
        var colors = [
            {key: 0, value: 'rgba(255,255,255,1)'},
            {key: 0.2, value: 'rgba(0,255,255,1)'},
            {key: 0.3, value: 'rgba(0,0,100,1)'},
            {key: 1, value: 'rgba(0,0,0,0.1)'}
        ]
        for (var item_style of colors) {
            per_style.addColorStop(item_style.key, item_style.value)
        }
        this.dot.cacheCtx.fillStyle = per_style;
        this.dot.cacheCtx.beginPath()
        this.dot.cacheCtx.arc(this.dot.r, this.dot.r,this.dot.r, 0, 2 * Math.PI)
        this.dot.cacheCtx.closePath()
        this.dot.cacheCtx.fill()
        this.dot.cacheCtx.restore();

    }

    move() {
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

    paint(ctx) {
        if (!this.useCache) {
            ctx.save();
            var j = 0;
            ctx.lineWidth = borderWidth;
            for (var i = 1; i < this.r; i += borderWidth) {
                ctx.beginPath();
                ctx.strokeStyle = this.color[j];
                ctx.arc(this.x, this.y, i, 0, 2 * Math.PI);
                ctx.stroke();
                j++;
            }
            ctx.restore();
        } else {
            ctx.drawImage(this.cacheCanvas, this.x - this.r, this.y - this.r);
        }
    }



}
export default DrawCanvasBall
