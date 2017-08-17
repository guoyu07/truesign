<template>

    <div id="blackhole"  :style="{width: sysinfo.screenWidth+'px',height: sysinfo.screenHeight+'px',overflow:'hidden'}">

        <canvas  id="cas"></canvas>
    </div>

</template>

<script>
    import { mapGetters,mapActions } from 'vuex'
    export default {
        data () {
            return {
            }
        },
        mounted(){
            require("./../../api/lib/canvas/particle/blackhole.js")

        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo'
            ])
        },
    }
</script>

<style>
    #cas{

        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
    }
</style>
