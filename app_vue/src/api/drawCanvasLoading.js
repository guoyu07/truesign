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

        this.angle = 0.01
        this.hsl = 0

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
                each_touch_test = false,
                edge_touch_test = false,
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
                    x: 0.98,
                    y: 0.98,
                    z: 0.98
                },
                v = {
                    vx: 0,
                    vy: 0,
                    vz: 0
                },
                scale_fn_base = 0,
                scale_fn = 1 / (1 + -z / fl),
                scale = {
                    scale_X: scale_fn,
                    scale_Y: scale_fn
                },
                vp = {    //消失点
                    vpX: window.innerWidth / 2,
                    vpY: window.innerHeight / 2,
                },
                visible = true,
                colors = [
                    {key: 0, value: 'rgba(255,255,255,1)'},
                    {key: 0.2, value: 'rgba(0,255,255,1)'},
                    {key: 0.3, value: 'rgba(0,0,100,1)'},
                    {key: 1, value: 'rgba(0,0,0,0.1)'}
                ],
                move = true,
                move_way = false
            }) {
        // console.log(g)
        let dot = {}
        dot.cid = cid
        dot.group = group
        dot.start_time = start_time
        dot.end_time = end_time
        if (!radius) {
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
        if (scale_fn_base) {
            dot.scale_fn = scale_fn_base
        }
        dot.scale = scale = {
            scale_X: dot.scale_fn,
            scale_Y: dot.scale_fn
        }
        dot.vp = vp
        dot.radius = radius

        dot.init_center = init_center
        dot.center = {
            x: init_center.x,
            y: init_center.y,
            tmp_x: init_center.x,
            tmp_y: init_center.y,
        }


        style = this.initStyle(dot.center, radius, false, dot.colors)


        dot.style = style

        dot.each_touch_test = each_touch_test
        dot.edge_touch_test = edge_touch_test
        dot.z = z
        dot.g = g
        dot.friction = friction
        dot.v = v
        dot.move = move
        dot.move_way = move_way
        this.dots.push(dot)
    }

    test({a = 1}) {
        console.log('a->', a)

    }

    move_3D(x = 0, y = 0.2, z = 0) {

        var cls = this
        // console.log(x,y,z)
        this.dots.forEach(function (k, v) {
                if (cls.dots[v].move) {
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

                            // var fix_dot =
                            //     new TWEEN.Tween(cls.dots[v].center)
                            //         .to(
                            //             {
                            //                 x: -cls.canvas.width / 2 + cls.dots[v].cid *2,
                            //                 y: -cls.canvas.height / 2
                            //
                            //             }, cls.dots[v].cid*10)
                            //         .easing(TWEEN.Easing.Quadratic.In)
                            //         .onUpdate(function () {
                            //
                            //             // console.log(cls.dots[v].center)
                            //             // cls.dots[v].center.x = cls.dots[v].center.tween_x
                            //             // cls.dots[v].center.y = cls.dots[v].center.tween_y
                            //         })
                            // var top_right_point = new TWEEN.Tween(cls.dots[v].center)
                            //     .to(
                            //         {
                            //             // x: -cls.canvas.width/2,
                            //             x: cls.canvas.width/2-15,
                            //             y: -cls.canvas.height/2+8
                            //         }, 800)
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            // var bottom_center_right_point = new TWEEN.Tween(cls.dots[v].center)
                            //     .to(
                            //         {
                            //             // x: -cls.canvas.width/2,
                            //             y: cls.canvas.height/4
                            //         }, 1200)
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            // var bottom_right_point = new TWEEN.Tween(cls.dots[v].center)
                            //     .to(
                            //         {
                            //             // x: -cls.canvas.width/2,
                            //             y: cls.canvas.height/2-15
                            //         }, 600)
                            //     .delay(1000)
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //         var change_start = new TWEEN.Tween(cls.dots[v])
                            //             .to(
                            //                 {
                            //                     // x: -cls.canvas.width/2,
                            //                     radius: 4
                            //                 }, 800)
                            //             .easing(TWEEN.Easing.Linear.None)
                            //             .onUpdate(function () {
                            //                 cls.dots[v].colors = [
                            //                     {key: 0, value: 'transparent'},
                            //
                            //                 ]
                            //                 cls.ctrl_mode.mode_z = 0
                            //             })
                            //
                            //             .start()
                            //
                            //     })
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
                            //
                            // var bottom_center_left_point = new TWEEN.Tween(cls.dots[v].center)
                            //     .to(
                            //         {
                            //             // x: -cls.canvas.width/2,
                            //             y: cls.canvas.height/4
                            //         }, 600)
                            //     // .delay(parseInt(cls.dots[v].cid + '00') / 20)
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //         var change_start = new TWEEN.Tween(cls.dots[v])
                            //             .to(
                            //                 {
                            //                     // x: -cls.canvas.width/2,
                            //                     radius: 4,
                            //
                            //                 }, 800)
                            //             .easing(TWEEN.Easing.Linear.None)
                            //             .onUpdate(function () {
                            //
                            //             })
                            //
                            //             .start()
                            //     })
                            //
                            // var bottom_mid_height_left_left = new TWEEN.Tween((cls.dots[v].center))
                            //     .to(
                            //         {
                            //             x:-130,
                            //         },parseInt(cls.dots[v].cid*6)
                            //     )
                            //     .delay(1000)
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onStart(function() {
                            //         cls.dots[v].colors = [
                            //             {key: 0, value: 'rgba(142,142,142,80)'},
                            //
                            //         ]
                            //         cls.ctrl_mode.mode_z = 1
                            //     })
                            //     .onUpdate(function () {
                            //
                            //     })
                            // var bottom_mid_height_left_top = new TWEEN.Tween((cls.dots[v].center))
                            //     .to(
                            //         {
                            //             x:-100 ,
                            //             y:cls.canvas.height/6,
                            //         },500
                            //     )
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //     })
                            // var bottom_mid_height_left_bottom = new TWEEN.Tween((cls.dots[v].center))
                            //     .to(
                            //         {
                            //             x:-60,
                            //             y:cls.canvas.height/4+(cls.canvas.height/4-cls.canvas.height/6)
                            //         },500
                            //     )
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            // var bottom_mid_height_left_rigth = new TWEEN.Tween((cls.dots[v].center))
                            //     .to(
                            //         {
                            //             x:-30,
                            //             y:cls.canvas.height/4
                            //         },500
                            //     )
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            //
                            // var bottom_mid_height_right_left = new TWEEN.Tween((cls.dots[v].center))
                            //     .to(
                            //         {
                            //             x:30,
                            //         },300
                            //     )
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            // var bottom_mid_height_right_top = new TWEEN.Tween((cls.dots[v].center))
                            //     .to(
                            //         {
                            //             x:60,
                            //             y:cls.canvas.height/8,
                            //         },300
                            //     )
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            // var bottom_mid_height_right_bottom = new TWEEN.Tween((cls.dots[v].center))
                            //     .to(
                            //         {
                            //             x:100,
                            //             y:cls.canvas.height/4+(cls.canvas.height/4-cls.canvas.height/8)
                            //         },parseInt((params.count-cls.dots[v].cid)*6)
                            //     )
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            // var bottom_mid_height_right_right = new TWEEN.Tween((cls.dots[v].center))
                            //     .to(
                            //         {
                            //             x:130,
                            //             y:cls.canvas.height/4
                            //         },300
                            //     )
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            // var bottom_center_right_point_end = new TWEEN.Tween(cls.dots[v].center)
                            //     .to(
                            //         {
                            //             // x: -cls.canvas.width/2,
                            //             x: cls.canvas.width/2-15
                            //         }, 800)
                            //     .easing(TWEEN.Easing.Linear.None)
                            //     .onUpdate(function () {
                            //
                            //     })
                            if (params.percent >= 100) {
                                // fix_dot.chain(init_loading_line)
                                init_loading_line.chain(line_to_bottom)
                                line_to_bottom.chain(bottom_left_point)
                                bottom_left_point.chain(bottom_center_left_point)
                                bottom_center_left_point.chain(disvisible)
                                    init_loading_line.start()


                                // init_loading_line.chain(top_right_point)
                                // top_right_point.chain(bottom_center_right_point)

                                // bottom_center_right_point.chain(bottom_right_point)
                                // bottom_right_point.chain(bottom_left_point)
                                // line_to_bottom.chain(bottom_left_point)
                                // bottom_left_point.chain(bottom_center_left_point)
                                //
                                // bottom_center_left_point.chain(bottom_mid_height_left_left)
                                //
                                // bottom_mid_height_left_left.chain(bottom_mid_height_left_top)
                                // bottom_mid_height_left_top.chain(bottom_mid_height_left_bottom)
                                // bottom_mid_height_left_bottom.chain(bottom_mid_height_left_rigth)
                                // bottom_mid_height_left_rigth.chain(bottom_mid_height_right_left)
                                // bottom_mid_height_right_left.chain(bottom_mid_height_right_top)
                                // bottom_mid_height_right_top.chain(bottom_mid_height_right_bottom)
                                // bottom_mid_height_right_bottom.chain(bottom_mid_height_right_right)
                                // bottom_mid_height_right_right.chain(bottom_center_right_point_end)
                                // bottom_center_right_point_end.chain(bottom_right_point)

                            }
                            else {
                                init_loading_line.start()
                            }
                        }
                        if (cls.dots[v].move_way.type === 'beat_line') {


                            var start_dot =
                                new TWEEN.Tween(cls.dots[v].center)
                                    .to(
                                        {
                                            x: -cls.canvas.width / 2 + 2,
                                            y: cls.canvas.height / 4

                                        }, 800)
                                    .easing(TWEEN.Easing.Linear.None)
                                    .onStart(function () {
                                    })
                                    .onUpdate(function () {
                                    })
                            var fix_dot =
                                new TWEEN.Tween(cls.dots[v].center)
                                    .to(
                                        {
                                            x: -cls.canvas.width / 2 + ((cls.canvas.width / 2 - 130) / params.count) * cls.dots[v].cid,
                                            y: cls.canvas.height / 4

                                        }, 800)
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
                                    }, (cls.dots[v].cid)*6.5
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
                                    }, 800
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
                                    }, 800
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
                                    }, 800
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
                                    }, 800
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
                                    }, 800
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
                                    }, 800
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
                                    }, (params.count-cls.dots[v].cid)*6.5
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
                                    }, 800)
                                .easing(TWEEN.Easing.Linear.None)

                                .onUpdate(function () {
                                    cls.dots[v].visible = true
                                })
                            var v_disvisible = new TWEEN.Tween(cls.dots[v].center)
                                .to(
                                    {
                                        // x: -cls.canvas.width/2,
                                        x: cls.canvas.width / 2 - 15
                                    }, 800)
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

                    else {

                        // cls.dots[v].ctrl_v.c_x = cls.ctrl_mode.mode_x
                        // cls.dots[v].ctrl_v.c_y = cls.ctrl_mode.mode_y
                        // cls.dots[v].ctrl_v.c_z = cls.ctrl_mode.mode_z

                        cls.dots[v].init_center.x += cls.dots[v].ctrl_v.c_x
                        cls.dots[v].init_center.y += cls.dots[v].ctrl_v.c_y

                        cls.dots[v].z += cls.dots[v].ctrl_v.c_z

                        cls.dots[v].scale_fn = 1 / (1 + -cls.dots[v].z / cls.dots[v].fl)

                        if (cls.dots[v].init_center.y < -(cls.height / 1.5)) {
                            cls.dots[v].init_center.y = cls.height / 1.5;
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


                        cls.dots[v].center.x = cls.dots[v].init_center.x * cls.dots[v].scale_fn;
                        cls.dots[v].center.y = cls.dots[v].init_center.y * cls.dots[v].scale_fn;
                        // console.log(cls.dots[v].scale)
                        // console.log(cls.dots[v].center)

                        cls.dots[v].ctrl_v.c_x *= cls.dots[v].friction.x
                        cls.dots[v].ctrl_v.c_y *= cls.dots[v].friction.y
                        cls.dots[v].ctrl_v.c_z *= cls.dots[v].friction.z
                    }


                }
                if (cls.dots[v].move_way.type === 'test') {
                    var coords = {center_x: cls.dots[v].center.x, center_y: cls.dots[v].center.y}; // Start at (0, 0)
                    var tween = new TWEEN.Tween(coords)
                        .to({center_x: cls.canvas.width / 2, center_y: cls.canvas.height / 2}, 3000)
                        .easing(TWEEN.Easing.Linear.None)
                        .onUpdate(function () {
                            cls.dots[v].center.x = coords.center_x
                            cls.dots[v].center.y = coords.center_y
                        })
                        .start()

                }


            }
        );

    }

    reckon_millisecond(cid, count, center_data, boundary) {
        if (center_data.x < 0 && center_data.x > boundary[3]) {
            return parseInt(cid + '00')
        }
        else if (center_data.x > 0 && center_data.x < boundary[1]) {
            return parseInt(count + 1 - cid + '00')
        }

    }

    move_line(type = 'left_right_center') {
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

    initStyle(center, radius, add_cmd = false,
              colors) {
        let style = {}
        let per_style = this.ctx.createRadialGradient(center.x, center.y, radius * 0.1, center.x, center.y, radius)
        // // per_style.addColorStop(0,this.randomColor())
        // // per_style.addColorStop(1,"white")
        for (var item_style of colors) {
            per_style.addColorStop(item_style.key, item_style.value)
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

    drawDots() {
        TWEEN.update()

        // console.log(this.dots)
        this.dots.sort(zSort)
        // console.log(this.dots)
        this.ctx.clearRect(0, 0, this.width, this.height);


        var canvasM = this
        var cls = this
        this.dots.forEach(function (k, v) {

            // if(cls.dots[v].move_way.type === 'loading_line'){
            //     if(cls.dots[v].move_way.params.percent >= 100){
            //         cls.move_3D()
            //     }
            // }
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

            canvasM.ctx.save()

            if (k.visible) {
                if (k.vp) {
                    canvasM.ctx.translate(k.vp.vpX, k.vp.vpY)
                }
                if (k.style) {
                    if (k.style.RadialGradient) {
                        // canvasM.ctx.fillStyle = k.style.RadialGradient
                        canvasM.ctx.fillStyle = canvasM.initStyle(k.center, k.radius, false, k.colors).RadialGradient

                    }
                    if (k.style.shadowBlur) {
                        canvasM.ctx.shadowBlur = k.style.shadowBlur
                    }
                    if (k.style.shadowColor) {
                        canvasM.ctx.shadowBlur = k.style.shadowColor
                    }
                    if (k.style.strokeStyle) {
                        canvasM.ctx.strokeStyle = k.style.strokeStyle
                    }
                }

                if (k.scale) {
                    canvasM.ctx.scale(k.scale.scale_X, k.scale.scale_Y)
                }
                canvasM.ctx.beginPath()
                canvasM.ctx.arc(k.center.x, k.center.y, k.radius, 0, 2 * Math.PI)
                canvasM.ctx.closePath()
                // canvasM.ctx.arc(10,10,5,0,2* Math.PI)
                // canvasM.ctx.arc(20,20,10,0,2*Math.PI)
                if (k.style.strokeStyle) {
                    canvasM.ctx.stroke()
                }
                canvasM.ctx.fill()
            }
            canvasM.ctx.restore()


        })

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

    draw({x = 1, y = 2}) {
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
