<template>
  <div class="top_router_view"  style="overflow: scroll">
        <div class="x"  ></div>
        <div class="f" ></div>
  </div>
</template>

<script>
  	module.exports = {
  		data: function () {
  			return {

  			}
  		},
        methods:{

        },
        components:{


        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">
    .x {
        border: solid 1px red;
        width: 100px;
        height: 100px;
        position: relative;
        background-color: transparent;
    }

    .x:before {
        position: absolute;
        top: 0px;
        right: 0;
        left: 0;
        bottom: 0;
        border-bottom: 100px solid red;
        border-left: 100px solid transparent;
        content: "";
    }

    .x:after {
        position: absolute;
        left: 1px;
        right: 0;
        top: 1px;
        bottom: 0;
        border-bottom: 99px solid white;
        border-left: 99px solid transparent;
        content: "";
    }

    .f {
        border: solid 1px red;
        width: 100px;
        height: 100px;
        position: relative;
        background-color: transparent;
    }

    .f:before {
        position: absolute;
        top: 0px;
        right: 0;
        left: 0;
        bottom: 0;
        border-bottom: 100px solid red;
        border-right: 100px solid transparent;
        content: "";
    }

    .f:after {
        position: absolute;
        left: 0;
        right: 1px;
        top: 1px;
        bottom: 0;
        border-bottom: 99px solid white;
        border-right: 99px solid transparent;
        content: "";
    }
</style>
