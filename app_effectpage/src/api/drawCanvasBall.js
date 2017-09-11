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
    this.dots.push(this.dot)

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
    this.dot.cacheCtx.arc(this.dot.r, this.dot.r, this.dot.r, 0, 2 * Math.PI)
    this.dot.cacheCtx.closePath()
    this.dot.cacheCtx.fill()
    this.dot.cacheCtx.restore();

  }

  doMove() {
    for (var i = 0; i < this.dots.length; i++) {
      this.dots[i].x += this.dots[i].vx;
      this.dots[i].y += this.dots[i].vy;
      if (this.dots[i].x > (this.canvas.width - this.dots[i].r) || this.dots[i].x < this.dots[i].r) {
        this.dots[i].x = this.dots[i].x < this.dots[i].r ? this.dots[i].r : (this.canvas.width - this.dots[i].r);
        this.dots[i].vx = -this.dots[i].vx;
      }
      if (this.dots[i].y > (this.canvas.height - this.dots[i].r) || this.dots[i].y < this.dots[i].r) {
        this.dots[i].y = this.dots[i].y < this.dots[i].r ? this.dots[i].r : (this.canvas.height - this.dots[i].r);
        this.dots[i].vy = -this.dots[i].vy;
      }
    }


//                        this.dots[i].paint(ctx);
  }

  dotDraw() {
    this.stats.update()
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    var cls = this

    for (var i = 0; i < this.dots.length; i++) {
      if (!cls.dots[i].useCache) {
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
        cls.ctx.save()
        var per_style = cls.ctx.createRadialGradient(cls.dots[i].x, cls.dots[i].y, cls.dots[i].r * 0.1, cls.dots[i].x, cls.dots[i].y, cls.dots[i].r)
        var colors = [
          {key: 0, value: 'rgba(255,255,255,1)'},
          {key: 0.2, value: 'rgba(0,255,255,1)'},
          {key: 0.3, value: 'rgba(0,0,100,1)'},
          {key: 1, value: 'rgba(0,0,0,0.1)'}
        ]
        for (var item_style of colors) {
          per_style.addColorStop(item_style.key, item_style.value)
        }
        cls.ctx.fillStyle = per_style;
        cls.ctx.beginPath()
        cls.ctx.arc(cls.dots[i].x, cls.dots[i].y, cls.dots[i].r, 0, 2 * Math.PI)
        cls.ctx.closePath()
        cls.ctx.fill()
        cls.ctx.restore();
      }
      else {
        cls.ctx.drawImage(cls.dots[i].cacheCanvas, cls.dots[i].x - cls.dots[i].r, cls.dots[i].y - cls.dots[i].r);
      }
    }
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
