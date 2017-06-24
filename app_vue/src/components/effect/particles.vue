<template>
    <div class="top_router_view" :style="{width: sysinfo.screenWidth,height:sysinfo.screenWidth,overflow:'hidden'}">
        <div id="particles-js" style="width: 100%;height: 100%;"></div>
  </div>
</template>

<script>
//    import particlesJS from 'particles.js'
    require('particles.js')
    import { mapGetters,mapActions } from 'vuex'

    export default {
        data () {
            return {
            }
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
            const config = require('../../api/lib/particles/particlesjs-config.json')
//            console.log(particlesJS)



            /* particlesJS.load(@dom-id, @path-json, @callback (optional)); */
            particlesJS('particles-js',
                config


            );
        }
    }
</script>

<style>

</style>
