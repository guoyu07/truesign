<template>
    <div id="chatapp">
    </div>

</template>



<script>
    import Velocity from 'velocity-animate'
    import Vue from 'vue'
    import { mapGetters,mapActions } from 'vuex'

    export default {
        data() {
            return{

            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'website',
                'sysinfo'
            ])
        },
        mounted(){

        },
        methods:{

        },


    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

#footerbar
    margin 0
    padding 0
    border none
    position fixed
    left 0
    bottom 0
    width 100%
    height 25px
    background-color transparent

    .showip{
        margin-left 15px
        font-size 16px
        color rgba(209, 209, 209, 0.45)
        font-family:"Source Code Pro", Courier, monospace !important
    }

</style>
