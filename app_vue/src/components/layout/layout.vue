<template>
    <div id="components_layout">
        <p class="top_router_tip">LAYOUT</p>
        <custom_navbar :nav_list="nav_list"></custom_navbar>
        <div id="layout_container">

            <div v-if="currect_label.label === '圣杯布局'">
                {{ currect_label.label}}

            </div>

            <div v-else-if="currect_label.label === '伸缩布局'" class="flex_layout">
                <div class="flex_header">
                    <div class="ctrl_btn">⇅</div>
                    <input type="text"
                           style="padding: 5px 20px;width: 20%;box-shadow: 0 0 15px black;margin-top: 15px;color: black !important;"
                           v-model="query" placeholder="LOOK FOR">
                </div>
                <div class="flex_body">
                    <div class="flex_left">
                        <div class="ctrl_btn">⇄</div>
                        <transition-group tag="ol"
                                          v-on:before-enter="beforeEnter"
                                          v-on:enter="enter"
                                          v-on:after-enter="afterEnter"
                                          v-on:enter-cancelled="enterCancelled"
                                          v-on:leave="leave"
                                          v-bind:css="false">
                            <li v-for="item,index in buildQuery" :key="item"
                                style="width: 100%;height: 30px;line-height:30px;text-align:center;background-color: #b8b8b8;box-shadow: 0 0 10px black;margin-top: 4px">
                                {{item}}
                            </li>
                        </transition-group>

                    </div>
                    <div class="flex_right">
                        <transition-group tag="ol"
                                          v-on:before-enter="beforeEnter"
                                          v-on:enter="m_enter"
                                          v-on:after-enter="afterEnter"
                                          v-on:enter-cancelled="enterCancelled"
                                          v-on:leave="m_leave"
                                          v-bind:css="false">
                            <li v-for="item,index in buildQuery" :key="item"
                                style="width: 10%;height: 30px;line-height:30px;text-align:center;background-color: #b8b8b8;box-shadow: 0 0 10px black;margin-top: 4px;display: inline-block">
                                {{item}}
                            </li>

                        </transition-group>
                    </div>
                </div>


            </div>


        </div>
    </div>
</template>

<script>
    import custom_navbar from '../common/custom_navbar.vue'
    export default {
        data: function () {
            return {
                nav_list: [
                    {label: '伸缩布局', url: 'www.baidu.com'},
                    {label: '圣杯布局', url: 'www.baidu.com'},
                ],
                query: '',
                list: [],
                currect_label: {label: '伸缩布局', url: 'www.baidu.com'}
            }
        },
        created(){
            var vm = this
            this.$root.eventHub.$off('change_custom_navbar')
            this.$root.eventHub.$on('change_custom_navbar', function (currect_label) {
                vm.currect_label = currect_label
            })
            for (let i = 0; i < 500; i++) {
                this.list.push(Math.random() * 1000)
            }
        },
        mounted(){

        },
        updated(){
            console.log('update')
            $('.flex_header .ctrl_btn').on('click', function () {
                if ($('.flex_header').css('flex') === '1 1 0%') {
                    $('.flex_header').css('flex', '0 0 0%')
                }
                else {
                    $('.flex_header').css('flex', '1 1 0%')
                }
            })
            $('.flex_left .ctrl_btn').on('click', function () {
                if ($('.flex_left').css('flex') === '1 1 0%') {
                    $('.flex_left').css('flex', '0 0 0%')
                }
                else {
                    $('.flex_left').css('flex', '1 1 0%')
                }
            })
        },
        methods: {

            beforeEnter(el) {
                //console.log('beforeEnter')
                el.style.opacity = 0
                el.style.height = 0
            },
            enter(el, done) {
                //console.log('enter')
                var delay = el.dataset.index * 200
                setTimeout(function () {
                    Velocity(
                        el,
                        {opacity: 1, height: '30px'},
                        {complete: done}
                    )
                }, delay)
            },
            m_enter(el, done) {
                //console.log('enter')
                var delay = el.dataset.index * 200
                setTimeout(function () {
                    Velocity(
                        el,
                        {opacity: 1, width: '10%',height:'30px'},
                        {complete: done}
                    )
                }, delay)
            },
            afterEnter(el) {
                //console.log('afterenter')

            },
            enterCancelled(el) {
                //console.log('enterCancelled')
            },
            beforeLeave(el){
                //console.log('befaoreLeave')
            },
            leave(el, done){
                //console.log('Leave')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        {opacity: 0, height: 0},
                        {complete: done}
                    )
                }, delay)
            },
            m_leave(el, done){
                //console.log('Leave')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        {opacity: 0, width: 0},
                        {complete: done}
                    )
                }, delay)
            },
            afterLeave(el){
                //console.log('afterLeave')
            },
            leaveCancelled(el){
                //console.log('leaveCancelled')
            },
        },
        components: {
            custom_navbar,
        },
        computed:{
            buildQuery(){
                var vm = this
                var buildQuery = []
                this.list.forEach(function (v) {
                        buildQuery.push(v+'')
                })
                return buildQuery.filter(function (item) {
                    return item.toLowerCase().indexOf(vm.query.toLowerCase()) !== -1
                })
//                return build_menulist
            },

        },
        watch: {},

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
    #components_layout
        width 100%;
        height 100%;
        overflow auto
        background-color transparent
        #layout_container
            width 100%;
            height 100%
            background-color transparent
            padding-top 35px
        .flex_layout
            height 100%
            display flex
            justify-content: center
            flex-direction column
            .flex_header
                flex 1
                background-color transparent
                text-align center
                position relative
                transition all 1.3s
                .ctrl_btn
                    display: block;
                    content: "关闭"
                    color: #09F;
                    position absolute
                    right: 0%
                    bottom 0
                    z-index 100
                    padding 2px 10px
                    width 60px
                    text-align center
                    background-color rgba(70, 70, 70, 0.12)
                    writing-mode: lr
                    border-radius 5px
                    cursor pointer
                    margin-bottom -25px
                    color #fff

            .flex_body
                border-top: 1px #909090 solid;
                flex 16
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                background-color transparent
                .flex_left
                    border-right: 1px #909090 solid;
                    flex 1
                    height 100%
                    background-color transparent
                    position relative
                    transition all 1.3s
                    .ctrl_btn
                        display: block;
                        color: #09F;
                        position absolute
                        top: 0%
                        right 0
                        margin-right -22px
                        background-color rgba(70, 70, 70, 0.12)
                        border-radius 5px
                        cursor pointer
                        padding 15px 5px
                        text-align center
                        color #fff
                .flex_right
                    flex 6
                    height 100%
                    background-color transparent

</style>
