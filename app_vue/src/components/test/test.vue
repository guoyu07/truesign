<template>
  <div class="top_router_view" style="text-align: center">
    <div style="position: absolute;left: 50%;width: 64px;height: 64px">
      <div class="outer"></div>
    </div>


  </div>
</template>

<script>
  	export default {
  		data: function () {
  			return {}
  		},
        created(){

        },
        methods:{

        },
        components: {
        },
        watch:{

        },

  	}
</script>

<style>
  .outer {
    position: absolute;
    width: 100%;
    height: 100%;
    background: url(http://cdn.iamsee.com/loading/icon-spin-s.png) no-repeat;
    animation: spin 800ms infinite linear;
  }
  @keyframes spin {
    0%   { transform: rotate(360deg); }
    100% { transform: rotate(0deg); }
  }
  @keyframes second-half-hide {
    0%        { opacity: 1; }
    50%, 100% { opacity: 0; }
  }
  @keyframes second-half-show {
    0%        { opacity: 0; }
    50%, 100% { opacity: 1; }
  }
</style>
