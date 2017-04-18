<template>
    <div id="footerbar">

        <span class="showip">IP   {{ ip }}</span>


    </div>

</template>



<script>
    import Velocity from 'velocity-animate'
    import Vue from 'vue'

    export default {
        data() {
            return{
                ip:'127.0.0.1'
            }
        },
        mounted(){



        },
        methods:{

        },
        computed:{


        }

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
