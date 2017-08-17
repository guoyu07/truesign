<template>
    <div class="root_home" style="overflow:hidden;background-color: transparent" >
        <!--<div class="word_show">-->
        <!--<h1>数据</h1>-->
        <!--<h1>可视</h1>-->
        <!--<h1>可控</h1>-->
        <!--<h1>你知道的,一切皆可查询...</h1>-->
        <!--</div>-->
        <!--<input  v-model="is_line_percent" style="border: 2px solid black;color:black !important;display: block;position: absolute;z-index:100;left:20%">-->
        <!--<input type="button" @click="add" value="增加" style="border: 2px solid black;color:black !important;display: block;position: absolute;z-index:100;left:40%">-->
        <threejs_wormhole></threejs_wormhole>
        <blackhole style="position: absolute;z-index:10"> </blackhole>
        <heartbeat_canvas :is_line_percent="is_line_percent+''"></heartbeat_canvas>

    </div>
</template>
<script>
    import heartbeat_canvas from '../effect/heartbeat_canvas.vue'
    import blackhole from '../effect/blackhole.vue'
    import threejs_wormhole from '../effect/threejs_wormhole.vue'
    export default {
        data(){
            return{
                is_line_percent:100
            }
        },
        mounted(){
//            this.$router.push('/project/website_main/website_index')
        },
        methods:{
            clickme(e){
                console.log(e)
            },
            add(){
                this.is_line_percent += 30
            }
        },
        components:{
            heartbeat_canvas,
            blackhole,
            threejs_wormhole

        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
    .root_home{
        width 100%;
        height 100%
        text-align center
        background-color rgba(247, 250, 255, 0.53)

    }
    .word_show{
        width: 50%;height: 50%;margin: 10% auto;min-width: 500px;min-height: 300px
        letter-spacing 5px

    }
</style>
