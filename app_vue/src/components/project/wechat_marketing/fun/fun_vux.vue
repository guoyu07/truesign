<template>
    <div class="top_mobile_view">
        <divider>功能模块</divider>

        <router-view></router-view>
    </div>
</template>


<script>
    require('vux/src/styles/reset.less')
//    require('../../../../../static/css/mobile_style.js')
    require('../../../../../static/css/mobile_style.css')
    const FastClick = require('fastclick')
    FastClick.attach(document.body)
    import { Divider } from 'vux'

    export default {
        data(){
            return {}
        },
        props: {},
        components: {
            Divider,

        },
        computed: {},
        created(){

        },
        mounted(){


        },
        beforeDestroy(){

        },
        methods: {},

    }
</script>
<style>


</style>
