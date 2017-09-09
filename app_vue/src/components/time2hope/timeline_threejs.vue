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

  var BinaryLoader = require('three/examples/js/loaders/BinaryLoader')


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
        this.init_helper()
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
        this.threejs_dev.scene.fog = new THREE.FogExp2(0xcccccc, 0.002);
      },
      init_camera() {
        this.threejs_dev.camera = new THREE.PerspectiveCamera(55, this.screenWidth / this.screenHeight, 0.1, 10000);
        this.threejs_dev.camera.position.set(-35, 5, -15);
        this.threejs_dev.camera.lookAt(new THREE.Vector3(0, 0, -45))
      },

      init_controls() {
        this.threejs_dev.controls = new THREE.TrackballControls(this.threejs_dev.camera, this.threejs_dev.renderer.domElement);

        this.threejs_dev.controls.rotateSpeed = 0.5;
        this.threejs_dev.controls.zoomSpeed = 0.8;
        this.threejs_dev.controls.panSpeed = 0.2;
        this.threejs_dev.controls.noZoom = false;
        this.threejs_dev.controls.noPan = false;
        this.threejs_dev.controls.staticMoving = false;
        this.threejs_dev.controls.dynamicDampingFactor = 0.1;
        this.threejs_dev.controls.keys = [65, 83, 68];
        this.threejs_dev.controls.target = new THREE.Vector3(0, 0, -15);
//                this.threejs_dev.controls.addEventListener('mousemove', this.do_render);


      },

      init_helper() {
        var vm = this
        /*环境光*/
        var ambient = new THREE.AmbientLight(0xe8e2e2, 0.4);
        this.threejs_dev.scene.add(ambient);

        var floor_material = new THREE.MeshPhongMaterial({color: 0x666666, dithering: true});
        var floor_geometry = new THREE.BoxGeometry(1000, 1, 1000);
        var floor_mesh = new THREE.Mesh(floor_geometry, floor_material);
        floor_mesh.position.set(0, -100, 0);
        floor_mesh.receiveShadow = true;
        this.threejs_dev.scene.add(floor_mesh);


        let spotLight = new THREE.SpotLight(0xffffff, 1);
        spotLight.position.set(50, 50, 0);
        spotLight.angle = Math.PI / 6;
        spotLight.penumbra = 0.05;
        spotLight.decay = 2;
        spotLight.distance = 200;
        spotLight.castShadow = true;
        spotLight.shadow.mapSize.width = 1024;
        spotLight.shadow.mapSize.height = 1024;
        spotLight.shadow.camera.near = 10;
        spotLight.shadow.camera.far = 200;
        this.threejs_dev.scene.add(spotLight);
        let lightHelper = new THREE.SpotLightHelper(spotLight);
        this.threejs_dev.scene.add(lightHelper);
        let shadowCameraHelper = new THREE.CameraHelper(spotLight.shadow.camera);
        this.threejs_dev.scene.add(shadowCameraHelper);
        this.threejs_dev.scene.add(new THREE.AxisHelper(10));

        let directionalLight = new THREE.DirectionalLight(0xffffff, 50);
        directionalLight.position.set(0, 0, 30);

        this.threejs_dev.scene.add(directionalLight);

        let directionalLightHelper = new THREE.DirectionalLightHelper(directionalLight);
        this.threejs_dev.scene.add(directionalLightHelper);
        let shadowCameraHelper_directionalLight = new THREE.CameraHelper(directionalLight.shadow.camera);
        this.threejs_dev.scene.add(shadowCameraHelper_directionalLight);

        /*半球/自然光*/
        var hemisphereLight = new THREE.HemisphereLight(0xfffff0, 0x101020, 0.2)
        hemisphereLight.position.set(0, 0, 6)
        this.threejs_dev.scene.add(hemisphereLight)
        let hemisphereLightHelper = new THREE.HemisphereLightHelper(hemisphereLight);
        this.threejs_dev.scene.add(hemisphereLightHelper);

        /*点光源/精灵*/

        var sprite_textureUrl = require('../../api/lib/threejs/light/blue_particle.jpg')
        var sprite_texture = new THREE.TextureLoader().load(sprite_textureUrl)
        var sprite_material = new THREE.SpriteMaterial({
          map: sprite_texture,
          blending: THREE.AdditiveBlending,
        })
        var sprite = new THREE.Sprite(sprite_material)
        sprite.scale.x = 5
        sprite.scale.y = 5;
        sprite.position.set(0, 0, 0.1)
        this.threejs_dev.scene.add(sprite);


        /*点光源上下左右*/
        var point_light_z = -0.35
        var point_light_intensity = 20
        var point_light_distance = 15
        var point_out = 1
        var point_light_decay = 0.9

        var point_light_top = new THREE.PointLight(0xffffff, point_light_intensity, point_light_distance, point_light_decay);
        point_light_top.position.set(0, point_out, point_light_z)
        var sphereSize = 1;
        var pointLightTopHelper = new THREE.PointLightHelper(point_light_top, sphereSize);
//        this.threejs_dev.scene.add( pointLightTopHelper );

        var point_light_bottom = new THREE.PointLight(0xffffff, point_light_intensity, point_light_distance, point_light_decay);
        point_light_bottom.position.set(0, -point_out, point_light_z)
        var pointLightBottomHelper = new THREE.PointLightHelper(point_light_bottom, sphereSize);
//        this.threejs_dev.scene.add( pointLightBottomHelper );

        var point_light_Left = new THREE.PointLight(0xffffff, point_light_intensity, point_light_distance, point_light_decay);
        point_light_Left.position.set(point_out, 0, point_light_z)
        var pointLightLeftHelper = new THREE.PointLightHelper(point_light_top, sphereSize);
//        this.threejs_dev.scene.add( pointLightTopHelper );

        var point_light_right = new THREE.PointLight(0xffffff, point_light_intensity, point_light_distance, point_light_decay);
        point_light_right.position.set(-point_out, 0, point_light_z)
        var pointLightRightHelper = new THREE.PointLightHelper(point_light_right, sphereSize);
//        this.threejs_dev.scene.add( pointLightTopHelper );
        this.threejs_dev.scene.add(point_light_top);
        this.threejs_dev.scene.add(point_light_bottom);
        this.threejs_dev.scene.add(point_light_Left);
        this.threejs_dev.scene.add(point_light_right);
        var point_light = {
          'point_light_top': point_light_top,
          'point_light_bottom': point_light_bottom,
          'point_light_Left': point_light_Left,
          'point_light_right': point_light_right,
        }
        this.addMeshToScene('point_light', point_light)

        var point_light_move_z = -2
        var point_light_top_move = new THREE.PointLight(0xffffff, point_light_intensity, point_light_distance, point_light_decay);
        point_light_top_move.position.set(0, point_out, point_light_move_z)
        var point_light_bottom_move = new THREE.PointLight(0xffffff, point_light_intensity, point_light_distance, point_light_decay);
        point_light_bottom_move.position.set(0, -point_out, point_light_move_z)
        var point_light_Left_move = new THREE.PointLight(0xffffff, point_light_intensity, point_light_distance, point_light_decay);
        point_light_Left_move.position.set(point_out, 0, point_light_move_z)
        var point_light_right_move = new THREE.PointLight(0xffffff, point_light_intensity, point_light_distance, point_light_decay);
        point_light_right_move.position.set(-point_out, 0, point_light_move_z)
        this.threejs_dev.scene.add(point_light_top_move);
        this.threejs_dev.scene.add(point_light_bottom_move);
        this.threejs_dev.scene.add(point_light_Left_move);
        this.threejs_dev.scene.add(point_light_right_move);
        var point_light_move = {
          'point_light_top_move': point_light_top_move,
          'point_light_bottom_move': point_light_bottom_move,
          'point_light_Left_move': point_light_Left_move,
          'point_light_right_move': point_light_right_move,
        }
        this.addMeshToScene('point_light_move', point_light_move)


      },
      init_objects() {
        var vm = this

        var floorcenter_material = new THREE.MeshPhongMaterial({color: 0x4080ff, dithering: false});
        var floorcenter_geometry = new THREE.BoxGeometry(3, 1, 2);
        var floorcenter_mesh = new THREE.Mesh(floorcenter_geometry, floorcenter_material);
        floorcenter_mesh.position.set(10, 10, 0);
        floorcenter_mesh.castShadow = true;
//        this.threejs_dev.scene.add( floorcenter_mesh );

        var line_material = new THREE.MeshPhongMaterial({

          blending: THREE.AdditiveBlending,
//          color:0x1aec1a,
          side: THREE.DoubleSide,
//          depthWrite	: false,
          transparent: false
        })
        var line_geometry = new THREE.CylinderBufferGeometry(0.3, 0.3, this.screenWidth / 2, 16);
        let line_mesh = new THREE.Mesh(line_geometry, line_material)
        line_mesh.rotation.x = 1 / 2 * Math.PI
        line_mesh.position.set(0, 0, -this.screenWidth / 4);
        line_mesh.castShadow = true;
        this.threejs_dev.scene.add(line_mesh)
        this.addMeshToScene('line_mesh', line_mesh)

        var o_mesh_objs = []
        for (let i = 1; i <= 100; i++) {
          let o_geometry = new THREE.TorusBufferGeometry(0.3, 0.1, 8, 300);

          let o_material = new THREE.MeshLambertMaterial(
            {
              color: 0x1aec1a,
              emissive: 0x53f953,
              side:
              THREE.DoubleSide
            })
          let o_mesh = new THREE.Mesh(o_geometry, o_material);
          o_mesh.position.set(0, 0, 0 - (i * vm.screenWidth / 200))
          o_mesh.castShadow = true;
          this.threejs_dev.scene.add(o_mesh);

          o_mesh_objs.push(o_mesh)
        }

        this.addMeshToScene('o_mesh_objs', o_mesh_objs)


        /*平面模型*/
//        var plane_objs = []
//        for(let i=0; i<=20; i++){
//          var random_center_z = Math.random() * (-vm.screenWidth/2) * 0.1 - i
//          var random_center_x =  ((Math.random()>0.5)?1:-1) * (10+Math.random()*10)
//          var random_center_y = ((Math.random()>0.5)?1:-1) * (10+Math.random()*10)
//          let plan_geometry = new THREE.PlaneGeometry( 8, 12, 32 );
//          var plan_material = new THREE.MeshPhongMaterial( {color: 0x000000, side: THREE.DoubleSide} );
//          var plane = new THREE.Mesh( plan_geometry, plan_material );
//          plane.position.set(random_center_x,random_center_y,random_center_z)
//          plane.rotation.x = Math.random()*360
//          plane.rotation.y = Math.random()*360
//          plane.rotation.z = Math.random()*360
//          plane.castShadow = true;
//          this.threejs_dev.scene.add( plane );
//          plane_objs.push(plane)
//        }
//        this.addMeshToScene('plane_objs',plane_objs)

        /*立方体模型*/
        var cube_objs = []
        for (let i = -5 * 4; i <= 5 * 4; i += 4) {
          for (let j = -5 * 4; j <= 5 * 4; j += 4) {
            var color = 0x969696
            if (Math.abs(j % 4) === 0) {
              color = 0x969696
            }
            else if (Math.abs(j % 4) === 1) {
              color = 0x161196

            }
            else if (Math.abs(j % 4) === 2) {
              color = 0x000000

            }
            else if (Math.abs(j % 4) === 3) {
              color = 0x189836

            }
            let box_geometry = new THREE.BoxGeometry(2, 1, 2);
            let box_material = new THREE.MeshPhongMaterial({color: color});
            let cube = new THREE.Mesh(box_geometry, box_material);
            cube.position.set(i, -99, j)
            this.threejs_dev.scene.add(cube);
            cube_objs.push(cube)
          }


        }
        this.addMeshToScene('cube_objs', cube_objs)

        /*粒子 星*/
//        var particleCount = 1800,
//          particles = new THREE.Geometry(),
//          pMaterial = new THREE.PointsMaterial({
//            color: 0xFFFFFF,
//            size: 20,
//            map: THREE.ImageUtils.loadTexture(require('../../api/lib/threejs/light/blue_particle.jpg')),
//            blending: THREE.AdditiveBlending,
//            transparent: true
//          });
//
//        for (var p = 0; p < particleCount; p++) {
//          var pX = Math.random() * 500 - 250,
//            pY = Math.random() * 500 - 250,
//            pZ = Math.random() * 500 - 250
//          pY = pY<-100?-100:pY
//          let particle = new THREE.Vector3(pX, pY, pZ);
//
//          particles.vertices.push(particle);
//        }
//
//        var particleSystem = new THREE.Points(
//          particles,
//          pMaterial);
//
//        particleSystem.sortParticles = true;
//        this.threejs_dev.scene.add( particleSystem );

        var maxParticleCount = 500
        var particleCount = 250
        var particlesData = []
        var pMaterial = new THREE.PointsMaterial({
          color: 0xFFFFFF,
          size: 3,
          blending: THREE.AdditiveBlending,
          transparent: true,
          sizeAttenuation: false
        });
        var particles = new THREE.BufferGeometry();
        var particlePositions = new Float32Array(maxParticleCount * 3);
        var r = 50
        for (var i = 0; i < maxParticleCount; i++) {
          var x = Math.random() * r - r / 2;
          var y = Math.random() * r - r / 2;
          var z = Math.random() * r - r / 2;
          particlePositions[i * 3] = x;
          particlePositions[i * 3 + 1] = y;
          particlePositions[i * 3 + 2] = z;
          // add it to the geometry
          particlesData.push({
            velocity: new THREE.Vector3(-1 + Math.random() * 2, -1 + Math.random() * 2, -1 + Math.random() * 2),
            numConnections: 0
          });
        }
        this.addMeshToScene('maxParticleCount', maxParticleCount)
        this.addMeshToScene('particlesData', particlesData)
        this.addMeshToScene('particleCount', particleCount)
        this.addMeshToScene('particlePositions', particlePositions)
        particles.setDrawRange(0, particleCount);
        particles.addAttribute('position', new THREE.BufferAttribute(particlePositions, 3).setDynamic(true));
        // create the particle system
        var pointCloud = new THREE.Points(particles, pMaterial);
        this.threejs_dev.scene.add(pointCloud)
        this.addMeshToScene('pointCloud', pointCloud)

        let geometry = new THREE.BufferGeometry();
        var segments = vm.threejs_dev.meshs.maxParticleCount * vm.threejs_dev.meshs.maxParticleCount;
        var positions = new Float32Array( segments * 3 );
        var colors = new Float32Array( segments * 3 );

        geometry.addAttribute( 'position', new THREE.BufferAttribute( positions, 3 ).setDynamic( true ) );
        geometry.addAttribute( 'color', new THREE.BufferAttribute( colors, 3 ).setDynamic( true ) );
        geometry.computeBoundingSphere();
        geometry.setDrawRange( 0, 0 );
        let material = new THREE.LineBasicMaterial( {
          vertexColors: THREE.VertexColors,
          blending: THREE.AdditiveBlending,
          transparent: true
        } );
        let linesMesh = new THREE.LineSegments( geometry, material );
        this.threejs_dev.scene.add(linesMesh)
        this.addMeshToScene('linesMesh', linesMesh)
        /*模型*/
//        var BinaryLoader = new THREE.BinaryLoader()
//        BinaryLoader.load(require("../../api/lib/threejs/obj/veyron/VeyronNoUv_bin.js"),function () {
//
//        })
//        BinaryLoader.load(require("../../api/lib/threejs/obj/veyron/VeyronNoUv_bin.js"), function (geometry) {
////          vm.createMesh(geometry, scene, 6.8, 2200, -200, -100, 0x0055ff, false);
//        });
//        BinaryLoader.load(require("../../api/lib/threejs/obj/female02/Female02_bin.js"), function (geometry) {
//          vm.createMesh(geometry, scene, 4.05, -1000, -350, 0, 0xffdd44, true);
//          vm.createMesh(geometry, scene, 4.05, 0, -350, 0, 0xffffff, true);
//          vm.createMesh(geometry, scene, 4.05, 1000, -350, 400, 0xff4422, true);
//          vm.createMesh(geometry, scene, 4.05, 250, -350, 1500, 0xff9955, true);
//          vm.createMesh(geometry, scene, 4.05, 250, -350, 2500, 0xff77dd, true);
//        });
//        BinaryLoader.load(require("../../api/lib/threejs/obj/male02/Male02_bin.js"), function (geometry) {
//          vm.createMesh(geometry, scene, 4.05, -500, -350, 600, 0xff7744, true);
//          vm.createMesh(geometry, scene, 4.05, 500, -350, 0, 0xff5522, true);
//          vm.createMesh(geometry, scene, 4.05, -250, -350, 1500, 0xff9922, true);
//          vm.createMesh(geometry, scene, 4.05, -250, -350, -1500, 0xff99ff, true);
//        });

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
        var vm = this
        var delta = 10
//        console.log("this.threejs_dev.meshs['laserBeam'].object3d",this.threejs_dev.meshs['laserBeam'].object3d)
//        this.threejs_dev.meshs['line_object3d'].rotation.x += 0.4 * delta;
//        this.threejs_dev.meshs['line_object3d'].rotation.y += 0.5 * delta;
//        var o_mesh_speed = Math.abs(this.threejs_dev.meshs.o_mesh.position.z)/vm.screenWidth/2 * 18
//        if(o_mesh_speed < 1.5){
//          o_mesh_speed = 1.5
//        }
        /*光圈移动*/
        var o_mesh_len = vm.screenWidth / 100
        vm.threejs_dev.meshs.o_mesh_objs.forEach(function (v, k) {
          if (vm.threejs_dev.meshs.o_mesh_objs[k].position.z >= 0) {
            vm.threejs_dev.meshs.o_mesh_objs[k].position.z = -vm.screenWidth / 2
          }
          vm.threejs_dev.meshs.o_mesh_objs[k].position.z += o_mesh_len / 60
        })
        /*点光源移动*/
        var point_light_speed = 2
        for (let k in vm.threejs_dev.meshs.point_light_move) {
          if (vm.threejs_dev.meshs.point_light_move[k].position.z >= 0) {
            vm.threejs_dev.meshs.point_light_move[k].position.z = -vm.screenWidth / 2
          }
          vm.threejs_dev.meshs.point_light_move[k].position.z += point_light_speed
        }

        this.threejs_dev.renderer.render(this.threejs_dev.scene, this.threejs_dev.camera);
        /*立方体移动*/
//        vm.threejs_dev.meshs.cube_os.forEach(function (v, k) {
//          if (!v.hasOwnProperty('f')) {
//
//            v.f = 1
//          }
//
//          if (v.position.y >= 0) {
//            v.f = -1
//          }
//          if (v.position.y <= -100) {
//            v.f = 1
//          }
////          console.log(k,v.position.y,v.f)
//          v.position.y += v.f * (k + 1) / 100
//        })
        /*粒子缓动*/
        var segments = vm.threejs_dev.meshs.maxParticleCount * vm.threejs_dev.meshs.maxParticleCount;
        var positions = new Float32Array( segments * 3 );
        var colors = new Float32Array( segments * 3 );
        var effectController = {
          showDots: true,
          showLines: true,
          minDistance: 150,
          limitConnections: false,
          maxConnections: 20,
          particleCount: 500
        };
        var r = 50
        var rHalf = r / 2;
        var vertexpos = 0;
        var colorpos = 0;
        var numConnected = 0;
        for (let i = 0; i < vm.threejs_dev.meshs.particleCount; i++){
          vm.threejs_dev.meshs.particlesData[i].numConnections = 10;
        }
        for (let i = 0; i < vm.threejs_dev.meshs.particleCount; i++) {
          // get the particle
          var particleData = vm.threejs_dev.meshs.particlesData[i];
          vm.threejs_dev.meshs.particlePositions[i * 3] += particleData.velocity.x;
          vm.threejs_dev.meshs.particlePositions[i * 3 + 1] += particleData.velocity.y;
          vm.threejs_dev.meshs.particlePositions[i * 3 + 2] += particleData.velocity.z;
          if (vm.threejs_dev.meshs.particlePositions[i * 3 + 1] < -rHalf || vm.threejs_dev.meshs.particlePositions[i * 3 + 1] > rHalf){
            particleData.velocity.y = -particleData.velocity.y;

          }
          if (vm.threejs_dev.meshs.particlePositions[i * 3] < -rHalf || vm.threejs_dev.meshs.particlePositions[i * 3] > rHalf){
            particleData.velocity.x = -particleData.velocity.x;

          }
          if (vm.threejs_dev.meshs.particlePositions[i * 3 + 2] < -rHalf || vm.threejs_dev.meshs.particlePositions[i * 3 + 2] > rHalf){
            particleData.velocity.z = -particleData.velocity.z;
          }
          if (effectController.limitConnections && particleData.numConnections >= effectController.maxConnections){
            continue;

          }
          // Check collision
          for (let j = i + 1; j < vm.threejs_dev.meshs.particleCount; j++) {
            var particleDataB = vm.threejs_dev.meshs.particlesData[j];
            if (effectController.limitConnections && particleDataB.numConnections >= effectController.maxConnections){
              continue;

            }
            var dx = vm.threejs_dev.meshs.particlePositions[i * 3] - vm.threejs_dev.meshs.particlePositions[j * 3];
            var dy = vm.threejs_dev.meshs.particlePositions[i * 3 + 1] - vm.threejs_dev.meshs.particlePositions[j * 3 + 1];
            var dz = vm.threejs_dev.meshs.particlePositions[i * 3 + 2] - vm.threejs_dev.meshs.particlePositions[j * 3 + 2];
            var dist = Math.sqrt(dx * dx + dy * dy + dz * dz);
            if (dist < effectController.minDistance) {
              particleData.numConnections++;
              particleDataB.numConnections++;
              var alpha = 1.0 - dist / effectController.minDistance;
              positions[vertexpos++] = vm.threejs_dev.meshs.particlePositions[i * 3];
              positions[vertexpos++] = vm.threejs_dev.meshs.particlePositions[i * 3 + 1];
              positions[vertexpos++] = vm.threejs_dev.meshs.particlePositions[i * 3 + 2];
              positions[vertexpos++] = vm.threejs_dev.meshs.particlePositions[j * 3];
              positions[vertexpos++] = vm.threejs_dev.meshs.particlePositions[j * 3 + 1];
              positions[vertexpos++] = vm.threejs_dev.meshs.particlePositions[j * 3 + 2];
              colors[colorpos++] = alpha;
              colors[colorpos++] = alpha;
              colors[colorpos++] = alpha;
              colors[colorpos++] = alpha;
              colors[colorpos++] = alpha;
              colors[colorpos++] = alpha;
              numConnected++;
            }
          }
        }

        vm.threejs_dev.meshs.linesMesh.geometry.setDrawRange(0, numConnected * 2);
        vm.threejs_dev.meshs.linesMesh.geometry.attributes.position.needsUpdate = true;
        vm.threejs_dev.meshs.linesMesh.geometry.attributes.color.needsUpdate = true;
        vm.threejs_dev.meshs.pointCloud.geometry.attributes.position.needsUpdate = true;


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
      },
      createCanvasMaterial(color, size) {
        var matCanvas = document.createElement('canvas');
        matCanvas.width = matCanvas.height = size;
        var matContext = matCanvas.getContext('2d');
        // create exture object from canvas.
        var texture = new THREE.Texture(matCanvas);
        // Draw a circle
        var center = size / 2;
        matContext.beginPath();
        matContext.arc(center, center, size / 2, 0, 2 * Math.PI, false);
        matContext.closePath();
        matContext.fillStyle = color;
        matContext.fill();
        // need to set needsUpdate
        texture.needsUpdate = true;
        // return a texture made from the canvas
        return texture;
      },
      getRandomAround(len) {
        return Math.random() > 0.5 ? 1 : -1 * Math.random() * len
      },

      createMesh(originalGeometry, scene, scale, x, y, z, color, dynamic) {
        var i, c;
        var vertices = originalGeometry.vertices;
        var vl = vertices.length;
        var geometry = new THREE.Geometry();
        var vertices_tmp = [];
        for (i = 0; i < vl; i++) {
          p = vertices[i];
          geometry.vertices[i] = p.clone();
          vertices_tmp[i] = [p.x, p.y, p.z, 0, 0];
        }
        var clones = [
          [6000, 0, -4000],
          [5000, 0, 0],
          [1000, 0, 5000],
          [1000, 0, -5000],
          [4000, 0, 2000],
          [-4000, 0, 1000],
          [-5000, 0, -5000],
          [0, 0, 0]
        ];
        if (dynamic) {
          for (i = 0; i < clones.length; i++) {
            c = ( i < clones.length - 1 ) ? 0x252525 : color;
            mesh = new THREE.Points(geometry, new THREE.PointsMaterial({size: 30, color: c}));
            mesh.scale.x = mesh.scale.y = mesh.scale.z = scale;
            mesh.position.x = x + clones[i][0];
            mesh.position.y = y + clones[i][1];
            mesh.position.z = z + clones[i][2];
            parent.add(mesh);
            clonemeshes.push(
              {
                mesh: mesh,
                speed: 0.5 + Math.random()
              });
          }
        } else {
          mesh = new THREE.Points(geometry, new THREE.PointsMaterial(
            {
              size: 30,
              color: color
            }
          ));
          mesh.scale.x = mesh.scale.y = mesh.scale.z = scale;
          mesh.position.x = x;
          mesh.position.y = y;
          mesh.position.z = z;
          parent.add(mesh);
        }
        meshes.push({
          mesh: mesh,
          vertices: geometry.vertices,
          vertices_tmp: vertices_tmp,
          vl: vl,
          down: 0,
          up: 0,
          direction: 0,
          speed: 35,
          delay: Math.floor(200 + 200 * Math.random()),
          started: false,
          start: Math.floor(100 + 200 * Math.random()),
          dynamic: dynamic
        });
      }


    }
  }
</script>
<style lang="stylus" rel="stylesheet/stylus">
  #v-time2hope

    background-color transparent
</style>
