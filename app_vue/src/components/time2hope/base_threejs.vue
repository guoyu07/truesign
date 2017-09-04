<template>
  <div id="v-basethreejs" :style="{width:screenWidth+'px',height:screenHeight+'px'}">
  </div>
</template>
<script>
  import {mapGetters, mapActions} from 'vuex'
  import drawThreejs from '../../api/drawThreejs'
  const CanvasRenderer = require('three/examples/js/renderers/CanvasRenderer')
  export default {
    data() {
      return {
        screenWidth: document.width,
        screenHeight: document.height,
        threejs_obj:{}
      }
    },
    watch: {
      screenWidth: function (a, b) {

      },
      screenHeight: function (a, b) {

      }
    },
    mounted() {
      this.screenWidth = this.sysinfo.screenWidth
      this.screenHeight = this.sysinfo.screenHeight
      this.start()
    },
    computed: {
      ...mapGetters([
          'apprules',
          'website',
          'sysinfo',
          'appshow'
        ])
    },
    methods: {
      start(){
        var vm = this
        this.init_container()
        this.init_renderer()
        this.init_scene()
        this.init_camera()
        this.init_helper()
        this.init_objects()
        this.init_controls()
        this.do_render()
        this.do_animate()
      },
      init_renderer(){
        let renderer = new THREE.CanvasRenderer();
        renderer.setClearColor(0xffffff, 1);
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(this.screenWidth, this.screenHeight);
        this.threejs_obj.basethreejs.init_renderer({render_type:renderer})

      },
      init_container(){
        this.threejs_obj.basethreejs = new drawThreejs('v-basethreejs')
      },
      init_scene(){
        let fog = new THREE.FogExp2( 0xcccccc, 0.002 );
        this.threejs_obj.basethreejs.init_scene({fog:fog})
      },
      init_camera(){
        let camera = new THREE.PerspectiveCamera(55, this.screenWidth / this.screenHeight, 1, 10000);
        this.threejs_obj.basethreejs.init_camera({camera:camera,x:0,y:0,z:1000})

      },
      init_helper(){
        this.threejs_obj.basethreejs.init_helper()
      },
      init_controls(){

         controls = new THREE.TrackballControls(this.threejs_obj.basethreejs.threejs_dev.camera, this.threejs_obj.basethreejs.threejs_dev.renderer.domElement);

         controls.rotateSpeed = 1.0;
         controls.zoomSpeed = 1.2;
         controls.panSpeed = 0.8;
         controls.noZoom = false;
         controls.noPan = false;
         controls.staticMoving = false;
         controls.dynamicDampingFactor = 0.1;
         controls.keys = [65, 83, 68];
//                this.threejs_dev.controls.addEventListener('mousemove', this.do_render);


      },
      init_objects(){
        var vm = this
        var meshs = []

        var img = require('../../../static/img/flag_of_china.png')
        var imgTexture = THREE.ImageUtils.loadTexture(img);
//                var imgTexture = THREE.TextureLoader(img,{}, function() { this.threejs_dev.renderer.render(this.threejs_dev.scene, this.threejs_dev.camera);});
        var imgMaterial = new THREE.MeshLambertMaterial({map: imgTexture});
        var imgMesh = new THREE.Mesh(new THREE.CubeGeometry(200, 200, 200), imgMaterial);
//        imgMesh.position.x = 550;
//        imgMesh.position.y = 0;
//        imgMesh.position.z = 0;
        meshs.push({name:'imgMesh',obj:imgMesh})
        this.threejs_obj.basethreejs.init_objects({obj_meshs:meshs})

      },
      init_resize(){
        var vm = this

        this.threejs_obj.basethreejs.threejs_dev.camera.aspect = this.screenWidth / this.screenHeight
        this.threejs_obj.basethreejs.threejs_dev.camera.updateProjectionMatrix();
        this.threejs_obj.basethreejs.threejs_dev.renderer.setSize(this.screenWidth, this.screenHeight);


      },
      init_mouse_move(event){
        this.mouse_move.mouseX = ( event.clientX - this.screenWidth / 2 );
        this.mouse_move.mouseY = ( event.clientY - this.screenHeight / 2 ) * 1;


      },
      do_render(){

        this.threejs_obj.basethreejs.threejs_dev.renderer.render(this.threejs_obj.basethreejs.threejs_dev.scene, this.threejs_obj.basethreejs.threejs_dev.camera);

      },
      do_animate() {
        requestAnimationFrame(this.do_animate);
        this.threejs_obj.basethreejs.threejs_dev.controls.update();
        this.threejs_obj.basethreejs.threejs_dev.stats.update()
        this.do_render();


      },
    },
    components: {}
  }
</script>
<style lang="stylus" rel="stylesheet/stylus">
#v-time2hope

  background-color rgba(97, 97, 97, 0.65)
</style>
