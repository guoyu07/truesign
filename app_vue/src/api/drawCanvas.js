require("./lib/canvas/utils")
const TWEEN = require('@tweenjs/tween.js');
const utils = window.utils
const requestAnimationFrame = window.requestAnimationFrame

class DrawCanvas {
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

        let old_stats = document.getElementById('canvas_stats')
        if(old_stats !== null){
            document.body.removeChild(old_stats);
        }

        this.stats = new Stats()
        this.stats.setMode(0);
        this.stats.domElement.id='canvas_stats'
        this.stats.domElement.style.position = 'fixed';
        this.stats.domElement.style.left = this.width  - 100 + 'px';
        this.stats.domElement.style.top = '0px';
        this.stats.domElement.style.width = '300px';
        this.stats.domElement.style.height = '100px';
        document.body.appendChild(this.stats.domElement);

    }

    initWidthHeight(width, height) {
        this.width = width
        this.height = height
    }

    initDot({
                cid = 1,
                group = '',
                fl = 200,
                start_time = Date.parse(new Date()),
                end_time = false,
                init_center = false,
                center = false,
                radius = false,
                edge_touch_test = false,
                each_touch_test = false,
                style = false,
                z = 0,
                ctrl_v = {
                    c_x: 0,
                    c_y: 0,
                    c_z: 0
                },
                g = {
                    down: 1,
                    right: 0,
                    out: 0
                },
                friction = {
                    /*摩擦力，阻力系数*/
                    x: 0.98,
                    y: 0.98,
                    z: 0.98
                },
                v = {
                    vx: 0,
                    vy: 0,
                    vz: 0
                },
                constant_speed = {
                    x: 0,
                    y: 0,
                    z: 0
                },
                vector = {
                    /*向量*/
                    x: 1,
                    y: 1,
                    z: 1
                },
                chaos = {
                    /* 混乱系数*/
                    x: 1,
                    y: 1,
                    z: 1,
                },
                scale_fn_base = 0,
                scale_fn = 1 / (1 + -z / fl),
                scale = {
                    scale_X: scale_fn,
                    scale_Y: scale_fn
                },
                vp = {    //消失点
                    vpX: this.canvas.width / 2,
                    vpY: this.canvas.height / 2,
                },
                visible = true,
                colors = [
                    {key: 0, value: 'rgba(255,255,255,1)'},
                    {key: 0.2, value: 'rgba(0,255,255,1)'},
                    {key: 0.3, value: 'rgba(0,0,100,1)'},
                    {key: 1, value: 'rgba(0,0,0,0.1)'}
                ],
                move = true,
                move_way = false,
                cache = false,
                cacheCanvas = {}
            }) {
        // console.log(g)
        this.dot = {}
        this.dot.cid = cid
        this.dot.group = group
        this.dot.start_time = start_time
        this.dot.end_time = end_time
        if (!radius) {

            radius = 40

        }

        this.dot.colors = colors
        this.dot.visible = visible
        this.dot.fl = fl
        this.dot.ctrl_v = ctrl_v
        if (scale_fn_base) {
            this.dot.scale_fn = scale_fn_base
        }
        this.dot.scale = scale = {
            scale_X: this.dot.scale_fn,
            scale_Y: this.dot.scale_fn
        }
        this.dot.vp = vp
        this.dot.radius = radius

        this.dot.init_center = init_center
        this.dot.center = {
            x: init_center.x,
            y: init_center.y,
            tmp_x: init_center.x,
            tmp_y: init_center.y,
        }
        //
        //
        style = this.initStyle(this.dot)
        // style = {}

        this.dot.style = style

        this.dot.edge_touch_test = edge_touch_test
        this.dot.each_touch_test = each_touch_test
        this.dot.z = z
        this.dot.g = g

        this.dot.constant_speed = {
            x: constant_speed.hasOwnProperty('x') ? constant_speed.x : 0,
            y: constant_speed.hasOwnProperty('y') ? constant_speed.y : 0,
            z: constant_speed.hasOwnProperty('z') ? constant_speed.z : 0
        }
        this.dot.friction = {
            x: friction.hasOwnProperty('x') ? friction.x : 0.98,
            y: friction.hasOwnProperty('y') ? friction.y : 0.98,
            z: friction.hasOwnProperty('z') ? friction.z : 0.98
        }
        this.dot.vector = {
            x: vector.hasOwnProperty('x') ? vector.x : 1,
            y: vector.hasOwnProperty('y') ? vector.y : 1,
            z: vector.hasOwnProperty('z') ? vector.z : 1
        }
        this.dot.chaos = {
            x: chaos.hasOwnProperty('x') ? chaos.x : 1,
            y: chaos.hasOwnProperty('y') ? chaos.y : 1,
            z: chaos.hasOwnProperty('z') ? chaos.z : 1
        }
        // dot.friction ={
        //     x : 1321
        // y : friction.hasOwnProperty(y)?friction.y:0.98,
        // z : friction.hasOwnProperty(z)?friction.z:0.98
        // }
        // dot.friction.y = friction.hasOwnProperty(y)?friction.y:0.98
        // dot.friction.z = friction.hasOwnProperty(z)?friction.z:0.98
        this.dot.v = v
        this.dot.move = move
        this.dot.move_way = move_way
        // cacheDom.width = 2 * radius
        // cacheDom.height = 2 * radius

        // cacheDom.style.backgroundColor = 'black'
        this.dot.cache = cache
        if (cache) {
            //   let cacheDom = document.createElement("canvas")
            //   cacheDom.width = this.width
            //   cacheDom.height = this.height
            //   console.log('iscache')
            this.dot.cacheCanvas = {
                cacheDom: {},
                cacheCtx: {}
            }
            this.dot.cacheCanvas.cacheDom = document.createElement("canvas")
            this.dot.cacheCanvas.cacheDom.width = 2 * this.dot.radius
            this.dot.cacheCanvas.cacheDom.height = 2 * this.dot.radius
            this.dot.cacheCanvas.cacheCtx = this.dot.cacheCanvas.cacheDom.getContext("2d")

            // this.dot.cacheCanvas = this.drawCache(this.dot)
            this.drawCacheByDot()
        }
        this.dots.push(this.dot)
        return this.dot

    }


    move_3D(x = 0, y = 0.2, z = 0) {

        var cls = this
        // console.log(x,y,z)
        this.dots.forEach(function (k, v) {
                if (cls.dots[v].move) {
                    if (!cls.dots[v].constant_speed.x) {
                        cls.dots[v].ctrl_v.c_x += cls.ctrl_mode.mode_x
                        cls.dots[v].ctrl_v.c_x -= cls.dots[v].friction.x * (typeof x === 'undefined' ? 0 : x)
                    }
                    else {
                        cls.dots[v].ctrl_v.c_x = cls.dots[v].constant_speed.x
                    }
                    if (!cls.dots[v].constant_speed.y) {
                        cls.dots[v].ctrl_v.c_y += cls.ctrl_mode.mode_y
                        cls.dots[v].ctrl_v.c_y -= cls.dots[v].friction.y * (typeof y === 'undefined' ? 0 : y)
                    }
                    else {
                        cls.dots[v].ctrl_v.c_y = cls.dots[v].constant_speed.y
                    }
                    if (!cls.dots[v].constant_speed.z) {
                        cls.dots[v].ctrl_v.c_z += cls.ctrl_mode.mode_z

                        cls.dots[v].ctrl_v.c_z -= cls.dots[v].friction.z * (typeof z === 'undefined' ? 0 : z)
                    }
                    else {
                        cls.dots[v].ctrl_v.c_z = cls.dots[v].constant_speed.z
                    }

                    if (cls.dots[v].group === 'loading_line') {

                        var params = ''
                        if (!params) {
                            params = cls.dots[v].move_way.params
                        }
                        var left_x = -cls.canvas.width / 2
                        var right_x = cls.canvas.width / 2
                        var top_y = -cls.canvas.height / 2
                        var bottom_y = cls.canvas.height / 2
                        var tan = cls.canvas.height / cls.canvas.width
                        // var init_dot = {center_x: cls.dots[v].center.x, center_y: cls.dots[v].center.y}
                        if (cls.dots[v].move_way.type === 'loading_line') {

                            var init_loading_line = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        x: -cls.canvas.width / 2 + (cls.dots[v].cid * cls.dots[v].radius + (cls.dots[v].cid - 1) * cls.dots[v].radius) / 2,
                                        y: cls.canvas.height / 2 - tan * (cls.dots[v].cid * cls.dots[v].radius + (cls.dots[v].cid - 1) * cls.dots[v].radius) / 2

                                    }, cls.dots[v].cid * 6)
                                .easing(TWEEN.Easing.Quadratic.In)
                                .onUpdate(function () {
                                })
                            var line_to_bottom = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        // x: -cls.canvas.width/2,

                                        y: cls.canvas.height / 2 - 8
                                    }, Math.abs(Math.random()) * (params.count - cls.dots[v].cid) * 20)
                                .delay(params.count * 6)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                })


                            var bottom_center_left_point = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        x: -cls.canvas.width / 2 + 2,
                                        y: cls.canvas.height / 4
                                    }, 600)
                                // .delay(parseInt(cls.dots[v].cid + '00') / 20)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                })
                            //
                            var bottom_left_point = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        // x: -cls.canvas.width/2,
                                        x: -cls.canvas.width / 2 + 8
                                    }, 600)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {

                                })
                            var disvisible = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        x: -cls.canvas.width / 2 + 2,
                                        y: cls.canvas.height / 4
                                    }, 600)
                                // .delay(parseInt(cls.dots[v].cid + '00') / 20)
                                .easing(TWEEN.Easing.Linear.None)
                                .onStart(function () {

                                    cls.dots[v].visible = false
                                })
                                .onUpdate(function () {
                                    cls.dots[v].visible = false
                                })

                            if (params.percent >= 100) {
                                // fix_dot.chain(init_loading_line)
                                init_loading_line.chain(line_to_bottom)
                                line_to_bottom.chain(bottom_left_point)
                                bottom_left_point.chain(bottom_center_left_point)
                                bottom_center_left_point.chain(disvisible)
                                init_loading_line.start()
                            }
                            else {
                                init_loading_line.start()
                            }
                        }
                        if (cls.dots[v].move_way.type === 'beat_line') {

                            var time_x = 460,
                                time_y = 10.5,
                                time_z = 10
                            var start_dot =
                                new TWEEN.Tween(cls.dots[v].center)
                                    .to(
                                        {
                                            x: -cls.canvas.width / 2 + 2,
                                            y: cls.canvas.height / 4

                                        }, time_z
                                    )
                                    .easing(TWEEN.Easing.Linear.None)
                                    .onStart(function () {
                                        cls.dots[v].visible = false
                                    })
                                    .onUpdate(function () {
                                        cls.dots[v].visible = false
                                    })
                            var fix_dot =
                                new TWEEN.Tween(cls.dots[v].center)
                                    .to(
                                        {
                                            x: -cls.canvas.width / 2 + ((cls.canvas.width / 2 - 130) / params.count) * cls.dots[v].cid,
                                            y: cls.canvas.height / 4

                                        }, (cls.dots[v].cid) * time_y)
                                    .easing(TWEEN.Easing.Linear.None)
                                    .onStart(function () {
                                        // console.log(params.count,cls.canvas.width / 2-130,parseInt((cls.canvas.width / 2-130)/params.count))
                                        cls.dots[v].visible = true
                                    })
                                    .onUpdate(function () {
                                        cls.dots[v].visible = true
                                    })
                            var bottom_mid_height_left_left = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: -130,
                                        y: cls.canvas.height / 4
                                    }, time_x
                                )
                                .easing(TWEEN.Easing.Linear.None)
                                .onStart(function () {
                                    cls.dots[v].visible = true
                                })
                                .onUpdate(function () {
                                    cls.dots[v].visible = true
                                })
                            var bottom_mid_height_left_top = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: -100,
                                        y: cls.canvas.height / 6,
                                    }, time_x
                                )
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    cls.dots[v].visible = true
                                })
                            var bottom_mid_height_left_bottom = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: -60,
                                        y: cls.canvas.height / 4 + (cls.canvas.height / 4 - cls.canvas.height / 6)
                                    }, time_x * 2
                                )
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    cls.dots[v].visible = true

                                })
                            var bottom_mid_height_left_rigth = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: -30,
                                        y: cls.canvas.height / 4
                                    }, time_x
                                )
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    cls.dots[v].visible = true

                                })

                            var bottom_mid_height_right_left = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: 30,
                                        y: cls.canvas.height / 4
                                    }, time_x
                                )
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    cls.dots[v].visible = true
                                })
                            var bottom_mid_height_right_top = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: 60,
                                        y: cls.canvas.height / 8,
                                    }, time_x
                                )
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    cls.dots[v].visible = true
                                })
                            var bottom_mid_height_right_bottom = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: 100,
                                        y: cls.canvas.height / 4 + (cls.canvas.height / 4 - cls.canvas.height / 8)
                                    }, time_x * 2
                                )
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    cls.dots[v].visible = true
                                })
                            var bottom_mid_height_right_right = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: 130,
                                        y: cls.canvas.height / 4
                                    }, time_x
                                )
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    cls.dots[v].visible = true
                                })
                            var bottom_center_right_point_end = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        // x: -cls.canvas.width/2,
                                        x: cls.canvas.width / 2 - 15
                                    }, (params.count - cls.dots[v].cid) * time_y)
                                .easing(TWEEN.Easing.Linear.None)

                                .onUpdate(function () {
                                    cls.dots[v].visible = true
                                })
                            var v_disvisible = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        // x: -cls.canvas.width/2,
                                        x: cls.canvas.width / 2 - 15
                                    }, time_z)
                                .easing(TWEEN.Easing.Linear.None)
                                .onStart(function () {
                                    cls.dots[v].visible = false
                                })
                                .onUpdate(function () {
                                    cls.dots[v].visible = false
                                })
                            if (params.percent >= 100) {
                                start_dot.chain(fix_dot)
                                fix_dot.chain(bottom_mid_height_left_left)
                                bottom_mid_height_left_left.chain(bottom_mid_height_left_top)
                                bottom_mid_height_left_top.chain(bottom_mid_height_left_bottom)
                                bottom_mid_height_left_bottom.chain(bottom_mid_height_left_rigth)
                                bottom_mid_height_left_rigth.chain(bottom_mid_height_right_left)
                                bottom_mid_height_right_left.chain(bottom_mid_height_right_top)
                                bottom_mid_height_right_top.chain(bottom_mid_height_right_bottom)
                                bottom_mid_height_right_bottom.chain(bottom_mid_height_right_right)
                                bottom_mid_height_right_right.chain(bottom_center_right_point_end)
                                bottom_center_right_point_end.chain(v_disvisible)
                                v_disvisible.chain(start_dot)
                                start_dot.start()


                                // init_loading_line.chain(top_right_point)
                                // top_right_point.chain(bottom_center_right_point)

                                // bottom_center_right_point.chain(bottom_right_point)
                                // bottom_right_point.chain(bottom_left_point)
                                // line_to_bottom.chain(bottom_left_point)
                                // bottom_left_point.chain(bottom_center_left_point)
                                //
                                // bottom_center_left_point.chain(bottom_mid_height_left_left)
                                //

                                // bottom_center_right_point_end.chain(bottom_right_point)

                            }
                            else {
                                init_loading_line.start()
                            }
                        }
                        if (cls.dots[v].move_way.type === 'clockwise') {
                            var init_cross = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        tmp_x: 0,
                                        tmp_y: 0,
                                        x: 0,
                                        y: 0,

                                    }, 1800)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {

                                })

                            var move_cross = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: params.cross_radius * Math.cos((2 * Math.PI / 360 * (params.angle * cls.dots[v].cid))),
                                        y: params.cross_radius * Math.sin((2 * Math.PI / 360 * (params.angle * cls.dots[v].cid))),

                                    }, 200)
                                .delay(200)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {

                                })
                            init_cross.chain(move_cross)
                            init_cross.start()

                            // if(cls.ctrl_mode.mode_z){
                            //     params.angle += cls.dots[v].ctrl_v.c_z
                            //     cls.dots[v].x =  params.cross_radius*Math.cos((2*Math.PI/360 * (params.angle*cls.dots[v].cid)) )
                            //     cls.dots[v].y =  params.cross_radius*Math.sin((2*Math.PI/360 * (params.angle*cls.dots[v].cid)) )
                            // }
                        }
                        if (cls.dots[v].move_way.type === 'i_point') {

                            var init_i_point = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        tmp_x: 0,
                                        tmp_y: 0,
                                        x: 0,
                                        y: 0,

                                    }, 1800)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {

                                })
                            var to_x = params.cross_radius * Math.cos((2 * Math.PI / 360 * (params.angle * cls.dots[v].cid)))
                            var to_y = params.cross_radius * Math.sin((2 * Math.PI / 360 * (params.angle * cls.dots[v].cid)))
                            var move_i_point = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: to_x,
                                        y: to_y

                                    }, 200)
                                .delay(200)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {

                                })
                            init_i_point.chain(move_i_point)
                            init_i_point.start()
                        }
                        if (cls.dots[v].move_way.type === 'i_body') {

                            var init_i_body = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        tmp_x: 0,
                                        tmp_y: 0,
                                        x: 0,
                                        y: 0,

                                    }, 1800)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {

                                })
                            let to_x = (params.cross_radius - cls.dots[v].cid) * Math.cos((2 * Math.PI / 360 * (params.angle * cls.dots[v].cid)))
                            let to_y = (params.cross_radius - cls.dots[v].cid) * Math.sin((2 * Math.PI / 360 * (params.angle * cls.dots[v].cid)))
                            to_x = (to_x > 0) ? to_x - cls.dots[v].cid : to_x + cls.dots[v].cid
                            var move_i_body = new TWEEN.Tween((cls.dots[v].center))
                                .to(
                                    {
                                        x: to_x,
                                        y: to_y

                                    }, 200)
                                .delay(200)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {

                                })


                            init_i_body.chain(move_i_body)
                            move_i_body.chain(init_i_body)
                            init_i_body.start()
                        }


                        // recovery_loading_line.chain(init_loading_line)


                        // if(params && params.percent === 100 && cls.dots[v].cid === params.count){
                        //     var left_x = -cls.canvas.width/2
                        //     var right_x = cls.canvas.width/2
                        //     var top_y = -cls.canvas.height/2
                        //     var bottom_y = cls.canvas.height/2

                        // cls.dots.forEach(function (kk,vv){
                        //     if(cls.dots[vv].group === 'loading_line') {
                        // var speed = (1/(1 + -cls.dots[vv].cid/params.count)-1)
                        // if (cls.dots[vv].center.x < 0 && cls.dots[vv].center.x > left_x) {
                        //     cls.ctrl_mode.mode_z = 1
                        //     let speed_lenght = (cls.dots[vv].center.x - left_x) / 10
                        //     if(speed_lenght > 1){
                        //         cls.dots[vv].center.x -= speed_lenght
                        //
                        //     }
                        //     else{
                        //         let speed_lenght = (cls.dots[vv].center.y - top_y) / 20
                        //         cls.dots[vv].center.y -= speed_lenght
                        //         if(speed_lenght <= 1){
                        //             cls.dots[vv].center.x -= speed_lenght
                        //             cls.ctrl_mode.mode_z = 0
                        //
                        //         }
                        //     }
                        //
                        // }
                        //
                        // else if (cls.dots[vv].center.x > 0 && cls.dots[vv].center.x < right_x - 10) {
                        //     let speed_lenght = (right_x - 10 - cls.dots[vv].center.x) / 10
                        //     if(speed_lenght > 1) {
                        //         cls.dots[vv].center.x += speed_lenght
                        //     }
                        //     else{
                        //         let speed_lenght = -(cls.dots[vv].center.y - bottom_y) / 20
                        //         cls.dots[vv].center.y += speed_lenght
                        //     }
                        // }

                        // }
                        // })


                        // }


                    }
                    else if (cls.dots[v].group === 'text') {

                        if(cls.dots[v].init_center.x === cls.dots[v].center.x && cls.dots.length>0){

                            let init_dots_xyz = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        x:cls.dots[v].center.x,
                                        y:cls.dots[v].center.y
                                    }, 1000
                                )
                                .delay(1500)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    if(cls.dots[v]) {

                                        if (cls.dots[v].hasOwnProperty('z')) {
                                            cls.dots[v].z = 0
                                            cls.dots[v].scale_fn = 1 / (1 + -cls.dots[v].z / cls.dots[v].fl)


                                            if (cls.dots[v].z > cls.dots[v].fl) {
                                                cls.dots[v].z = -1000
                                            }
                                            if (cls.dots[v].scale_fn > 1000) {
                                                cls.dots[v].scale_fn = 1000
                                            }
                                            if (cls.dots[v].z < -1000) {
                                                cls.dots[v].z = cls.dots[v].fl

                                            }
                                            cls.dots[v].scale = {
                                                scale_X: cls.dots[v].scale_fn,
                                                scale_Y: cls.dots[v].scale_fn
                                            }
                                        }
                                    }

                                })

                            let boom_dots = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        x: (Math.random()>0.5?1:-1)*Math.random()*cls.canvas.width/2,
                                        y: (Math.random()>0.5?1:-1)*Math.random()*cls.canvas.height/2
                                    }, 1000
                                )
                                .delay(1500)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                })
                            let fix_z = new TWEEN.Tween(cls.dots[v])
                                .to(
                                    {
                                        z: (Math.random()>0.5?1:-1)*Math.random()*200,
                                    }, 1000
                                )
                                .delay(1500)
                                .easing(TWEEN.Easing.Linear.None)
                                .onUpdate(function () {
                                    if(cls.dots[v]) {
                                        if(cls.dots[v].hasOwnProperty('z')){
                                            cls.dots[v].scale_fn = 1 / (1 + -cls.dots[v].z / cls.dots[v].fl)
                                            if (cls.dots[v].z > cls.dots[v].fl) {
                                                cls.dots[v].z = -1000
                                            }
                                            if (cls.dots[v].scale_fn > 1000) {
                                                cls.dots[v].scale_fn = 1000
                                            }
                                            if (cls.dots[v].z < -1000) {
                                                cls.dots[v].z = cls.dots[v].fl

                                            }
                                            cls.dots[v].scale = {
                                                scale_X: cls.dots[v].scale_fn,
                                                scale_Y: cls.dots[v].scale_fn
                                            }
                                        }
                                    }

                                })

                            init_dots_xyz.chain(boom_dots,fix_z)
                            boom_dots.chain(init_dots_xyz)

                            init_dots_xyz.start()
                        }
                       
                        /*处理墙壁触碰*/
                        if (cls.dots[v].edge_touch_test) {
                            if (Math.abs(cls.dots[v].center.x) > (Math.abs(cls.canvas.width / 2) - cls.dots[v].radius)) {
                                cls.dots[v].vector.x *= -1;

                            }
                            if (Math.abs(cls.dots[v].center.y) > (Math.abs(cls.canvas.height / 2) - cls.dots[v].radius)) {
                                cls.dots[v].vector.y *= -1;

                            }
                        }


                    }
                    else {
                        // cls.dots[v].ctrl_v.c_x = cls.ctrl_mode.mode_x
                        // cls.dots[v].ctrl_v.c_y = cls.ctrl_mode.mode_y
                        // cls.dots[v].ctrl_v.c_z = cls.ctrl_mode.mode_z
                        if (cls.dots[v].edge_touch_test) {
                            cls.dots[v].center.tmp_x += cls.dots[v].ctrl_v.c_x * cls.dots[v].vector.x * cls.dots[v].chaos.x
                            cls.dots[v].center.tmp_y += cls.dots[v].ctrl_v.c_y * cls.dots[v].vector.y * cls.dots[v].chaos.y
                            cls.dots[v].scale.scale_X += cls.dots[v].ctrl_v.c_z * cls.dots[v].vector.z * cls.dots[v].chaos.z
                            cls.dots[v].scale.scale_Y = cls.dots[v].scale.scale_X
                        }
                        else {
                            cls.dots[v].center.tmp_x += cls.dots[v].ctrl_v.c_x * cls.dots[v].chaos.x
                            cls.dots[v].center.tmp_y += cls.dots[v].ctrl_v.c_y * cls.dots[v].chaos.y
                            cls.dots[v].scale.scale_X += cls.dots[v].ctrl_v.c_z * cls.dots[v].chaos.z
                            cls.dots[v].scale.scale_Y = cls.dots[v].scale.scale_X
                        }


                        cls.dots[v].z += cls.dots[v].ctrl_v.c_z

                        cls.dots[v].scale_fn = 1 / (1 + -cls.dots[v].z / cls.dots[v].fl)

                        if (cls.dots[v].center.tmp_y < -(cls.height / 1.5)) {
                            cls.dots[v].center.tmp_y = cls.height / 1.5;
                        }
                        if (cls.dots[v].z > cls.dots[v].fl) {
                            cls.dots[v].z = -1000
                        }
                        if (cls.dots[v].scale_fn > 1000) {
                            cls.dots[v].scale_fn = 1000
                        }
                        if (cls.dots[v].z < -1000) {
                            cls.dots[v].z = cls.dots[v].fl

                        }
                        cls.dots[v].scale = {
                            scale_X: cls.dots[v].scale_fn,
                            scale_Y: cls.dots[v].scale_fn
                        }


                        cls.dots[v].center.x = cls.dots[v].center.tmp_x * cls.dots[v].scale_fn;
                        cls.dots[v].center.y = cls.dots[v].center.tmp_y * cls.dots[v].scale_fn;

                        /*处理墙壁触碰*/
                        if (cls.dots[v].edge_touch_test) {
                            if (Math.abs(cls.dots[v].center.x) > (Math.abs(cls.canvas.width / 2) - cls.dots[v].radius)) {
                                cls.dots[v].vector.x *= -1;

                            }
                            if (Math.abs(cls.dots[v].center.y) > (Math.abs(cls.canvas.height / 2) - cls.dots[v].radius)) {
                                cls.dots[v].vector.y *= -1;

                            }
                        }

                        if (!cls.dots[v].constant_speed.x) {
                            cls.dots[v].ctrl_v.c_x *= cls.dots[v].friction.x

                        }
                        if (!cls.dots[v].constant_speed.y) {
                            cls.dots[v].ctrl_v.c_y *= cls.dots[v].friction.y

                        }
                        if (!cls.dots[v].constant_speed.z) {
                            cls.dots[v].ctrl_v.c_z *= cls.dots[v].friction.z

                        }
                    }


                }
                if (cls.dots[v].move_way.type === 'test') {

                    var left = new TWEEN.Tween(cls.dots[v].center)
                        .to({x: -cls.canvas.width / 2, y: 0}, 3000)
                        .easing(TWEEN.Easing.Linear.None)
                        .onUpdate(function () {

                        })
                    var right = new TWEEN.Tween(cls.dots[v].center)
                        .to({x: cls.canvas.width / 2 - 10, y: 0}, 3000)
                        .easing(TWEEN.Easing.Linear.None)
                        .onUpdate(function () {

                        })
                    left.chain(right)
                    right.chain(left)
                    left.start()


                }


            }
        );

    }

    fixDotCenter(new_dot) {
        this.dots.forEach(function (k, v) {
            if (new_dot.each_touch_test) {
                if (z === k.z) {
                    var long_line = Math.pow(new_dot.center.x - k.center.x, 2) + Math.pow(new_dot.center.y - k.center.y, 2)
                    var x_line = Math.pow(new_dot.center.x - k.center.x, 2)
                    var y_line = Math.pow(new_dot.center.y - k.center.y, 2)
                    var x_fn = x_line / long_line
                    var y_fn = y_line / long_line
                    var x_location = new_dot.center.x > k.center.x ? 1 : 0
                    var y_location = new_dot.center.y > k.center.y ? 1 : 0
                    var cur_long_line = Math.pow(new_dot.radius + k.radius)
                    var cur_x_point = 0
                    var cur_y_point = 0
                    if (long_line < cur_long_line) {
                        if (x_location && y_location) {
                            cur_x_point = Math.sqrt(cur_long_line * x_fn) + k.center.x
                            cur_y_point = Math.sqrt(cur_long_line * y_fn) + k.center.y
                        }
                        if (x_location && !y_location) {
                            cur_x_point = Math.sqrt(cur_long_line * x_fn) + k.center.x
                            cur_y_point = Math.sqrt(cur_long_line * y_fn) - k.center.y
                        }
                        if (!x_location && y_location) {
                            cur_x_point = Math.sqrt(cur_long_line * x_fn) - k.center.x
                            cur_y_point = Math.sqrt(cur_long_line * y_fn) + k.center.y
                        }
                        if (!x_location && !y_location) {
                            cur_x_point = Math.sqrt(cur_long_line * x_fn) - k.center.x
                            cur_y_point = Math.sqrt(cur_long_line * y_fn) - k.center.y
                        }
                    }
                    new_dot.center = {
                        x: cur_x_point,
                        y: cur_y_point
                    }

                }
            }

        })

    }

    randomColor() {
        var is_color = (Math.floor(Math.random() * 0xffffff).toString(16)) + ""
        while (is_color.length <= 5) {
            is_color = '0' + is_color
        }
        return '#' + is_color
    }

    initStyle(dot, v_ctx) {
        let style = {}

        var per_style = {}
        if (v_ctx) {
            per_style = v_ctx.createRadialGradient(dot.radius, dot.radius, dot.radius * 0.1, dot.radius, dot.radius, dot.radius)
        }
        else {
            per_style = this.ctx.createRadialGradient(dot.center.x, dot.center.y, dot.radius * 0.1, dot.center.x, dot.center.y, dot.radius)
        }

        for (var item_style of dot.colors) {
            per_style.addColorStop(item_style.key, item_style.value)
        }
        // per_style.addColorStop(0,"rgba(255,0,0,1)");
        // per_style.addColorStop(0.2,"rgba(255,255,0,1)");
        // per_style.addColorStop(1,"rgba(0,0,0,1)");
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

    drawCache(dot) {
        // var canvasCache = dot.cacheCanvas
        // canvasCache.cacheCtx.save()
        // if (dot.style) {
        //     if (dot.style.RadialGradient) {
        //         // canvasCache.cacheCtx.fillStyle = dot.style.RadialGradient
        //
        //         var build_style = this.initStyle(dot,canvasCache.cacheCtx).RadialGradient
        //         // console.log('grd',grd)
        //         // console.log('build_style',build_style)
        //         canvasCache.cacheCtx.fillStyle = build_style
        //
        //     }
        //     if (dot.style.shadowBlur) {
        //         canvasCache.cacheCtx.shadowBlur = dot.style.shadowBlur
        //     }
        //     if (dot.style.shadowColor) {
        //         canvasCache.cacheCtx.shadowBlur = dot.style.shadowColor
        //     }
        //     if (dot.style.strokeStyle) {
        //         canvasCache.cacheCtx.strokeStyle = dot.style.strokeStyle
        //     }
        // }
        //
        //
        // canvasCache.cacheCtx.beginPath()
        // canvasCache.cacheCtx.arc(dot.radius,dot.radius , dot.radius, 0, 2 * Math.PI)
        // canvasCache.cacheCtx.closePath()
        // // canvasCache.cacheCtx.stroke()
        // // if (dot.style.strokeStyle) {
        // //     canvasCache.cacheCtx.stroke()
        // // }
        // canvasCache.cacheCtx.fill()
        // canvasCache.cacheCtx.restore();
        // return canvasCache
        var canvasCache = dot.cacheCanvas
        canvasCache.cacheCtx.save()
        // var per_style = this.initStyle(dot,canvasCache.cacheCtx).RadialGradient
        var per_style = canvasCache.cacheCtx.createRadialGradient(dot.radius, dot.radius, dot.radius * 0.1, dot.radius, dot.radius, dot.radius)
        var colors = [
            {key: 0, value: 'rgba(255,255,255,1)'},
            {key: 0.2, value: 'rgba(0,255,255,1)'},
            {key: 0.3, value: 'rgba(0,0,100,1)'},
            {key: 1, value: 'rgba(0,0,0,0.1)'}
        ]
        for (var item_style of colors) {
            per_style.addColorStop(item_style.key, item_style.value)
        }
        canvasCache.cacheCtx.fillStyle = per_style;
        canvasCache.cacheCtx.beginPath()
        canvasCache.cacheCtx.arc(dot.radius, dot.radius, dot.radius, 0, 2 * Math.PI)
        canvasCache.cacheCtx.closePath()
        canvasCache.cacheCtx.fill()
        canvasCache.cacheCtx.restore();
        return canvasCache


    }

    drawCacheByDot() {
        this.dot.cacheCanvas.cacheCtx.save()
        var per_style = this.initStyle(this.dot, this.dot.cacheCanvas.cacheCtx).RadialGradient
        // var per_style = this.dot.cacheCanvas.cacheCtx.createRadialGradient(this.dot.radius, this.dot.radius, this.dot.radius * 0.1, this.dot.radius, this.dot.radius, this.dot.radius)
        // var colors = [
        //   {key: 0, value: 'rgba(255,255,255,1)'},
        //   {key: 0.2, value: 'rgba(0,255,255,1)'},
        //   {key: 0.3, value: 'rgba(0,0,100,1)'},
        //   {key: 1, value: 'rgba(0,0,0,0.1)'}
        // ]
        // for (var item_style of colors) {
        //   per_style.addColorStop(item_style.key, item_style.value)
        // }
        this.dot.cacheCanvas.cacheCtx.fillStyle = per_style;
        this.dot.cacheCanvas.cacheCtx.beginPath()
        this.dot.cacheCanvas.cacheCtx.arc(this.dot.radius, this.dot.radius, this.dot.radius, 0, 2 * Math.PI)
        this.dot.cacheCanvas.cacheCtx.closePath()
        this.dot.cacheCanvas.cacheCtx.fill()
        this.dot.cacheCanvas.cacheCtx.restore();
    }

    drawDots(dot) {
        TWEEN.update()
        this.stats.update();
        var cls = this


        if(!dot){
            this.ctx.clearRect(0, 0, this.width, this.height);
            this.dots.forEach(function (k, v) {

                if (cls.dots[v].move && (cls.ctrl_mode.mode_z || Math.abs(cls.dots[v].ctrl_v.c_z) >= 1)) {
                    cls.dots[v].ctrl_v.c_x += cls.ctrl_mode.mode_x
                    cls.dots[v].ctrl_v.c_x -= cls.dots[v].friction.x * (typeof x === 'undefined' ? 0 : x)
                    cls.dots[v].ctrl_v.c_y += cls.ctrl_mode.mode_y
                    cls.dots[v].ctrl_v.c_y -= cls.dots[v].friction.y * (typeof y === 'undefined' ? 0 : y)
                    cls.dots[v].ctrl_v.c_z += cls.ctrl_mode.mode_z

                    cls.dots[v].ctrl_v.c_z -= cls.dots[v].friction.z * (typeof z === 'undefined' ? 0 : z)
                    if (cls.dots[v].group === 'loading_line') {

                        var params = ''
                        if (!params) {
                            params = cls.dots[v].move_way.params
                        }
                        var left_x = -cls.canvas.width / 2
                        var right_x = cls.canvas.width / 2
                        var top_y = -cls.canvas.height / 2
                        var bottom_y = cls.canvas.height / 2
                        var tan = cls.canvas.height / cls.canvas.width


                        if (cls.dots[v].move_way.type === 'clockwise') {
                            // params.angle += cls.dots[v].ctrl_v.c_z
                            // cls.dots[v].center.x =  params.cross_radius*Math.cos((2*Math.PI/360 * (params.angle*cls.dots[v].cid)) )
                            // cls.dots[v].center.y =  params.cross_radius*Math.sin((2*Math.PI/360 * (params.angle*cls.dots[v].cid)) )
                            // cls.dots[v].ctrl_v.c_z *=  cls.dots[v].friction.z
                            // cls.dots[v].move_way.params.base_angle += cls.dots[v].ctrl_v.c_z
                            // cls.dots[v].center.x =
                            //     cls.dots[v].move_way.params.cross_radius*Math.cos((2*Math.PI/360 * cls.dots[v].move_way.params.base_angle) )
                            // cls.dots[v].center.y =
                            //     cls.dots[v].move_way.params.cross_radius*Math.sin((2*Math.PI/360 * cls.dots[v].move_way.params.base_angle) )
                            // cls.dots[v].ctrl_v.c_z *= 0.98
                        }
                        if (cls.dots[v].move_way.type === 'i_point') {
                            // console.log( cls.dots[v].ctrl_v.c_z)
                            cls.dots[v].move_way.params.base_angle += cls.dots[v].ctrl_v.c_z
                            cls.dots[v].center.x =
                                cls.dots[v].move_way.params.cross_radius * Math.cos((2 * Math.PI / 360 * cls.dots[v].move_way.params.base_angle))
                            cls.dots[v].center.y =
                                cls.dots[v].move_way.params.cross_radius * Math.sin((2 * Math.PI / 360 * cls.dots[v].move_way.params.base_angle))
                            cls.dots[v].ctrl_v.c_z *= cls.dots[v].friction.z
                        }
                        if (cls.dots[v].move_way.type === 'i_body') {

                            // params.angle += cls.dots[v].ctrl_v.c_z
                            // cls.dots[v].center.x =  (params.cross_radius-cls.dots[v].cid)*Math.cos((2*Math.PI/360 * (params.angle*cls.dots[v].cid)) )
                            // cls.dots[v].center.y =  (params.cross_radius-cls.dots[v].cid)*Math.sin((2*Math.PI/360 * (params.angle*cls.dots[v].cid)) )
                            // cls.dots[v].ctrl_v.c_z *= cls.dots[v].friction.z
                            // cls.dots[v].move_way.params.base_angle += cls.dots[v].ctrl_v.c_z
                            // cls.dots[v].center.x =
                            //     cls.dots[v].move_way.params.cross_radius*Math.cos((2*Math.PI/360 * cls.dots[v].move_way.params.base_angle) )
                            // cls.dots[v].center.y =
                            //     cls.dots[v].move_way.params.cross_radius*Math.sin((2*Math.PI/360 * cls.dots[v].move_way.params.base_angle) )
                            // cls.dots[v].ctrl_v.c_z *= 0.98
                        }
                    }

                }
                if (k.visible) {
                    cls.ctx.save()
                    if (k.cache) {
                        // console.log('k.cacheCanvas.cacheDom',k.cacheCanvas.cacheDom)

                        if (k.vp && k.scale) {
                            cls.ctx.translate(k.vp.vpX - k.radius * k.scale.scale_X, k.vp.vpY - k.radius * k.scale.scale_Y)

                        }
                        else if (k.vp && !k.scale) {
                            cls.ctx.translate(k.vp.vpX - k.radius, k.vp.vpY - k.radius)
                        }
                        if (k.scale) {
                            cls.ctx.scale(k.scale.scale_X, k.scale.scale_Y)

                        }
                        // console.log(111111111111111)
                        cls.ctx.drawImage(k.cacheCanvas.cacheDom, k.center.x, k.center.y);
                        // cls.ctx.drawImage(k.cacheCanvas.cacheDom ,k.center.x+cls.width/2,k.center.y+cls.height/2);

                    }
                    else {

                        if (k.vp && k.scale) {
                            cls.ctx.translate(k.vp.vpX, k.vp.vpY)

                        }
                        else if (k.vp && !k.scale) {
                            cls.ctx.translate(k.vp.vpX + k.radius, k.vp.vpY + k.radius)
                        }
                        // if (k.vp) {
                        //     cls.ctx.translate(k.vp.vpX, k.vp.vpY)
                        // }
                        if (k.style) {
                            if (k.style.RadialGradient) {
                                // cls.ctx.fillStyle = k.style.RadialGradient
                                cls.ctx.fillStyle = cls.initStyle(k).RadialGradient

                            }
                            if (k.style.shadowBlur) {
                                cls.ctx.shadowBlur = k.style.shadowBlur
                            }
                            if (k.style.shadowColor) {
                                cls.ctx.shadowBlur = k.style.shadowColor
                            }
                            if (k.style.strokeStyle) {
                                cls.ctx.strokeStyle = k.style.strokeStyle
                            }
                        }

                        if (k.scale) {
                            cls.ctx.scale(k.scale.scale_X, k.scale.scale_Y)
                        }
                        cls.ctx.beginPath()
                        cls.ctx.arc(k.center.x, k.center.y, k.radius, 0, 2 * Math.PI)
                        cls.ctx.closePath()
                        // cls.ctx.arc(10,10,5,0,2* Math.PI)
                        // cls.ctx.arc(20,20,10,0,2*Math.PI)
                        if (k.style.strokeStyle) {
                            cls.ctx.stroke()
                        }
                        cls.ctx.fill()
                    }

                }
                cls.ctx.restore()


            })

        }
        else{
            if (dot.visible) {
                cls.ctx.save()
                if (dot.cache) {
                    // console.log('dot.cacheCanvas.cacheDom',dot.cacheCanvas.cacheDom)

                    if (dot.vp && dot.scale) {
                        cls.ctx.translate(dot.vp.vpX - dot.radius * dot.scale.scale_X, dot.vp.vpY - dot.radius * dot.scale.scale_Y)

                    }
                    else if (dot.vp && !dot.scale) {
                        cls.ctx.translate(dot.vp.vpX - dot.radius, dot.vp.vpY - dot.radius)
                    }
                    if (dot.scale) {
                        cls.ctx.scale(dot.scale.scale_X, dot.scale.scale_Y)

                    }
                    // console.log(111111111111111)
                    cls.ctx.drawImage(dot.cacheCanvas.cacheDom, dot.center.x, dot.center.y);
                    // cls.ctx.drawImage(dot.cacheCanvas.cacheDom ,dot.center.x+cls.width/2,dot.center.y+cls.height/2);

                }
                else {

                    if (dot.vp && dot.scale) {
                        cls.ctx.translate(dot.vp.vpX, dot.vp.vpY)

                    }
                    else if (dot.vp && !dot.scale) {
                        cls.ctx.translate(dot.vp.vpX + dot.radius, dot.vp.vpY + dot.radius)
                    }
                    // if (dot.vp) {
                    //     cls.ctx.translate(dot.vp.vpX, dot.vp.vpY)
                    // }
                    if (dot.style) {
                        if (dot.style.RadialGradient) {
                            // cls.ctx.fillStyle = dot.style.RadialGradient
                            cls.ctx.fillStyle = cls.initStyle(k).RadialGradient

                        }
                        if (dot.style.shadowBlur) {
                            cls.ctx.shadowBlur = dot.style.shadowBlur
                        }
                        if (dot.style.shadowColor) {
                            cls.ctx.shadowBlur = dot.style.shadowColor
                        }
                        if (dot.style.strokeStyle) {
                            cls.ctx.strokeStyle = dot.style.strokeStyle
                        }
                    }

                    if (dot.scale) {
                        cls.ctx.scale(dot.scale.scale_X, dot.scale.scale_Y)
                    }
                    cls.ctx.beginPath()
                    cls.ctx.arc(dot.center.x, dot.center.y, dot.radius, 0, 2 * Math.PI)
                    cls.ctx.closePath()
                    // cls.ctx.arc(10,10,5,0,2* Math.PI)
                    // cls.ctx.arc(20,20,10,0,2*Math.PI)
                    if (dot.style.strokeStyle) {
                        cls.ctx.stroke()
                    }
                    cls.ctx.fill()
                }

            }
            cls.ctx.restore()
        }

        function zSort(a, b) {
            return (a.z - b.z);
        }
    }


    draw_alfa(v_this, awayfrom = 75, item_radius = 25) {

        v_this.angle += 0.03;
        v_this.hsl <= 360 ? v_this.hsl += 0.25 : v_this.hsl = 0;
        let s = -Math.sin(v_this.angle);
        let c = Math.cos(v_this.angle);
        console.log('draw_alfa')
        v_this.ctx.save();
        v_this.ctx.globalAlpha = 0.8;
        v_this.ctx.beginPath();
        v_this.ctx.fillStyle = 'hsla(' + v_this.hsl + ', 100%, 50%, 1)';
        v_this.ctx.arc(v_this.width / 2 + (s * awayfrom), v_this.height / 2 + (c * awayfrom), item_radius, 0, 2 * Math.PI);
        v_this.ctx.fill();
        v_this.ctx.restore();


    }


    initCtrl() {
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

    /*
     * 分析文字粒子
     * */
    getimgData(text,fontSize) {
        this.drawText(text,fontSize);
        var imgData = this.ctx.getImageData(0, 0, this.canvas.width, this.canvas.height);
        this.ctx.clearRect(0, 0, this.width, this.height);
        return imgData;
    }

    drawText(text,fontSize) {
        var vm = this
        this.ctx.save()
        this.ctx.font=fontSize+"px Droid Sans Fallback";
        this.ctx.fillStyle = "rgba(168,168,168,1)";
        this.ctx.textAlign = "center";
        this.ctx.textBaseline = "middle";

        var lastSubStrIndex= 0
        var lineWidth = 0
        var addHeight = 0
        for(let i=0; i<text.length; i++){

            lineWidth += vm.ctx.measureText(text[i]).width;
            // addHeight = vm.ctx.measureText(text[i]).height;
            if(lineWidth>vm.canvas.width-60 || text[i] === '^'){
                let currectText = text.substring(lastSubStrIndex,i)
                currectText=currectText.replace(/\^/,'')
                vm.ctx.fillText(currectText,vm.canvas.width / 2, vm.canvas.height / (3)+addHeight);
                // vm.ctx.fillText(currectText,20, 20+addHeight);
                addHeight += fontSize
                lineWidth=0;



                lastSubStrIndex=i;
            }
            if(i===text.length-1){  //绘制剩余部分

                let currectText = text.substring(lastSubStrIndex,i+1)
                currectText=currectText.replace(/\^/,'')
                // vm.ctx.fillText(currectText,20, 20+addHeight);
                vm.ctx.fillText(currectText,vm.canvas.width / 2, vm.canvas.height / (3)+addHeight);
            }
        }


        this.ctx.restore();
    }
    clearCtx(){
        this.ctx.clearRect(0, 0, this.width, this.height);
    }
}

export default DrawCanvas
