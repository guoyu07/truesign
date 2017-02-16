<template>
  <div class="webgl-home">
  </div>
</template>

<script>
import { TweenMax } from 'gsap';
import { TimelineLite } from 'gsap';

import Scene from '../../library/webgl/core/Scene.js';
import Cube from '../../library/webgl/meshes/Cube.js';

export default {
  components: {},
  vuex: {
    getters: {},
    actions: {}
  },
  data () {
    return {
      width: window.innerWidth,
      height: window.innerHeight,
      scene: Object(),
      cube: Object()
    }
  },
  created: function(){
    this.scene = new Scene(this.width, this.height);
    this.cube = new Cube();
  },
	mounted: function() {
    window.addEventListener('resize', this.onResize);
    TweenMax.ticker.addEventListener('tick', this.update);

    this.scene.add(this.cube.mesh);
    this.$el.appendChild(this.scene.renderer.domElement);
  },
  beforeDestroy: function(){

  },
  methods:{
    onResize: function(event){
      this.width = window.innerWidth;
      this.height = window.innerHeight;
      this.scene.resize(this.width, this.height);
    },
    update: function(event){
      this.cube.update();
      this.scene.render();
    }
  }
}
</script>

<style>
  .webgl-home, canvas{
    width: 100%;
    height: 100%;
    margin: 0px;
    padding: 0px;
    overflow: hidden;
  }

</style>
