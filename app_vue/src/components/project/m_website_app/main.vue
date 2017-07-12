<template>

    <div class="top_router_view" id="m_page_main" style="background-color: transparent;" >

        <effectlogo logo_pos="center" logo_width="200" ></effectlogo>
        <div style="text-align: center;color: white;font-size: 1rem">

            <input type="button" @click="changeme" value="doit"/>
        </div>
        <transition  name="fade" mode="out-in" >
                <router-view></router-view>
        </transition>

    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import { analysis_socket_response } from '../../../api/lib/helper/dataAnalysis'
    import effectlogo from '../../loading/effect_logo.vue'

    export default {
        data(){
            return{
                flag : true
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
//            this.$root.eventHub.$emit('change_logo_box_shadow','0 0 20px red')
        },
        beforeDestroy(){
            console.log('beforeDestory')
        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),
            changeme(){
                var vm  = this
                this.flag = !this.flag

                this.$root.eventHub.$emit('changeShowMobileFooter',vm.flag)
            }



        },
        components:{
            effectlogo


        }
    }
</script>
<style>
#m_page_main{
    transition: all 1.5s;
}
</style>
