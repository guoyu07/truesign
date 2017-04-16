<template>
  <div class="top_router_view"  >
        <div class="triangle-bottomright" style="position: absolute;z-index:1"></div>
        <div class="triangle-topleft" style="position: absolute;z-index:1"></div>
  </div>
</template>

<script>
  	module.exports = {
  		data: function () {
  			return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
  			}
  		},
        methods:{

        },
        mounted(){
            console.log($('.triangle-bottomright').css('border-bottom'))
        },
        components:{


        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">


</style>
