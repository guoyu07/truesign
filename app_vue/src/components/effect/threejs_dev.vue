<template>
    <div id="threejs_dev" style="overflow: hidden;text-align: center;background-color: white">
       
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
                screenHeight: document.body.clientHeight-26,  // 这里是给到了一个默认值 （这个很重要）
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
                }
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
                vm.screenHeight = parseInt(width2height[1])-26
            })
        },
        mounted(){
            this.init_renderer()
            this.init_container()
            this.init_scene()
            this.init_camera()
            this.init_helper()
            this.init_controls()
            this.do_render()
            this.do_animate()
            window.addEventListener( 'resize', this.init_resize, false );
//            document.addEventListener( 'mousemove', this.init_mouse_move, false );


        },
        methods:{
            do_change_render(){
//                this.threejs_dev.renderer.setSize(500,500);


            },
            init_renderer(){
                this.threejs_dev.renderer = new THREE.CanvasRenderer();
                this.threejs_dev.renderer.setClearColor( 0xffffff,1 );
//                this.threejs_dev.renderer.setPixelRatio( window.devicePixelRatio );
                this.threejs_dev.renderer.setSize( this.screenWidth, this.screenHeight );

            },
            init_container(){
                var vm = this
                this.threejs_dev.container = document.createElement( 'div' );
                document.getElementById("threejs_dev").appendChild( this.threejs_dev.container );
                this.threejs_dev.container.appendChild( this.threejs_dev.renderer.domElement );
            },
            init_scene(){
                this.threejs_dev.scene = new THREE.Scene();
            },
            init_camera(){
                this.threejs_dev.camera = new THREE.PerspectiveCamera( 55, this.screenWidth/this.screenHeight, 1, 10000 );
                this.threejs_dev.camera.position.z = 1000;
            },
            init_helper(){
                var vm = this
                var separation = 150;
                var amountx = 10;
                var amounty = 10;
                var PI2 = Math.PI * 2;
                var material = new THREE.SpriteCanvasMaterial( {

                    color: 0x0808080,
                    program: function ( context ) {

                        context.beginPath();
                        context.arc( 0, 0, 0.5, 0, PI2, true );
                        context.fill();

                    }

                } );
                for ( var ix = 0; ix < amountx; ix++ ) {

                    for ( var iy = 0; iy < amounty; iy++ ) {

                        var particle = new THREE.Sprite( material );
                        particle.position.x = ix * separation - ( ( amountx * separation ) / 2 );
                        particle.position.y = -153;
                        particle.position.z = iy * separation - ( ( amounty * separation ) / 2 );
                        particle.scale.x = particle.scale.y = 5;
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
                this.threejs_dev.controls.staticMoving = true;
                this.threejs_dev.controls.dynamicDampingFactor = 0.3;
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
<style></style>