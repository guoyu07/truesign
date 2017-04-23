<template>
    <div class="top_router_view">
        <div id="particles-js" style="width: 100%;height: 100%;"></div>
  </div>
</template>

<script>
//    import particlesJS from 'particles.js'
    require('particles.js')

    export default {
        data () {
            return {
            }
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
