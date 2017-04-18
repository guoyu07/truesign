<template>
  <div class="top_router_view"  >
        <div style="text-align: center;color: white">
            <h1>{{screenWidth}},{{screenHeight}}</h1>
        </div>
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
            const vm = this
            window.onresize = () => {
                return (() => {
                    window.screenWidth = document.body.clientWidth
                    window.screenHeight = document.body.clientHeight
                    vm.screenWidth = window.screenWidth
                    vm.screenHeight = window.screenHeight
                    vm.$root.eventHub.$emit('screenWidth2screenHeight',screenWidth+','+screenHeight)
                })()
            }
        },
        components:{
            watch: {
                screenWidth (val) {
                    if (!this.timer) {
                        this.screenWidth = val
                        this.timer = true
                        let that = this
                        setTimeout(function () {
                            // that.screenWidth = that.$store.state.canvasWidth
                            console.log(that.screenWidth)
                            that.timer = false
                        }, 400)
                    }
                },
                screenHeight (val) {
                    if (!this.timer) {
                        this.screenHeight = val

                        this.timer = true
                        let that = this
                        setTimeout(function () {
                            // that.screenWidth = that.$store.state.canvasWidth
                            console.log(that.screenHeight)
                            that.timer = false
                        }, 400)
                    }
                }
            },

        }
  	}
</script>

<style lang="stylus" rel="stylesheet/stylus">


</style>
