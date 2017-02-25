require("./lib/canvas/utils")
const utils =  window.utils
const requestAnimationFrame = window.requestAnimationFrame
class DrawCanvas {
    constructor(el) {
        this.el = el
        this.canvas = document.getElementById(this.el)
        // this.CANVAS_Width = document.getElementById(this.el).offsetWidth
        // this.CANVAS_Height = document.getElementById(this.el).offsetHeight()
        this.width = window.innerWidth;
        this.height = window.innerHeight
        this.canvas.width = this.width
        this.canvas.height = this.height
        this.ctx = this.canvas.getContext('2d')

        this.dots = []

    }

    initDot(
        fl = 200,
        start_time=Date.parse(new Date()),
        end_time=false,
        center = false,
        radius = false,
        each_touch_test = false,
        edge_touch_test = false,
        style = false,
        z=0,
        g = {
            down:1,
            right:0,
            out:0
        },
        v = {
            vx:0,
            vy:0,
            vz:0
        },
        scale = 1/(1 + z/fl),
        vp = {    //消失点
            vpX:window.innerWidth/2,
            vpY:window.innerHeight/2,
        },
        visible=true,


    ){

        let dot = {}
        z = -Math.abs(Math.random()*10)
        dot.start_time = start_time
        dot.end_time = end_time
        if(!radius){
            // radius = parseInt(this.width/80)+z
            // // console.log(radius)
            // if(radius<0 ){
            //     radius = 1
            // }
            radius = 30

        }
        if(!center){
            center = {
                x:parseInt(Math.abs(Math.random()*this.width)-radius),
                y:parseInt(Math.abs(Math.random()*this.height)-radius),
            }
        }
        if(!style){
            style = this.initStyle(center,radius)

        }
        dot.visible = visible
        dot.fl = fl
        dot.style = style
        dot.vp = vp
        dot.radius = radius
        dot.center = center
        dot.each_touch_test = each_touch_test
        dot.edge_touch_test = edge_touch_test
        dot.z = z
        dot.g = g
        dot.v = v
        this.dots.push(dot)
    }
    move(){
        var dots = this.dots
        var cls = this
        this.dots.forEach(function (k,v) {
            dots[v].center.x += 1
        })
    }
    fixDotCenter(new_dot) {
        this.dots.forEach(function (k, v) {
            if(new_dot.each_touch_test)
            {
                if(z === k.z){
                    var long_line = Math.pow(new_dot.center.x-k.center.x,2)+Math.pow(new_dot.center.y-k.center.y,2)
                    var x_line = Math.pow(new_dot.center.x-k.center.x,2)
                    var y_line = Math.pow(new_dot.center.y-k.center.y,2)
                    var x_fn = x_line/long_line
                    var y_fn = y_line/long_line
                    var x_location = new_dot.center.x>k.center.x? 1:0
                    var y_location = new_dot.center.y>k.center.y? 1:0
                    var cur_long_line = Math.pow(new_dot.radius+k.radius)
                    var cur_x_point = 0
                    var cur_y_point = 0
                    if(long_line < cur_long_line)
                    {
                        if(x_location && y_location){
                            cur_x_point = Math.sqrt(cur_long_line*x_fn)+k.center.x
                            cur_y_point = Math.sqrt(cur_long_line*y_fn)+k.center.y
                        }
                        if(x_location && !y_location){
                            cur_x_point = Math.sqrt(cur_long_line*x_fn)+k.center.x
                            cur_y_point = Math.sqrt(cur_long_line*y_fn)-k.center.y
                        }
                        if(!x_location && y_location){
                            cur_x_point = Math.sqrt(cur_long_line*x_fn)-k.center.x
                            cur_y_point = Math.sqrt(cur_long_line*y_fn)+k.center.y
                        }
                        if(!x_location && !y_location){
                            cur_x_point = Math.sqrt(cur_long_line*x_fn)-k.center.x
                            cur_y_point = Math.sqrt(cur_long_line*y_fn)-k.center.y
                        }
                    }
                    new_dot.center = {
                        x:cur_x_point,
                        y:cur_y_point
                    }

                }
            }

        })

    }
    randomColor(){
        var is_color = (Math.floor(Math.random()*0xffffff).toString(16))+""
        console.log(is_color+'->'+is_color.length)
        while(is_color.length<=5){
            is_color = '0' + is_color
        }
        return '#' + is_color
    }
    initStyle(center,radius,add_cmd = false){
        let style = {}
        let per_style = this.ctx.createRadialGradient(center.x,center.y,radius*0.1,center.x,center.y,radius)
        // per_style.addColorStop(0,this.randomColor())
        // per_style.addColorStop(1,"white")
        per_style.addColorStop(0,"rgba(255,255,255,1)");
        per_style.addColorStop(0.2,"rgba(0,255,255,1)");
        per_style.addColorStop(0.3,"rgba(0,0,100,1)");
        per_style.addColorStop(1,"rgba(0,0,0,0.1)");
        style.RadialGradient = per_style
        return style

        // style.shadowBlur = 20
        // style.shadowColor = '#E69269'
        // style.strokeStyle = '#ffd98f'

        // var grd=this.ctx.createRadialGradient(20,20,5,20,20,10);
        // grd.addColorStop(0,"red");
        // grd.addColorStop(1,"white");
        // style = grd
    }
    drawDots(){

        this.ctx.clearRect(0, 0, this.width, this.height);
        var canvasM =  this
        this.dots.forEach(function (k,v) {

            if(k.visible){
                canvasM.ctx.beginPath()
                if(k.style){
                    if(k.style.RadialGradient){
                        // canvasM.ctx.fillStyle = k.style.RadialGradient
                        canvasM.ctx.fillStyle = canvasM.initStyle(k.center,k.radius).RadialGradient

                    }
                    if(k.style.shadowBlur){
                        canvasM.ctx.shadowBlur = k.style.shadowBlur
                    }
                    if(k.style.shadowColor){
                        canvasM.ctx.shadowBlur = k.style.shadowColor
                    }
                    if(k.style.strokeStyle){
                        canvasM.ctx.strokeStyle = k.style.strokeStyle
                    }
                }
                if(k.scala){
                    canvasM.ctx.scale(k.centerx)
                }
                canvasM.ctx.arc(k.center.x,k.center.y,k.radius,0,2* Math.PI)
                // canvasM.ctx.arc(10,10,5,0,2* Math.PI)
                // canvasM.ctx.arc(20,20,10,0,2*Math.PI)
                if(k.style.strokeStyle) {
                    canvasM.ctx.stroke()
                }
                canvasM.ctx.fill()
            }



        })
    }

