require("./lib/canvas/utils")
const utils =  window.utils
const requestAnimationFrame = window.requestAnimationFrame
class DrawCanvas {
    constructor(el,width,height) {
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
            mode_x:0,
            mode_y:0,
            mode_z:0
        }
        this.ctx_center = {
            x:0,
            y:0
        }
        this.dots = []

    }
    initWidthHeight(width,height){
        this.width = width
        this.height = height
    }
    initDot({
                fl = 200,
                start_time=Date.parse(new Date()),
                end_time=false,
                init_center = false,
                center = false,
                radius = false,
                each_touch_test = false,
                edge_touch_test = false,
                style = false,
                z=0,
                ctrl_v = {
                    c_x:0,
                    c_y:0,
                    c_z:0
                },
                g = {
                    down:1,
                    right:0,
                    out:0
                },
                friction = {
                    x:0.98,
                    y:0.98,
                    z:0.98
                },
                v = {
                    vx:0,
                    vy:0,
                    vz:0
                },
                scale_fn_base = 0,
                scale_fn = 1/(1 + -z/fl),
                scale = {
                    scale_X:scale_fn,
                    scale_Y:scale_fn
                },
                vp = {    //消失点
                    vpX:window.innerWidth/2,
                    vpY:window.innerHeight/2,
                },
                visible=true,
                colors=[
                    {key:0,value:'rgba(255,255,255,1)'},
                    {key:0.2,value:'rgba(0,255,255,1)'},
                    {key:0.3,value:'rgba(0,0,100,1)'},
                    {key:1,value:'rgba(0,0,0,0.1)'}
                ]
            }

    ){
        // console.log(g)
        let dot = {}

        dot.start_time = start_time
        dot.end_time = end_time
        if(!radius){
            // radius = parseInt(this.width/80)+z
            // // console.log(radius)
            // if(radius<0 ){
            //     radius = 1
            // }
            radius = 40

        }
        // if(!center){
        //     // init_center = center = {
        //     //     x:parseInt(Math.abs(Math.random()*this.width)-radius),
        //     //     y:parseInt(Math.abs(Math.random()*this.height)-radius),
        //     // }
        //     if(init_center){
        //         center = init_center
        //     }
        //     else{
        //         center = init_center = {
        //                 x:parseInt(Math.abs(Math.random()*this.width)-radius),
        //                 y:parseInt(Math.abs(Math.random()*this.height)-radius),
        //             }
        //     }
        // }
        dot.colors = colors
        dot.visible = visible
        dot.fl = fl
        dot.ctrl_v = ctrl_v
        if(scale_fn_base){
            dot.scale_fn = scale_fn_base
        }
        dot.scale = scale = {
            scale_X : dot.scale_fn,
            scale_Y : dot.scale_fn
        }
        dot.vp = vp
        dot.radius = radius

        dot.init_center = init_center
        dot.center = {
            x:init_center.x,
            y:init_center.y
        }


        style = this.initStyle(dot.center,radius,false,dot.colors)


        dot.style = style

        dot.each_touch_test = each_touch_test
        dot.edge_touch_test = edge_touch_test
        dot.z = z
        dot.g = g
        dot.friction = friction
        dot.v = v
        this.dots.push(dot)
    }
    test({a=1}){
        console.log('a->',a)

    }
    move_3D(x=0,y=0.2,z=0){
        var cls = this
        // console.log(x,y,z)
        this.dots.forEach(function (k,v) {
            cls.dots[v].ctrl_v.c_x += cls.ctrl_mode.mode_x
            cls.dots[v].ctrl_v.c_x -= cls.dots[v].friction.x * (typeof x === 'undefined'?0:x)
            cls.dots[v].ctrl_v.c_y += cls.ctrl_mode.mode_y
            cls.dots[v].ctrl_v.c_y -= cls.dots[v].friction.y * (typeof y === 'undefined'?0:y)
            cls.dots[v].ctrl_v.c_z += cls.ctrl_mode.mode_z

            cls.dots[v].ctrl_v.c_z -= cls.dots[v].friction.z * (typeof z === 'undefined'?0:z)
            // cls.dots[v].ctrl_v.c_x = cls.ctrl_mode.mode_x
            // cls.dots[v].ctrl_v.c_y = cls.ctrl_mode.mode_y
            // cls.dots[v].ctrl_v.c_z = cls.ctrl_mode.mode_z

            cls.dots[v].init_center.x += cls.dots[v].ctrl_v.c_x
            cls.dots[v].init_center.y += cls.dots[v].ctrl_v.c_y

            cls.dots[v].z += cls.dots[v].ctrl_v.c_z

            cls.dots[v].scale_fn = 1/(1 + -cls.dots[v].z/cls.dots[v].fl)

            if(cls.dots[v].init_center.y < -(cls.height/1.5)){
                cls.dots[v].init_center.y = cls.height/1.5;
            }
            if(cls.dots[v].z>cls.dots[v].fl){
                cls.dots[v].z = -1000
            }
            if(cls.dots[v].scale_fn>1000){
                cls.dots[v].scale_fn=1000
            }
            if(cls.dots[v].z<-1000){
                cls.dots[v].z=cls.dots[v].fl
            }
            cls.dots[v].scale = {
                scale_X:cls.dots[v].scale_fn,
                scale_Y:cls.dots[v].scale_fn
            }



            cls.dots[v].center.x = cls.dots[v].init_center.x * cls.dots[v].scale_fn;
            cls.dots[v].center.y = cls.dots[v].init_center.y * cls.dots[v].scale_fn;
            // console.log(cls.dots[v].scale)
            // console.log(cls.dots[v].center)

            cls.dots[v].ctrl_v.c_x *= cls.dots[v].friction.x
            cls.dots[v].ctrl_v.c_y *= cls.dots[v].friction.y
            cls.dots[v].ctrl_v.c_z *= cls.dots[v].friction.z

        });

    }
    move_line(type='left_right_center'){}

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
        while(is_color.length<=5){
            is_color = '0' + is_color
        }
        return '#' + is_color
    }

    initStyle(center,radius,add_cmd = false,
              colors){
        let style = {}
        let per_style = this.ctx.createRadialGradient(center.x,center.y,radius*0.1,center.x,center.y,radius)
        // // per_style.addColorStop(0,this.randomColor())
        // // per_style.addColorStop(1,"white")
        for (var item_style of colors){
            per_style.addColorStop(item_style.key,item_style.value)
        }
        // per_style.addColorStop(0,"rgba(255,255,255,1)");
        // per_style.addColorStop(0.2,"rgba(0,255,255,1)");
        // per_style.addColorStop(0.3,"rgba(0,0,100,1)");
        // per_style.addColorStop(1,"rgba(0,0,0,0.1)");
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
        // console.log(this.dots)
        this.dots.sort(zSort)
        // console.log(this.dots)
        this.ctx.clearRect(0, 0, this.width, this.height);



        var canvasM =  this
        this.dots.forEach(function (k,v) {
            canvasM.ctx.save()

            if(k.visible){
                if(k.vp){
                    canvasM.ctx.translate(k.vp.vpX,k.vp.vpY)
                }
                if(k.style){
                    if(k.style.RadialGradient){
                        // canvasM.ctx.fillStyle = k.style.RadialGradient
                        canvasM.ctx.fillStyle = canvasM.initStyle(k.center,k.radius,false,k.colors).RadialGradient

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

                if(k.scale){
                    canvasM.ctx.scale(k.scale.scale_X,k.scale.scale_Y)
                }
                canvasM.ctx.beginPath()
                canvasM.ctx.arc(k.center.x,k.center.y,k.radius,0,2* Math.PI)
                canvasM.ctx.closePath()
                // canvasM.ctx.arc(10,10,5,0,2* Math.PI)
                // canvasM.ctx.arc(20,20,10,0,2*Math.PI)
                if(k.style.strokeStyle) {
                    canvasM.ctx.stroke()
                }
                canvasM.ctx.fill()
            }
            canvasM.ctx.restore()



        })

        function zSort(a,b){
            return (a.z - b.z);
        }
    }


    initCtrl(){
        const cls = this
        window.addEventListener('keydown', function (event) {
            // console.log('event.keyCode',event.keyCode)
            switch (event.keyCode) {
                case 38:        //up
                    cls.ctrl_mode.mode_z = 1;
                    break;
                case 40:        //down
                    cls.ctrl_mode.mode_z = -1;
                    break;
                case 37:        //left
                    cls.ctrl_mode.mode_x = 1;
                    break;
                case 39:        //right
                    cls.ctrl_mode.mode_x = -1;
                    break;
                case 32:        //space
                    cls.ctrl_mode.mode_y = 1;
                    break;
                case 191:        //ctrl
                    cls.ctrl_mode.mode_y = -1;
                    break;

            }
        }, false);

        window.addEventListener('keyup', function (event) {
            switch (event.keyCode) {
                case 38:        //up
                case 40:        //down
                    cls.ctrl_mode.mode_z = 0;
                    break;
                case 37:        //left
                case 39:        //right
                    cls.ctrl_mode.mode_x = 0;
                    break;
                case 32:        //space
                case 191:        //space
                    cls.ctrl_mode.mode_y = 0;
                    break;
            }
        }, false);
    }

    draw({x=1,y=2}) {
        console.log(x)
        console.log(y)
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
export default DrawCanvas
