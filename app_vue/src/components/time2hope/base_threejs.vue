<template>
  <div id="v-basethreejs" :style="{width:screenWidth+'px',height:screenHeight+'px'}">
  </div>
</template>
<script>
  import {mapGetters, mapActions} from 'vuex'
  import drawThreejs from '../../api/drawThreejs'

  const TWEEN = require('@tweenjs/tween.js');
  var THREE = require('three/build/three')
  var TrackballControls = require('three/examples/js/controls/TrackballControls')
  var CSS3DRenderer = require('three/examples/js/renderers/CSS3DRenderer')

  var stats = require('three/examples/js/libs/stats.min')
  var Projector = require('three/examples/js/renderers/Projector')
  var DAT = require('three/examples/js/libs/dat.gui.min')
  var CanvasRenderer = require('three/examples/js/renderers/CanvasRenderer')


  import THREEx_LaserBeam from '../../api/lib/threejs/THREEx_LaserBeam'
  import THREEx_LaserCooked from '../../api/lib/threejs/THREEx_LaserCooked'

  export default {
    data() {
      return {
        screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
        screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
        mouse_move: {
          mouseX: 0,
          mouseY: 0,
        },
        threejs_dev: {
          stats: '',
          gui: '',
          container: '',
          scene: '',
          camera: '',
          renderer: '',
          controls: '',
          objects: '',
          meshs: [],
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
        textureList: [
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
        particles: []
      }
    },
    computed: {
      // 使用对象展开运算符将 getters 混入 computed 对象中
      ...mapGetters([
        'sysinfo',
      ])
    },
    created() {
      var vm = this
      this.$root.eventHub.$on('screenWidth2screenHeight', function (data) {
//                console.log('screenWidth2screenHeight')
//                console.log(data)
        var width2height = data.split(",")
//                console.log(width2height)
        vm.screenWidth = parseInt(width2height[0])
        vm.screenHeight = parseInt(width2height[1])
        vm.do_resize()
      })
      var Options = function () {
        this.number = 1;
      };

      window.onload = function () {
        var options = new Options();
        var gui = new DAT.GUI();
        var controller = gui.add(options, 'number').min(0).max(10).step(1);

        controller.onChange(function (value) {
          console.log("onChange:" + value)
        });

        controller.onFinishChange(function (value) {
          console.log("onFinishChange" + value)
        });
      };
    },
    mounted() {
      this.start()
      window.addEventListener('resize', this.init_resize, false);

    },
    methods: {
      start() {
        var vm = this
        this.initStats()

        this.init_renderer()
        this.init_container()
        this.init_scene()
        this.init_camera()
//        this.init_helper()
        this.init_objects()
        this.init_controls()
        this.do_render()
        this.do_animate()
        //                this.initGui(this.threejs_dev.tween.opts,function (options) {
//                    console.log("userOpts", userOpts)
//                    vm.initTween();
//                    console.log('callback=>',options)
//                    console.log('callback=>',vm.threejs_dev.tween.opts)
//                })

//                this.initGui(this.threejs_dev.tween.opts)


      },
      initGui() {
        var Options = function () {
          this.color0 = "#ffae23"; // CSS string
          this.color1 = [0, 128, 255]; // RGB array
          this.color2 = [0, 128, 255, 0.3]; // RGB with alpha
          this.color3 = {h: 350, s: 0.9, v: 0.3}; // Hue, saturation, value
        };
        var options = new Options();
        var gui = new DAT.GUI();
        gui.addColor(options, 'color0');
        gui.addColor(options, 'color1');
        gui.addColor(options, 'color2');
        gui.addColor(options, 'color3');
      },
      initStats() {
        let old_stats = document.getElementById('threejs_stats')
        if (old_stats !== null) {
          document.body.removeChild(old_stats);
        }
        this.threejs_dev.stats = new Stats()
        this.threejs_dev.stats.setMode(0);
        this.threejs_dev.stats.domElement.id = 'threejs_stats'
        this.threejs_dev.stats.domElement.style.position = 'fixed';
        this.threejs_dev.stats.domElement.style.left = this.screenWidth - 100 + 'px';
        this.threejs_dev.stats.domElement.style.top = this.screenHeight - 100 + 'px';
//                this.threejs_dev.stats.domElement.style.width = '300px';
//                this.threejs_dev.stats.domElement.style.height = '100px';
        document.body.appendChild(this.threejs_dev.stats.domElement);
      },
      initTween() {
        var vm = this
        var update = function () {
          vm.threejs_dev.meshs['move_cube'].position.x = current.x;
        }
        var current = {x: -vm.threejs_dev.tween.opts.range};
        TWEEN.removeAll();
        var easing = TWEEN.Easing[vm.threejs_dev.tween.opts.easing.split('.')[0]][vm.threejs_dev.tween.opts.easing.split('.')[1]];
        var tweenHead = new TWEEN.Tween(current)
          .to({x: +vm.threejs_dev.tween.opts.range}, vm.threejs_dev.tween.opts.duration)
          .delay(vm.threejs_dev.tween.opts.delay)
          .easing(easing)
          .onUpdate(update);
        var tweenBack = new TWEEN.Tween(current)
          .to({x: -vm.threejs_dev.tween.opts.range}, vm.threejs_dev.tween.opts.duration)
          .delay(vm.threejs_dev.tween.opts.delay)
          .easing(easing)
          .onUpdate(update);
        tweenHead.chain(tweenBack);
        tweenBack.chain(tweenHead);
        tweenHead.start();
      },
      do_change_render() {
//                this.threejs_dev.renderer.setSize(500,500);


      },
      init_renderer() {
        this.threejs_dev.renderer = new THREE.WebGLRenderer();
        this.threejs_dev.renderer.setClearColor(0x000000, 1);
        this.threejs_dev.renderer.setPixelRatio(window.devicePixelRatio);
        this.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);

        this.threejs_dev.renderer.shadowMap.enabled = true
        this.threejs_dev.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        this.threejs_dev.renderer.gammaInput = true;
        this.threejs_dev.renderer.gammaOutput = true;

      },
      init_container() {
        var vm = this
        this.threejs_dev.container = document.createElement('div');
        document.getElementById("v-basethreejs").appendChild(this.threejs_dev.container);
        console.log('container_div', this.threejs_dev.container)
        this.threejs_dev.container.id = 'container_div'
        this.threejs_dev.container.style.fontSize = 0
        this.threejs_dev.container.appendChild(this.threejs_dev.renderer.domElement);
      },
      init_scene() {
        this.threejs_dev.scene = new THREE.Scene();
//                this.threejs_dev.scene.fog = new THREE.FogExp2( 0xcccccc, 0.002 );
      },
      init_camera() {
        this.threejs_dev.camera = new THREE.PerspectiveCamera(45, this.screenWidth / this.screenHeight, 0.1, 1000);
        this.threejs_dev.camera.position.z = 30;
      },
      init_helper() {
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
      init_controls() {
        this.threejs_dev.controls = new THREE.TrackballControls(this.threejs_dev.camera, this.threejs_dev.renderer.domElement);

        this.threejs_dev.controls.rotateSpeed = 1.0;
        this.threejs_dev.controls.zoomSpeed = 1.2;
        this.threejs_dev.controls.panSpeed = 0.8;
        this.threejs_dev.controls.noZoom = false;
        this.threejs_dev.controls.noPan = false;
        this.threejs_dev.controls.staticMoving = false;
        this.threejs_dev.controls.dynamicDampingFactor = 0.1;
        this.threejs_dev.controls.keys = [65, 83, 68];
//                this.threejs_dev.controls.addEventListener('mousemove', this.do_render);


      },
      init_objects() {
        var vm = this
        var scale = 1.5


        /*盒子*/
        let geometry = new THREE.CubeGeometry(1, 1, 1);
        let material = new THREE.MeshPhongMaterial({
          color: 0xaa8888,
          specular: 0xffffff,
          // shininess: 200,
          side: THREE.BackSide,
        });
        let object3d = new THREE.Mesh(geometry, material)
        object3d.scale.set(300, 150, 300)
//        this.threejs_dev.scene.add(object3d)

        /*障碍物*/
        for (var i = 0; i < 0; i++) {
          let geometry = new THREE.TorusGeometry(0.5 - 0.15, 0.15, 32, 16);
          //var geometry	= new THREE.SphereGeometry(0.5, 32, 16);
          let material = new THREE.MeshPhongMaterial({
            color: 0xffffff,
            specular: 0xffffff,
            shininess: 200,
          });
          let object3d = new THREE.Mesh(geometry, material);
          object3d.scale.set(20, 10, 20).multiplyScalar(1 / 2)
          this.threejs_dev.scene.add(object3d);
          object3d.position.x = (Math.random() - 0.5) * 10
          object3d.position.y = (Math.random() - 0.5) * 5
          object3d.position.z = (Math.random() - 0.5) * 10

          object3d.rotation.x = (Math.random() - 0.5) * Math.PI * 2
          object3d.rotation.y = (Math.random() - 0.5) * Math.PI * 2
          object3d.rotation.z = (Math.random() - 0.5) * Math.PI * 2
        }

        /*线光*/
        var line_object3d	= new THREE.Object3D()
        // generate the texture
        let line_canvas	= this.generateLaserBodyCanvas()
        var texture	= new THREE.Texture( line_canvas )
        texture.needsUpdate	= true;
        // do the material
//        var line_material	= new THREE.MeshBasicMaterial({
//          map		: texture,
//          blending	: THREE.AdditiveBlending,
//          color		: 0xffffff,
//          side		: THREE.DoubleSide,
//          depthWrite	: false,
//          transparent	: false
//        })
        var line_material	= new THREE.MeshBasicMaterial({

          blending	: THREE.AdditiveBlending,
          color		: 0xffffff,
          side		: THREE.DoubleSide,
          depthWrite	: false,
          transparent	: false
        })

//        var line_geometry	= new THREE.PlaneGeometry(1, 1)
//        var line_geometry = new THREE.CylinderBufferGeometry( 1, 1, 20, 32 );
        var line_geometry = new THREE.CylinderBufferGeometry( 0.1, 0.1, 16, 16 );

        let line_mesh	= new THREE.Mesh(line_geometry, line_material)


        line_mesh.rotation.z	= 1/2 * Math.PI

        this.threejs_dev.scene.add(line_mesh)
        this.addMeshToScene('line_object3d', line_object3d)

        var o_geometry = new THREE.TorusBufferGeometry(0.3, 0.1, 16, 300 );

        var o_material = new THREE.MeshBasicMaterial(
          {
            color: 0x33ff99,
            side:
            THREE.DoubleSide
          } );
        var o_mesh = new THREE.Mesh( o_geometry, o_material );
        o_mesh.rotation.y	= 1/2 * Math.PI
        this.threejs_dev.scene.add( o_mesh );
        this.addMeshToScene('o_mesh', o_mesh)


//        var laserCooked = new THREEx_LaserCooked(laserBeam)
//        this.addMeshToScene('laserCooked', laserCooked)

        /*灯光*/
//        let ambient = new THREE.AmbientLight( 0xffffff, 0.1 );
//        this.threejs_dev.scene.add( ambient );
//        let directionalLight = new THREE.DirectionalLight( 0xffffff, 1 );
//        directionalLight.position.set( 15, 40, 35 );
//
//        this.threejs_dev.scene.add( directionalLight );
//
//        let directionalLightHelper = new THREE.DirectionalLightHelper( directionalLight );
//        this.threejs_dev.scene.add( directionalLightHelper );
//        let shadowCameraHelper = new THREE.CameraHelper( directionalLight.shadow.camera );
//        this.threejs_dev.scene.add( shadowCameraHelper );
//        this.threejs_dev.scene.add( new THREE.AxisHelper( 10 ) );
//
//        let spotLight = new THREE.SpotLight( 0xffffff, 1 );
//        spotLight.position.set( 15, 40, 35 );
//        spotLight.angle = Math.PI / 4;
//        spotLight.penumbra = 0.05;
//        spotLight.decay = 2;
//        spotLight.distance = 200;
//        spotLight.castShadow = true;
//        spotLight.shadow.mapSize.width = 1024;
//        spotLight.shadow.mapSize.height = 1024;
//        spotLight.shadow.camera.near = 10;
//        spotLight.shadow.camera.far = 200;
//        this.threejs_dev.scene.add( spotLight );
//        let lightHelper = new THREE.SpotLightHelper( spotLight );
//        this.threejs_dev.scene.add( lightHelper );
//        shadowCameraHelper = new THREE.CameraHelper( spotLight.shadow.camera );
//        this.threejs_dev.scene.add( shadowCameraHelper );
//        this.threejs_dev.scene.add( new THREE.AxisHelper( 10 ) );
//
//
//        var light_material = new THREE.MeshPhongMaterial( { color: 0x808080, dithering: true } );
//        var light_geometry = new THREE.BoxGeometry( 1000, 1, 1000 );
//        var light_mesh = new THREE.Mesh( light_geometry, light_material );
//        light_mesh.position.set( 0, -10, 0 );
//        light_mesh.receiveShadow = true;
//        this.threejs_dev.scene.add( light_mesh );
//        light_material = new THREE.MeshPhongMaterial( { color: 0x4080ff, dithering: true } );
//        light_geometry = new THREE.BoxGeometry( 3, 1, 2 );
//        light_mesh = new THREE.Mesh( light_geometry, light_material );
//        light_mesh.position.set( 40, 2, 0 );
//        light_mesh.castShadow = true;
//        this.threejs_dev.scene.add( light_mesh );
//        var light = new THREE.HemisphereLight(0xfffff0, 0x101020, 0.2)
//        light.position.set(0.75, 1, 0.25)
//        this.threejs_dev.scene.add(light)

      },
      init_resize() {
        var vm = this

        this.threejs_dev.camera.aspect = this.screenWidth / this.screenHeight
        this.threejs_dev.camera.updateProjectionMatrix();

        this.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);


      },
      init_mouse_move(event) {
        this.mouse_move.mouseX = ( event.clientX - this.screenWidth / 2 );
        this.mouse_move.mouseY = ( event.clientY - this.screenHeight / 2 ) * 1;


      },
      do_render() {
        var delta = 10
//        console.log("this.threejs_dev.meshs['laserBeam'].object3d",this.threejs_dev.meshs['laserBeam'].object3d)
//        this.threejs_dev.meshs['line_object3d'].rotation.x += 0.4 * delta;
//        this.threejs_dev.meshs['line_object3d'].rotation.y += 0.5 * delta;
        this.threejs_dev.renderer.render(this.threejs_dev.scene, this.threejs_dev.camera);


      },
      do_animate() {
        requestAnimationFrame(this.do_animate);
        this.threejs_dev.controls.update();
        this.threejs_dev.stats.update()
        this.do_render();


      },
      do_resize() {
        this.initStats()
        this.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);
        this.init_camera()
        this.init_controls()
      },
      /*###### 扩展方法  #############*/
      addMeshToScene(name, mesh) {
        this.threejs_dev.meshs[name] = mesh
      },
      generateLaserBodyCanvas() {
        // init canvas
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        canvas.width = 1;
        canvas.height = 1;
        // set gradient
        var gradient = context.createLinearGradient(0, 0, canvas.width, canvas.height);
//        gradient.addColorStop(0, 'rgba(  0,  0,  0,0.1)');
//        gradient.addColorStop(0.1, 'rgba(160,160,160,0.3)');
//        gradient.addColorStop(0.5, 'rgba(255,255,255,0.5)');
//        gradient.addColorStop(0.9, 'rgba(160,160,160,0.3)');
//        gradient.addColorStop(1.0, 'rgba(  0,  0,  0,0.1)');
//        gradient.addColorStop(0, 'rgba(  255,  255,  255,0.1)');
//        gradient.addColorStop(0.1, 'rgba(255,255,255,0.3)');
//        gradient.addColorStop(0.5, 'rgba(255,255,255,0.5)');
//        gradient.addColorStop(0.9, 'rgba(255,255,255,0.3)');
        gradient.addColorStop(0, 'rgba(  0,  0, 0,1)');
        gradient.addColorStop(1.0, 'rgba(  255,  255, 255,1)');
        // fill the rectangle
        context.fillStyle = gradient;
        context.fillRect(0, 0, canvas.width, canvas.height);
        // return the just built canvas
        return canvas;
      }


    }
  }
</script>
<style lang="stylus" rel="stylesheet/stylus">
  #v-time2hope

    background-color transparent
</style>
