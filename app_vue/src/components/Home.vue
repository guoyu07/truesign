<template>
    <div class="top_router_view" style="" >
        <transition name="fade-show">
            <effect_line v-if="show_loading" id="loading_page" style="position: absolute;z-index: -1" :effect_line_top="effect_line_top"></effect_line>
            <line_vue  v-else-if="!show_loading" id="main_page" style="position:absolute;z-index: 12;transition: all 1s"></line_vue>

        </transition>
        <initsocket   :style="{position:'absolute',zIndex: '20',visibility:show_conn,transition: 'all 1s'}" ></initsocket>

    </div>
</template>



<script>
    import line_vue from './test/line.vue'
    import effect_line from './loading/effect_line.vue'
    import initsocket from './communicationModule/initSocket.vue'
    export default {
        data(){
            return{
                effect_line_top:'0',
                show_loading:false,
                show_conn:'hidden'
            }
        },
        mounted(){
            var vm = this
            this.listen_key_fun()
            this.$root.eventHub.$on('rule_submit_form',function (data) {

                vm.$root.eventHub.$emit('laoding','start')
            })
            this.$root.eventHub.$emit('autoInit',1)
            this.$root.eventHub.$on('laoding',function (data) {
                console.log('loading->',data)
                if(data==='start'){
                    vm.show_loading=true
                    setTimeout(function () {
                        $('#loading_page').css('z-index',13)
                        vm.effect_line_top = '100'
                    },1000)



                }
                else if(data==='over'){
                    $('#loading_page').css('z-index',-1)
                    vm.show_loading=false

                }
            })
            this.$root.eventHub.$on('socket_response',function (data) {
                console.log('socket_response->',data)
            })
        },
        methods:{
            keythis(n,e){
                alert(n)
            },
            listen_key_fun(){
                var vm = this
                $(document).keypress(function(e){
                    console.log(e.keyCode)
                    if(e.ctrlKey && e.which === 13 || e.which === 10) {
                        //要执行的操作
                        vm.show_conn = (vm.show_conn === 'visible')?'hidden':'visible'
                    }
                });
            }
        },
        components:{
            line_vue,
            effect_line,
            initsocket

        }
    }
</script>
<style>
    .fade-show-enter-active{
        transition: all 1s;
        opacity: 1;

    }
    .fade-show-enter{
        transition: all 1s;

        opacity: 0;

    }
    .fade-show-leave{
        transition: all 1s;

        opacity: 1;
    }
    .fade-show-leave-active{
        transition: all 1s;

        opacity: 0;


    }
</style>
