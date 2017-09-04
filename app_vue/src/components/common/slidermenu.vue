<template>
    <div id="slidermenu" :style="{overflow:'auto',marginLeft:showmenu===false?'-360px':'0px'}" @mouseleave="menu_mouseleave">


        <div id="menubar"  @mouseover="menu_mouseover" @animationend="test" >
        <input v-model="query" style="width: 80%;height: 100%;float: left;margin-left:5px;border: none;outline:none" placeholder="MENU">
        <i @click="clearquery">M</i>
    </div>
        <transition-group id="menu-list" name="menu-list" tag="div" v-if="showmenu"
                          v-on:before-enter="beforeEnter"
                          v-on:enter="enter"
                          v-on:after-enter="afterEnter"
                          v-on:enter-cancelled="enterCancelled"
                          v-on:leave="leave"
                          v-bind:css="false"
        >
            <li class="menu_item" v-for="(item,index) in buildMenuList" :key="index" :data-index="index" :data-id="item.id"
                style="width: 100%;text-align: left;background-color:rgba(51, 62, 75, 0.8);color:rgba(105,210,231,0.69);padding-left: 10px">
                <!--<a :href="item.path" >{{item.name}}</a>-->
                <router-link :to="item.path" style="color:rgba(105,210,231,0.69)">{{item.name}}</router-link>
            </li>
        </transition-group>


    </div>

</template>



<script>
    import router_build from '../../app_config/router-build'
//    import Velocity from 'velocity-animate'
    import Vue from 'vue'

    export default {
        data() {
            return{
                menulist:[],
                showmenu:false,
                list: [
                    { msg: 'Bruce Lee' },
                    { msg: 'Jackie Chan' },
                    { msg: 'Chuck Norris' },
                    { msg: 'Jet Li' },
                    { msg: 'Kung Fury' }
                ],
                query: '',
            }
        },
        mounted(){




        },
        methods:{
            clearquery(){
                this.query = ''
            },
            menu_mouseover(){
                var vm = this
                this.showmenu = true
                Vue.nextTick(function () {
                    vm.menulist = router_build
                })
                $('#slidermenu').css('height','97%')

            },
            menu_mouseleave(){
                this.showmenu = false
                this.menulist = []
                $('#slidermenu').css('height','35px')

            },

            beforeEnter(el) {
                //console.log('beforeEnter')
                el.style.opacity = 0
                el.style.height = 0
            },
            enter(el,done) {
                //console.log('enter')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 1, height: '40px' },
                        { complete: done }
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
            leave(el,done){
                //console.log('Leave')
                var delay = el.dataset.index * 100
                setTimeout(function () {
                    Velocity(
                        el,
                        { opacity: 0, height: 0 },
                        { complete: done }
                    )
                }, delay)
            },
            afterLeave(el){
                //console.log('afterLeave')
            },
            leaveCancelled(el){
                //console.log('leaveCancelled')
            },
            test(){
                alert(1)
            }
        },
        computed:{
            buildMenuList(){
                var vm = this
                var build_menulist = []
                this.menulist.forEach(function (v,k) {
                    v.forEach(function (vv,kk) {
                        build_menulist.push(vv)
                    })
                })
                return build_menulist.filter(function (item) {
                    return item.name.toLowerCase().indexOf(vm.query.toLowerCase()) !== -1
                })
//                return build_menulist
            },

        }

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

.menu_item:hover
    background-color: rgba(0, 0, 0, 0.42) !important
    box-shadow: 0 0  5px #57DCDF;
.menu_item>a:hover
    /*font Monaco !important*/
    /*font-size 16px*/
#slidermenu
    transition all 1s
    position fixed
    left 0
    top 0px
    width 420px
    height 35px
    background-color transparent
    text-align center
    line-height 35px
    z-index 1
    /*background-color rgba(51, 62, 75, 0.43)
    box-shadow 0 0 10px green*/
    #menubar
        cursor pointer
        width 100%
        height 35px
        background-color rgba(51, 62, 75, 0.8)
        box-shadow: 0px 0px 15px  #69d2e7;
        color:#69d2e7
        transition all 1s





</style>
