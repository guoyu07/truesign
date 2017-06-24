<template>
    <div class="root_home" style="overflow:auto;" >
        <div class="word_show">
            <h1>数据</h1>
            <h1>可视</h1>
            <h1>可控</h1>
            <h1>你知道的,一切皆可查询...</h1>
        </div>

    </div>
</template>
<script>

    export default {
        data(){
            return{
            }
        },
        mounted(){
//            this.$router.push('/project/website_main/website_index')
        },
        methods:{
            clickme(e){
                console.log(e)
            }
        },
        components:{


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
