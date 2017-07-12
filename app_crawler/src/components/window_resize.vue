<template>
    <div class="top_router_view"  >

    </div>
</template>

<script>
    import { mapGetters,mapActions } from 'vuex'

    export default {
        data() {
            return {
                screenWidth: document.body.clientWidth,   // 这里是给到了一个默认值 （这个很重要）
                screenHeight: document.body.clientHeight,  // 这里是给到了一个默认值 （这个很重要）
            }
        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ])
        },
        mounted(){
            const vm = this
            this.updateSysInfo({screenWidth:vm.screenWidth,screenHeight:vm.screenHeight})

            window.onresize = () => {
                return (() => {
                    window.screenWidth = document.body.clientWidth
                    window.screenHeight = document.body.clientHeight
                    vm.screenWidth = window.screenWidth
                    vm.screenHeight = window.screenHeight
                    vm.$root.eventHub.$emit('screenWidth2screenHeight',screenWidth+','+screenHeight)
                    vm.updateSysInfo({screenWidth:vm.screenWidth,screenHeight:vm.screenHeight})
                })()
            }
        },
        components:{


        },

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
</script>

<style lang="stylus" rel="stylesheet/stylus">


</style>
