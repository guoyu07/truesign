<template>

    <div class="top_router_view" style="background-color: whitesmoke;position:absolute;width:100%;height:100%;z-index:200;overflow: scroll" >
        <div id="show-html" style="position: absolute;width: 100%;height: auto;z-index:10;">

        </div>
        <div id="show-wangeditor">
            <wangeditor :editorShow_height="200"></wangeditor>
        </div>
    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import { analysis_socket_response } from '../../../api/lib/helper/dataAnalysis'
    import wangeditor from '../../tools/wangeditor.vue'

    export default {
        data(){
            return{
                
            }
        },
        computed: {
            // 使用对象展开运算符将 getters 混入 computed 对象中
            ...mapGetters([
                'apprules',
                'website',
                'sysinfo',
                'appshow'
            ])
        },
        created(){
            var vm = this



        },
        mounted(){

            var vm = this
            

        },
        beforeDestroy(){
            console.log('shadowsocks->beforeDestory')
            this.$root.eventHub.$off('socket_response')
        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            

        },
        components:{

            wangeditor
        }
    }
</script>
<style>
#show-wangeditor{
    position: absolute;
    top: 80%;
    width: 100%;
    height: 200px;
    overflow: hidden;
    background-color: lightgrey;
    z-index:20;
    box-shadow: 0 0 15px black;
}
</style>
