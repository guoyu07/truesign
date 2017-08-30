<template>
    <div>
        <div id="threejs_dev" style="overflow: hidden;text-align: center;background-color: #ffffff">

        </div>
        <div id="video_div">
            <video id="video" autoplay loop webkit-playsinline style="display:none">
                <source src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo_video/sintel.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                <source src="http://truesign-app.oss-cn-beijing.aliyuncs.com/demo_video/sintel.ogv" type='video/ogg; codecs="theora, vorbis"'>
            </video>
        </div>
    </div>
</template>


<script>
//    var TWEEN =  require('tween')
    var THREE = require('three/build/three')
    var TrackballControls = require('three/examples/js/controls/TrackballControls')
    var CSS3DRenderer =  require('three/examples/js/renderers/CSS3DRenderer')

    var stats =  require('three/examples/js/libs/stats.min')
    var Projector =  require('three/examples/js/renderers/Projector')
    var dat =  require('three/examples/js/libs/dat.gui.min')
    var CanvasRenderer =  require('three/examples/js/renderers/CanvasRenderer')
    export default {
        data () {
            return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
                mouse_move:{
                    mouseX:0,
                    mouseY:0,
                },
                threejs_dev:{
                    stats:'',
                    container:'',
                    scene:'',
                    camera:'',
                    renderer:'',
                    controls:'',
                    objects:''
                },
                textureList:[
                    require("../../../static/img/flowers/flower-1.png"),
                    require("../../../static/img/flowers/flower-2.png"),
                    require("../../../static/img/flowers/flower-3.png"),
                    require("../../../static/img/flowers/flower-4.png"),
                    require("../../../static/img/flowers/flower-5.png"),
                    require("../../../static/img/flowers/flower-6.png"),
                    require("../../../static/img/flowers/flower-7.png"),
                    require("../../../static/img/flowers/flower-8.png"),
                    require("../../../static/img/flowers/flower-9.png"),
                    require("../../../static/img/flowers/flower-10.png"),

                ],
                particles:[]
            }
        },
        created(){
            var vm = this
            this.$root.eventHub.$on('screenWidth2screenHeight',function (data) {
//                console.log('screenWidth2screenHeight')
//                console.log(data)
                var width2height = data.split(",")
//                console.log(width2height)
                vm.screenWidth = parseInt(width2height[0])
                vm.screenHeight = parseInt(width2height[1])
            })
        },
        mounted(){
            this.start()
            window.addEventListener( 'resize', this.init_resize, false );

        },
        methods:{
            start(){
                this.initStats()
                this.init_renderer()
                this.init_container()
                this.init_scene()
                this.init_camera()
                this.init_helper()
                this.init_objects()
                this.init_controls()
                this.do_render()
                this.do_animate()


            },
            initStats(){
                let old_stats = document.getElementById('threejs_stats')
                if(old_stats !== null){
                    document.body.removeChild(old_stats);
                }
                this.threejs_dev.stats = new Stats()
                this.threejs_dev.stats.setMode(0);
                this.threejs_dev.stats.domElement.id='threejs_stats'
                this.threejs_dev.stats.domElement.style.position = 'fixed';
                this.threejs_dev.stats.domElement.style.left = this.screenWidth  - 100 + 'px';
                this.threejs_dev.stats.domElement.style.top = '0px';
//                this.threejs_dev.stats.domElement.style.width = '300px';
//                this.threejs_dev.stats.domElement.style.height = '100px';
                document.body.appendChild(this.threejs_dev.stats.domElement);
            },
            do_change_render(){
//                this.threejs_dev.renderer.setSize(500,500);


            },
            init_renderer(){
                this.threejs_dev.renderer = new THREE.CanvasRenderer();
                this.threejs_dev.renderer.setClearColor( 0xffffff,1 );
                this.threejs_dev.renderer.setPixelRatio( window.devicePixelRatio );
                this.threejs_dev.renderer.setSize( this.screenWidth,this.screenHeight);

            },
            init_container(){
                var vm = this
                this.threejs_dev.container = document.createElement( 'div' );
//                this.threejs_dev.container.style.width = this.screenWidth+'px'
//                this.threejs_dev.container.style.height = this.screenHeight+'px'
                document.getElementById("threejs_dev").appendChild( this.threejs_dev.container );
                this.threejs_dev.container.appendChild( this.threejs_dev.renderer.domElement );
            },
            init_scene(){
                this.threejs_dev.scene = new THREE.Scene();
//                this.threejs_dev.scene.fog = new THREE.FogExp2( 0xcccccc, 0.002 );
            },
            init_camera(){
                this.threejs_dev.camera = new THREE.PerspectiveCamera( 55, this.screenWidth/this.screenHeight, 1, 10000 );
                this.threejs_dev.camera.position.z = 1000;
            },
            init_helper(){
                var vm = this
                var separation = 50;
                var amountx = 10;
                var amounty = 10;
                var PI2 = Math.PI * 2;
                var material = new THREE.SpriteCanvasMaterial( {

//                    color: 0x000000,
                    color: 0xE72D2D,
                    program: function ( context ) {

                        context.beginPath();
                        context.arc( 0, 0,1, 0, PI2, true );
                        context.fill();

                    }

                } );
                for ( var ix = 0; ix < amountx; ix++ ) {

                    for ( var iy = 0; iy < amounty; iy++ ) {

                        var particle = new THREE.Sprite( material );
                        particle.position.x = ix * separation - ( ( amountx * separation ) / 2 );
                        particle.position.y = -153;
                        particle.position.z = iy * separation - ( ( amounty * separation ) / 2 );
                        particle.scale.x = particle.scale.y = 6;
                        vm.threejs_dev.scene.add( particle );

                    }

                }


            },
            init_controls(){
                // this.threejs_dev.controls = new THREE.TrackballControls(this.threejs_dev.camera, this.threejs_dev.renderer.domElement);
                // this.threejs_dev.controls.rotateSpeed = 1;
                // this.threejs_dev.controls.minDistance = 500;
                // this.threejs_dev.controls.maxDistance = 6000;
                // this.threejs_dev.controls.addEventListener('change', this.do_render);



                this.threejs_dev.controls = new THREE.TrackballControls( this.threejs_dev.camera );
                this.threejs_dev.controls.rotateSpeed = 1.0;
                this.threejs_dev.controls.zoomSpeed = 1.2;
                this.threejs_dev.controls.panSpeed = 0.8;
                this.threejs_dev.controls.noZoom = false;
                this.threejs_dev.controls.noPan = false;
                this.threejs_dev.controls.staticMoving = false;
                this.threejs_dev.controls.dynamicDampingFactor = 0.1;
                this.threejs_dev.controls.keys = [ 65, 83, 68 ];
                this.threejs_dev.controls.addEventListener( 'change', this.do_render );




            },
            init_objects(){
                var video = document.getElementById( 'video' );

                //

                var image = document.createElement( 'canvas' );
                image.width = 480;
                image.height = 204;

                var imageContext = image.getContext( '2d' );
                imageContext.fillStyle = '#000000';
                imageContext.fillRect( 0, 0, 480, 204 );
                imageContext.drawImage( video, 0, 0 );
                var texture = new THREE.Texture( image );

                var material = new THREE.MeshBasicMaterial( { map: texture, overdraw: 0.5 } );

//                var imageReflection = document.createElement( 'canvas' );
//                imageReflection.width = 480;
//                imageReflection.height = 204;

//                var imageReflectionContext = imageReflection.getContext( '2d' );
//                imageReflectionContext.fillStyle = '#000000';
//                imageReflectionContext.fillRect( 0, 0, 480, 204 );
//
//                var imageReflectionGradient = imageReflectionContext.createLinearGradient( 0, 0, 0, 204 );
//                imageReflectionGradient.addColorStop( 0.2, 'rgba(240, 240, 240, 1)' );
//                imageReflectionGradient.addColorStop( 1, 'rgba(240, 240, 240, 0.8)' );
//
//                var textureReflection = new THREE.Texture( imageReflection );
//
//                var materialReflection = new THREE.MeshBasicMaterial( { map: textureReflection, side: THREE.BackSide, overdraw: 0.5 } );

                //

                var plane = new THREE.PlaneGeometry( 480, 204, 4, 4 );

                var mesh = new THREE.Mesh( plane, material );
                mesh.scale.x = mesh.scale.y = mesh.scale.z = 1.5;
                this.threejs_dev.scene.add(mesh);

//                mesh = new THREE.Mesh( plane, materialReflection );
//                mesh.position.y = -306;
//                mesh.rotation.x = -Math.PI;
//                mesh.scale.x = mesh.scale.y = mesh.scale.z = 1.5;
//                this.threejs_dev.scene.add( mesh );
            },
            init_resize(){
                var vm = this

                this.threejs_dev.camera.aspect = this.screenWidth/this.screenHeight
                this.threejs_dev.camera.updateProjectionMatrix();

                this.threejs_dev.renderer.setSize(this.screenWidth,this.screenHeight);


            },
            init_mouse_move(event){
                this.mouse_move.mouseX = ( event.clientX - this.screenWidth/2 );
                this.mouse_move.mouseY = ( event.clientY - this.screenHeight/2 ) * 1;


            },
            do_render(){


                this.threejs_dev.renderer.render( this.threejs_dev.scene, this.threejs_dev.camera );

            },
            do_animate() {
                requestAnimationFrame( this.do_animate );
                this.threejs_dev.controls.update();
                this.threejs_dev.stats.update()
                this.do_render();



            }


        }
    }
</script>
<style>
    #threejs_dev,#threejs_dev div{
        font-size: 0;
    }
    #video_div {
        font-family: Monospace;
        background-color: #f0f0f0 !important;
        margin: 0px !important;
        overflow: visible;
    }
</style>