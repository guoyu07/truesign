<template>
    <div>
        <div id="threejs_dev" style="overflow: hidden;text-align: center;background-color: #ffffff">

        </div>
        <!--<div id="video_div">-->
            <!--<video id="video" autoplay loop webkit-playsinline style="display:none">-->
                <!--<source src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo_video/sintel.mp4"-->
                        <!--type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>-->
                <!--<source src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo_video/sintel.ogv"-->
                        <!--type='video/ogg; codecs="theora, vorbis"'>-->
            <!--</video>-->
        <!--</div>-->
    </div>
</template>


<script>
    const TWEEN = require('@tweenjs/tween.js');
    var THREE = require('three/build/three')
    var TrackballControls = require('three/examples/js/controls/TrackballControls')
    var CSS3DRenderer = require('three/examples/js/renderers/CSS3DRenderer')

    var stats = require('three/examples/js/libs/stats.min')
    var Projector = require('three/examples/js/renderers/Projector')
    var DAT = require('three/examples/js/libs/dat.gui.min')
    var CanvasRenderer = require('three/examples/js/renderers/CanvasRenderer')

//    import Screenshot from '../../../static/js/3dparty/threeX/THREEx.screenshot.js'
    import KeyboardSate from '../../../static/js/3dparty/threeX/THREEx.KeyboardState.js'
    export default {
        data () {
            return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                mouse_move: {
                    mouseX: 0,
                    mouseY: 0,
                },
                threejs_dev: {
                    stats: '',
                    gui:'',
                    container: '',
                    scene: '',
                    camera: '',
                    renderer: '',
                    controls: '',
                    objects: '',
                    meshs: {},
                    tween: {
                        obj: {},
                        opts: {
                            range: 800,
                            duration: 2500,
                            delay: 200,
                            easing: 'Elastic.EaseInOut',
                            position: {
                                x: 0,
                                y: 0,
                                z: 0,
                            },
                            scale: {
                                x: 1,
                                y: 1,
                                z: 1
                            }
                        }
                    }
                },
                particles: []
            }
        },
        created(){
            var vm = this
            this.$root.eventHub.$on('screenWidth2screenHeight', function (data) {
                var width2height = data.split(",")
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])
            })
            var keyboard = new KeyboardSate();
            if( keyboard.pressed("shift+H") )     displayHelp();
        },
        mounted(){
            this.start()
            window.addEventListener('resize', this.init_resize, false);

        },
        methods: {
            start(){
                var vm = this
                this.initStats()

                this.init_renderer()
                this.init_container()
                this.init_scene()
                this.init_camera()
                this.init_helper()
                this.init_objects()

                this.initGui(this.threejs_dev.tween.opts,function (options) {
                    vm.initTween();
                })

//                this.initGui(this.threejs_dev.tween.opts)
                this.init_controls()
                this.do_render()
                this.do_animate()
            },
            initGui(options, callback){
                var easings	= {};
                Object.keys(TWEEN.Easing).forEach(function(family){
                    Object.keys(TWEEN.Easing[family]).forEach(function(direction){
                        var name	= family+'.'+direction;
                        easings[name]	= name;
                    });
                });
                var change	= function(){
                    callback(options)
                }
                this.threejs_dev.gui = new DAT.GUI({ height	: 4 * 32 - 1 });
                this.threejs_dev.gui.add(options, 'range').name('Range coordinate').min(64).max(1280).onChange(change);
                this.threejs_dev.gui.add(options, 'duration').name('Duration (ms)').min(100).max(4000).onChange(change);
                this.threejs_dev.gui.add(options, 'delay').name('Delay (ms)').min(0).max(1000).onChange(change);
                this.threejs_dev.gui.add(options, 'easing').name('Easing Curve').options(easings).onChange(change);
            },
            initStats(){
                let old_stats = document.getElementById('threejs_stats')
                if (old_stats !== null) {
                    document.body.removeChild(old_stats);
                }
                this.threejs_dev.stats = new Stats()
                this.threejs_dev.stats.setMode(0);
                this.threejs_dev.stats.domElement.id = 'threejs_stats'
                this.threejs_dev.stats.domElement.style.position = 'fixed';
                this.threejs_dev.stats.domElement.style.left = this.screenWidth - 100 + 'px';
                this.threejs_dev.stats.domElement.style.top = this.screenHeight-100+'px';
//                this.threejs_dev.stats.domElement.style.width = '300px';
//                this.threejs_dev.stats.domElement.style.height = '100px';
                document.body.appendChild(this.threejs_dev.stats.domElement);
            },
            initTween(){
//                console.log('initTween')
                var vm = this
                var update	= function(){
                    vm.threejs_dev.meshs['cube_move'].position.x = current.x;

                }
                var current	= { x: -vm.threejs_dev.tween.opts.range };
//                console.log('vm.threejs_dev.tween.opts.range',vm.threejs_dev.tween.opts.range)
//                vm.threejs_dev.meshs['cube_move'].position.x = vm.threejs_dev.tween.opts.range
//                console.log(vm.threejs_dev.meshs['cube_move'])
                TWEEN.removeAll();

                var easing	= TWEEN.Easing[vm.threejs_dev.tween.opts.easing.split('.')[0]][vm.threejs_dev.tween.opts.easing.split('.')[1]];
                console.log(vm.threejs_dev.tween.opts.easing,easing)
                var tweenHead	= new TWEEN.Tween(current)
                    .to({x: +vm.threejs_dev.tween.opts.range}, vm.threejs_dev.tween.opts.duration)
                    .delay(vm.threejs_dev.tween.opts.delay)
                    .easing(easing)
                    .onUpdate(update);
                var tweenBack	= new TWEEN.Tween(current)
                    .to({x: -vm.threejs_dev.tween.opts.range}, vm.threejs_dev.tween.opts.duration)
                    .delay(vm.threejs_dev.tween.opts.delay)
                    .easing(easing)
                    .onUpdate(update);
                tweenHead.chain(tweenBack);
                tweenBack.chain(tweenHead);
                tweenHead.start();
            },
            do_change_render(){
//                this.threejs_dev.renderer.setSize(500,500);


            },
            init_renderer(){
                this.threejs_dev.renderer = new THREE.CanvasRenderer();
                this.threejs_dev.renderer.setClearColor(0xffffff, 1);
                this.threejs_dev.renderer.setPixelRatio(window.devicePixelRatio);
                this.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);

            },
            init_container(){
                var vm = this
                this.threejs_dev.container = document.createElement('div');
//                this.threejs_dev.container.style.width = this.screenWidth+'px'
//                this.threejs_dev.container.style.height = this.screenHeight+'px'
                document.getElementById("threejs_dev").appendChild(this.threejs_dev.container);
                this.threejs_dev.container.appendChild(this.threejs_dev.renderer.domElement);
            },
            init_scene(){
                this.threejs_dev.scene = new THREE.Scene();
//                this.threejs_dev.scene.fog = new THREE.FogExp2( 0xcccccc, 0.002 );
            },
            init_camera(){
                this.threejs_dev.camera = new THREE.PerspectiveCamera(55, this.screenWidth / this.screenHeight, 1, 100000);
                this.threejs_dev.camera.position.z = 1000;
            },
            init_helper(){
                var vm = this
                var separation = 50;
                var amountx = 10;
                var amounty = 10;
                var PI2 = Math.PI * 2;
                var material = new THREE.SpriteCanvasMaterial({

//                    color: 0x000000,
                    color: 0xE72D2D,
                    program: function (context) {

                        context.beginPath();
                        context.arc(0, 0, 1, 0, PI2, true);
                        context.fill();

                    }

                });
                for (var ix = 0; ix < amountx; ix++) {

                    for (var iy = 0; iy < amounty; iy++) {

                        var particle = new THREE.Sprite(material);
                        particle.position.x = ix * separation - ( ( amountx * separation ) / 2 );
                        particle.position.y = -153;
                        particle.position.z = iy * separation - ( ( amounty * separation ) / 2 );
                        particle.scale.x = particle.scale.y = 6;
                        vm.threejs_dev.scene.add(particle);

                    }

                }




            },
            init_controls(){
                // this.threejs_dev.controls = new THREE.TrackballControls(this.threejs_dev.camera, this.threejs_dev.renderer.domElement);
                // this.threejs_dev.controls.rotateSpeed = 1;
                // this.threejs_dev.controls.minDistance = 500;
                // this.threejs_dev.controls.maxDistance = 6000;
                // this.threejs_dev.controls.addEventListener('change', this.do_render);


                this.threejs_dev.controls = new THREE.TrackballControls(this.threejs_dev.camera, this.threejs_dev.renderer.domElement);
//                this.threejs_dev.controls.rotateSpeed = 1.0;
//                this.threejs_dev.controls.zoomSpeed = 1.2;
//                this.threejs_dev.controls.panSpeed = 0.8;
//                this.threejs_dev.controls.noZoom = false;
//                this.threejs_dev.controls.noPan = false;
//                this.threejs_dev.controls.staticMoving = false;
//                this.threejs_dev.controls.dynamicDampingFactor = 0.1;
//                this.threejs_dev.controls.keys = [65, 83, 68];
//                this.threejs_dev.controls.addEventListener('mousemove', this.do_render);


            },
            init_objects(){

                var cube_move = new THREE.Mesh(new THREE.SphereGeometry(80, 48, 32), new THREE.MeshNormalMaterial());
                cube_move.position.x = -450;
                cube_move.position.y = 0;
                cube_move.position.z = 0;
                this.addMeshToScene('cube_move', cube_move);

            },
            init_resize(){
                var vm = this

                this.threejs_dev.camera.aspect = this.screenWidth / this.screenHeight
                this.threejs_dev.camera.updateProjectionMatrix();

                this.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);


            },
            init_mouse_move(event){
                this.mouse_move.mouseX = ( event.clientX - this.screenWidth / 2 );
                this.mouse_move.mouseY = ( event.clientY - this.screenHeight / 2 ) * 1;


            },
            do_render(){
                this.threejs_dev.renderer.render(this.threejs_dev.scene, this.threejs_dev.camera);
            },
            do_animate() {
                requestAnimationFrame(this.do_animate);
                this.threejs_dev.controls.update();
                this.threejs_dev.stats.update()
                TWEEN.update();
                this.do_render();


            },

            /*###### 扩展方法  #############*/
            addMeshToScene(name, mesh){
                this.threejs_dev.meshs[name] = mesh
                this.threejs_dev.scene.add(mesh)
            }


        }
    }
</script>
<style>
    #threejs_dev, #threejs_dev div {
        font-size: 0;
    }
    select{
        color: black;
    }
    #video_div {
        font-family: Monospace;
        background-color: #f0f0f0 !important;
        margin: 0px !important;
        overflow: visible;
    }
</style>