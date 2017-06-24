<template>
    <div class="top_router_view" style="position:absolute;z-index:900;">
        <div class="parallax_div" style="margin-top:-70px;width: 100%;height: 50%;background-color: rgba(236,236,255,0.73);line-height:600px;font-size: 0.3rem">新故相推，日生不滞</div>
        <div class="parallax_div" style="width: 100%;height: 200px;background-color: transparent"></div>
        <div class="parallax_div" style="margin-top:-70px;width: 100%;height: 50%;background-color: rgba(236,236,255,0.73);line-height:600px;font-size: 0.3rem"></div>

        <img width="100%" height="70%" src="http://truesign-app.oss-cn-beijing.aliyuncs.com/tmp_img/538031d8c9c1794a155d07b5847dc9b6.jpg">

        <div style="width: 100%;height: 350px;background-color: transparent"></div>
        <div class="parallax_div" style="margin-top:-70px;width: 100%;height: 50%;background-color: rgba(236,236,255,0.73);line-height:600px;font-size: 0.3rem"></div>


        <parallax :fixed='true' >

            <img  style="" src="http://truesign-app.oss-cn-beijing.aliyuncs.com/tmp_img/7065230ad9fe0e846f902db49278f057.jpg" alt="very cool bg">
        </parallax>

    </div>
</template>


<script>
    import Parallax from 'vue-parallaxy'
    export default {
        components: {
            Parallax
        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
.parallax_div
    font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
    font-style: normal;
    font-stretch: normal;
    font-size: 1em;
    color: #00B5AD !important
</style>