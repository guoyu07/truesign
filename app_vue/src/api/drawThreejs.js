require("./lib/canvas/utils")
const TWEEN = require('@tweenjs/tween.js');
const utils = window.utils
const requestAnimationFrame = window.requestAnimationFrame

class DrawThreejs {
  constructor(el, width, height) {
    this.el = el
    this.width = width
    this.height = height
    var vm = this
    this.threejs_dev = {
      container : document.getElementById(vm.el),
      renderer : {},
      scene : {},
      camera : {},
      objects : {},
      helper : {
        stats : {},
        dat_gui: {},
      },
      meshsName:[]

    }



  }

  initWidthHeight(width, height) {
    this.width = width
    this.height = height
  }

  init_renderer({render_type}){
    this.threejs_dev.renderer = render_type
  }
  init_scene({fog}){
    this.threejs_dev.scene = new THREE.Scene();
    this.threejs_dev.scene.fog = fog
  }
  init_camera({camera,x,y,z}){
    this.threejs_dev.camera = camera
    this.threejs_dev.camera.position.x = x;
    this.threejs_dev.camera.position.y = y;
    this.threejs_dev.camera.position.z = z;
  }
  init_helper(){
    this.threejs_dev.helper.stats = new Stats()
    this.threejs_dev.helper.stats.setMode(0);
    this.threejs_dev.helper.stats.domElement.id = 'canvas_stats'
    this.threejs_dev.helper.stats.domElement.style.position = 'fixed';
    this.threejs_dev.helper.stats.domElement.style.left = this.width - 100 + 'px';
    this.threejs_dev.helper.stats.domElement.style.top = '0px';
    this.threejs_dev.helper.stats.domElement.style.width = '300px';
    this.threejs_dev.helper.stats.domElement.style.height = '100px';
    document.body.appendChild(this.threejs_dev.helper.stats.domElement);
  }
  init_objects({obj_meshs:meshs}){
    var vm = this
    for (let mesh in meshs){
      vm.threejs_dev.scene.add(mesh.obj)
      vm.threejs_dev.meshsName[mesh.name] = mesh.obj
    }
  }
  init_controls({controller}){
    this.threejs_dev.controls = controller
  }



}

export default DrawThreejs
