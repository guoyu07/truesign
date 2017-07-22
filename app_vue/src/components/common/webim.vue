<template>
    <div id="webim">
        <H1>webim</H1>
    </div>
</template>



<script>

    /*
    * 出错无法使用*/
    import layui from 'layui-layer'
    require('layui-layer/layer.css')
    layui.use('layim', function(layim){
        //先来个客服模式压压精
        layim.config({
            brief: true //是否简约模式（如果true则不显示主面板）
        }).chat({
            name: '客服姐姐',type: 'friend',avatar: 'http://tp1.sinaimg.cn/5619439268/180/40030060651/1',id: -2
        });
    });
    export default {
        data(){
            return{

            }
        },
        mounted(){

        },
        components:{

        }
    }
</script>
<style>

</style>
