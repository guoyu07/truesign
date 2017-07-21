<template>
    <div id="custom_navbar">
        <ol id="custom_navbar_container" >
            <div id="nav_bg" :style="{left:nav_bg_left+'px'}"></div>
            <div id="nav_label" v-for="item,index in nav_list" @click="click_nav" :data-index="index">
                <li v-if="index === default_click" class="nav_lable_active" :data-index="index">{{item.label}}</li>
                <li v-else="index === default_click" class="" :data-index="index">{{item.label}}</li>
            </div>

        </ol>

    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                nav_bg_left:0,
                default_click:0
            }
        },
        props: {
            nav_list: {
                default: function () {
                    return [
                        {label:'圣杯布局',url:'www.baidu.com'},
                        {label:'伸缩布局',url:'www.baidu.com'}
                    ]
                }
            },

        },
        created(){
            var vm = this
            this.$root.eventHub.$on('screenWidth2screenHeight',function () {
                vm.reset_click_label()
            })
        },
        mounted(){
            var vm = this
            this.reset_click_label()
        },
        methods: {
            reset_click_label:function () {
                var vm = this
                $('#nav_label li').each(function () {
                    if($(this).data('index') === vm.default_click){
                        vm.nav_bg_left = $(this).offset().left
                    }

                })
            },
            click_nav:function (e,data) {
                e = e.currentTarget
                let currect_index = e.dataset.index
                let currect_label = this.nav_list[currect_index]
                this.default_click = parseInt(currect_index)
                this.nav_bg_left = e.offsetLeft
                this.$root.eventHub.$emit('change_custom_navbar',currect_label)
            }
        },

        components: {},
        watch: {},

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
#custom_navbar
    position fixed
    height 35px;
    width 100%
    background-color rgba(96, 96, 96, 0.27)
    box-shadow 0 0 10px black
    cursor pointer
    overflow hidden
    #custom_navbar_container
        width 100%
        height 100%
        text-align center
        #nav_bg
            height 100%
            width 120px
            position absolute
            z-index:1
            background-color #4D5661
            transition all 0.8s
        #nav_label
            position relative
            z-index:2
            height 100%
            width 120px
            line-height 35px
            background-color transparent
            display inline-block
            color #737373
            font-size 18px
            text-shadow: 0px 1px 0px #e5e5ee;
            letter-spacing 2px
            border-right 1px outset rgba(68, 72, 71, 0.56)
            transition all 0.8s
            .nav_lable_active
                color:#fff
        #nav_label:first-child
            border-right 1px outset rgba(68, 72, 71, 0.56)
        #nav_label:last-child
            border-right none

</style>