    draw() {
        this.ctx.beginPath();
        this.ctx.arc(100, 75, 50, 0, 2 * Math.PI);
        this.ctx.fill();
    }

    drawStats() {

        var canvas = this.canvas
        var c = this.ctx

        var numStars = 1000;
        var radius = 1;
        var focalLength = canvas.width;

        var centerX, centerY;

        var stars = [], star;
        var i;

        var animate = false;

        initializeStars();

        function executeFrame() {
            if (animate) {
                requestAnimFrame(executeFrame);

            }
            moveStars();
            drawStars();
        }

        function initializeStars() {
            centerX = canvas.width / 2;
            centerY = canvas.height / 2;

            stars = [];
            for (i = 0; i < numStars; i++) {
                star = {
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    z: Math.random() * canvas.width
                };
                stars.push(star);
            }
        }

        function moveStars() {
            for (i = 0; i < numStars; i++) {
                star = stars[i];
                star.z--;

                if (star.z <= 0) {
                    star.z = canvas.width;
                }
            }
        }

        function drawStars() {
            var pixelX, pixelY, pixelRadius;

            // Resize to the screen
            if (canvas.width !== window.innerWidth || canvas.width !== window.innerWidth) {
                // canvas.width = window.innerWidth;
                // canvas.height = window.innerHeight;
                initializeStars();
            }

            c.fillStyle = "rgba(26,27,24,0.7)";
            c.fillRect(0, 0, canvas.width, canvas.height);
            c.fillStyle = "white";
            for (i = 0; i < numStars; i++) {
                star = stars[i];

                pixelX = (star.x - centerX) * (focalLength / star.z);
                pixelX += centerX;
                pixelY = (star.y - centerY) * (focalLength / star.z);
                pixelY += centerY;
                pixelRadius = radius * (focalLength / star.z);

                c.beginPath();
                c.arc(pixelX, pixelY, pixelRadius, 0, 2 * Math.PI);
                c.fill();
            }
        }

        canvas.addEventListener("mousemove", function (e) {
            focalLength = e.x;
        });

// Kick off the animation when the mouse enters the canvas
        canvas.addEventListener('mouseover', function (e) {
            animate = true;
            executeFrame();
        });

// Pause animation when the mouse exits the canvas
        canvas.addEventListener("mouseout", function (e) {
            var mouseDown = false;
            animate = true;
        });

// Draw the first frame to start animation
        executeFrame();
    }


}
module.exports = exports = DrawCanvas
