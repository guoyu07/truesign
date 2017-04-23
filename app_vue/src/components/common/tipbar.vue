<template>
    <transition name="shifting-half-fade" v-if="show" mode="out-in">
        <div id="tipbar"  :style="{width:width+'px'}">

            {{ content }}

        </div>
    </transition>

</template>



<script>
    import Velocity from 'velocity-animate'
    import Vue from 'vue'

    export default {
        data() {
            return{


            }
        },
        props:{
            width:{
                type: String,
                default: '300', //left_top
                required: false,

            },
            time:{
                type: String,
                default: '1000', //left_top
                required: false,

            },
            content:{
                type: String,
                default: '', //left_top
                required: false,

            },
            show:{
                default: false, //left_top
                required: false,

            },

        },
        mounted(){
            var vm = this
            setTimeout(function () {
                vm.show=false
            },vm.time)

        },
        methods:{

        },
        computed:{


        }

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

#tipbar

    position fixed
    z-index 100
    width 200px;
    height 35px;
    line-height 35px
    border-radius 5px
    box-shadow: 0 0 20px #57DCDF !important
    border-top 1px solid rgba(255, 255, 255, 0.42)
    border-bottom 1px solid rgba(255, 255, 255, 0.42)
    left 50%
    transform translateX(-50%)
    right 0
    top 30px

    background-color rgba(73, 166, 169, 0.26)
    font-size 20px
    color white
    font-weight 800
    text-align center
    transition all 1.5s


</style>
