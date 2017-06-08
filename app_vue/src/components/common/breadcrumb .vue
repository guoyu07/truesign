<template>
    <div class="top_router_view">
        <el-breadcrumb separator="/">
            <el-breadcrumb-item v-for="(item,index) in breadcrumb_item_list" :key="item"  :to="item.to_path">{{item.value}}</el-breadcrumb-item>

        </el-breadcrumb>
    </div>
</template>


<script>
    export default {

        data() {
            return {
            };
        },
        props:{
            breadcrumb_item_list:{
                type: String,
                default:
                    [
                        {
                            to_path:{ path: '/后台首页' },
                            value:'后台首页'
                        },
                        {
                            to_path:{ path: '/活动管理' },
                            value:'活动管理'
                        },
                        {
                            to_path:{ path: '/活动列表' },
                            value:'活动列表'
                        },
                        {
                            to_path:{ path: '/活动详情' },
                            value:'活动详情'
                        }

                    ],
                required: false,

            },



        },
        methods: {
            handleSelect(key, keyPath) {
//                console.log(key, keyPath);
                this.$root.eventHub.$emit('changeNavMenu',key)
                this.$root.eventHub.$emit('destoryFullPage',key)

            }
        },
        mounted(){

        },

        computed:{

        }

    }
</script>
<style lang="stylus" rel="stylesheet/stylus">

</style>