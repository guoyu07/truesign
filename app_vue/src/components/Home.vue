<template>
    <div class="root_home" style="background-color: black" >
        <!--<div class="word_show">-->
            <!--<h1>数据</h1>-->
            <!--<h1>可视</h1>-->
            <!--<h1>可控</h1>-->
            <!--<h1>你知道的,一切皆可查询...</h1>-->
        <!--</div>-->
        <!--<input  v-model="is_line_percent" style="border: 2px solid black;color:black !important;display: block;position: absolute;z-index:100;left:20%">-->
        <!--<input type="button" @click="add" value="增加" style="border: 2px solid black;color:black !important;display: block;position: absolute;z-index:100;left:40%">-->
        <loading_canvas   style="background-color: transparent;position: absolute;" ></loading_canvas>

        <transition >
             <main_page v-if="type==='main_page'"></main_page>
        </transition>
    </div>
</template>
<script>
    import main_page from './pages/main_page_20170816.vue'
    import loading_canvas from './effect/loading_canvas.vue'

    export default {
        data(){
            return{
                is_line_percent:100,
                type:'loading'
            }
        },
        mounted(){
//            this.$router.push('/project/website_main/website_index')
            var vm =this
            setTimeout(function () {
                vm.type='main_page'
            },3000)
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
            main_page,
            loading_canvas
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
