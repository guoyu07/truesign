<template>

    <div class="top_router_view">

        <canvas id="cas"></canvas>
    </div>

</template>

<script>
    export default {
        data () {
            return {
            }
        },
        mounted(){
            require("./../../api/lib/canvas/particle/blackhole.js")

        }
    }
</script>

<style>
    #cas{
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        margin: auto;
    }
</style>
