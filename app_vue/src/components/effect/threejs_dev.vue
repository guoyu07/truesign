<template>
    <div id="threejs_dev" style="overflow: hidden;text-align: center;background-color: black">
       
    </div>
</template>


<script>
//    var TWEEN =  require('tween')
    var THREE = require('three/build/three')
    var TrackballControls = require('three/examples/js/controls/TrackballControls')
    var CSS3DRenderer =  require('three/examples/js/renderers/CSS3DRenderer')

    var stats =  require('three/examples/js/libs/stats.min')
    var Projector =  require('three/examples/js/renderers/Projector')
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
                this.init_renderer()
                this.init_container()
                this.init_scene()
                this.init_camera()
                this.init_helper()
                this.init_controls()
                this.do_render()
                this.do_animate()

            },
            do_change_render(){
//                this.threejs_dev.renderer.setSize(500,500);


            },
            init_renderer(){
                this.threejs_dev.renderer = new THREE.CanvasRenderer();
                this.threejs_dev.renderer.setClearColor( 0x000000,1 );
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

                for ( var i = 0; i < 10; i++ ) {

                    var textureLoader = new THREE.TextureLoader();
                    var textureId = parseInt(Math.random()*10);
                    var texture = textureLoader.load(vm.textureList[textureId]);
//                    var texture = textureLoader.load(require('../../../static/img/baidu.png'));
                    var sec_particle = new THREE.Sprite( new THREE.SpriteMaterial( { map: texture } ) );
                    console.log('texture',texture)
//                    sec_particle.position.x = Math.round(Math.random() *vm.screenHeight* 1000)%200 +120;
//                    sec_particle.position.y =Math.round(Math.random() *vm.screenWidth* 10000)%100 +60;
//                    sec_particle.position.z = Math.random() * 3 - 30;
                    sec_particle.position.x = 0
                    sec_particle.position.y = 0
                    sec_particle.position.z = 400
//                    sec_particle.scale.x = sec_particle.scale.y = Math.round(Math.random() * 50)%5+10;
                    sec_particle.scale.x = sec_particle.scale.y = 66
                    sec_particle.sizeX = sec_particle.scale.x;
                    sec_particle.xScaleSpeed = -0.08;


                    sec_particle.speed = Math.round(Math.random()*10)/50;
                    vm.particles.push(sec_particle);
                    vm.threejs_dev.scene.add( sec_particle );
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
                // this.threejs_dev.camera.position.x += ( this.mouse_move.mouseX -this.threejs_dev.camera.position.x ) * 0.05;
                // this.threejs_dev.camera.position.y += ( -this.mouse_move.mouseY - this.threejs_dev.camera.position.y ) * 0.05;
                // this.threejs_dev.camera.lookAt( this.threejs_dev.scene.position );
                for(var i=0; i<this.particles.length; i++){
                    /**
                     * 速度控制
                     * @type {*}
                     */
                    this.particles[i].position.x+=this.particles[i].speed;
                    // particles[i].position.z+=0.1;
                    this.particles[i].position.y-=this.particles[i].speed+0.1;
                    this.particles[i].scale.x += this.particles[i].xScaleSpeed;
                    if(this.particles[i].scale.x <0.3 && this.particles[i].scale.x >0){
                        this.particles[i].scale.x=-0.3
                    }
                    if(this.particles[i].scale.x >-0.3&&this.particles[i].scale.x <0){
                        this.particles[i].scale.x=0.3
                    }
                    if(this.particles[i].scale.x <-this.particles[i].sizeX){
                        this.particles[i].xScaleSpeed = 0.08
                    }
                    if(this.particles[i].scale.x >=this.particles[i].sizeX){
                        this.particles[i].xScaleSpeed = -0.08
                    }

                    if(this.particles[i].position.y<-100||this.particles[i].position.x>50|this.particles[i].position.z>150){
                        this.particles[i].position.x = -Math.round(Math.random() *this.screenWidth* 1000)%(this.screenWidth);
                        this.particles[i].position.y = Math.round(Math.random() *this.screenHeight* 1000)%200 +120
                        this.particles[i].position.z = Math.random() * 5 - 30;
                        this.particles[i].speed=Math.round(Math.random()*10)/30;
                    }
                }
                this.threejs_dev.renderer.render( this.threejs_dev.scene, this.threejs_dev.camera );

            },
            do_animate() {
                requestAnimationFrame( this.do_animate );
                this.threejs_dev.controls.update();
                this.do_render();

            }


        }
    }
</script>
<style>
    #threejs_dev,#threejs_dev div{
        font-size: 0;
    }
</style>