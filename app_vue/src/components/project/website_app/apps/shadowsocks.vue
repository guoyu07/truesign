<template>
    <div class="top_router_view" style="background-color: transparent;" >
        <h1>shadowsocks</h1>
    </div>
</template>



<script>
    import { mapGetters,mapActions } from 'vuex'
    import effectlogo from '../../../loading/effect_logo.vue'
    import tipbar from '../../../common/tipbar.vue'
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
                'sysinfo'
            ])
        },
        mounted(){


        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
            ]),


        },
        components:{

        }
    }
</script>
<style>

</style>
