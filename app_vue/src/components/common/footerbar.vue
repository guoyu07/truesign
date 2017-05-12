<template>
    <div class="common-footer">
        <div id="footerbar">
                <div class="show-ip" style="">IP   {{ sysinfo.ip }}</div>

            <!--网络-->

                <div class="svg_container" style="">
                    <div   style="position: absolute;">

                    </div>
                    <div v-if="website.conn_status"  id="footer_logo_conn_status">
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/sustainable.svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />
                    </div>
                    <div v-else="website.conn_status"  id="footer_logo_conn_status">
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/worldwide.svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />

                    </div>
                </div>
            <!--音乐-->
            <transition name="fade" mode="out-in">

                <div v-if="appshow.music" key="true" class="svg_container" @click="clickthis('music',$event)">
                    <div  style="position: absolute;">

                    </div>
                    <div>
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/music-player%20(1).svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />
                    </div>
                </div>
                <div v-else="appshow.music" key="false" class="svg_container" @click="clickthis('music',$event)">
                    <div  style="position: absolute;">

                    </div>
                    <div>
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/music-player.svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />
                    </div>
                </div>
            </transition>
                <!--<div class="svg_container">-->
                    <!--<div style="position: absolute;">-->

                    <!--</div>-->
                    <!--<div >-->
                        <!--<embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/play-button%20(2).svg" width="100%" height="100%"-->
                               <!--type="image/svg+xml"-->
                               <!--pluginspage="http://www.adobe.com/svg/viewer/install/" />-->
                    <!--</div>-->
                <!--</div>-->
            <!---->

            <div class="svg_container">
                    <div style="position: absolute;">

                    </div>
                    <div  >
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/no-photos.svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />
                    </div>
                </div>
                <div class="svg_container">
                    <div  style="position: absolute;">

                    </div>
                    <div >
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/muted%20(1).svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />
                    </div>
                </div>
            <!-- 聊天 -->
            <transition name="fade" mode="out-in">
                <div v-if="appshow.chat" key="true" class="svg_container" @click="clickthis('chat',$event)">
                    <div  style="position: absolute;">
                    </div>
                    <div >
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/chat.svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />
                    </div>
                </div>
                <div v-else="appshow.chat" key="false" class="svg_container" @click="clickthis('chat',$event)">
                    <div  style="position: absolute;">
                    </div>
                    <div >
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/speech-bubble%20(1).svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />
                    </div>
                </div>

            </transition>
            <!-- 设定 -->
                <div class="svg_container">
                    <div  style="position: absolute;">

                    </div>
                    <div >
                        <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/settings.svg" width="100%" height="100%"
                               type="image/svg+xml"
                               pluginspage="http://www.adobe.com/svg/viewer/install/" />
                    </div>
                </div>






        </div>
        <div class="footerbar-detail" v-if="appshow.music">

            <div class="footerbar-detail-bg" style="position: absolute;background-color: #3D4D5C;width: 100%;height:460px;z-index:-1">

            </div>
            <div class="footerbar-detail-content" style="position: absolute;width: 100%;z-index:10">
                <div v-if="appshow.music" style="margin-top: 10px">
                    <iframe frameborder="no" border="0" marginwidth="0" marginheight="0" width=330 height=450 src="//music.163.com/outchain/player?type=0&id=68897988&auto=1&height=430"></iframe>
                </div>
            </div>

        </div>

    </div>

</template>



<script>
    import Velocity from 'velocity-animate'
    import Vue from 'vue'
    import { mapGetters,mapActions } from 'vuex'

    export default {
        data() {
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
        mounted(){
            var vm = this
            this.$root.eventHub.$on('fresh_ip',function (data) {
                console.log('fresh_ip',data)
                vm.ip =  data
            })


        },
        methods:{
            ...mapActions([
                'updateWebSite',
                'updateSysInfo',
                'updateAppRules',
                'updateAppShow'
            ]),
            clickthis(type,e){
                if(type === 'chat'){
                    let change2status = false
                    if(this.appshow.chat){
                        change2status = false
                    }
                    else if(this.website.login_status && this.website.website_user.emailstatus){
                        change2status = true
                    }
                    this.updateAppShow({chat:change2status})
                }
                if(type === 'music'){
                    let change2status = true
                    if(this.appshow.music){
                        change2status = false
                    }
                    else{
                        change2status = true
                    }
                    console.log(change2status)
                    console.log(this.appshow.music)
                    this.updateAppShow({music:change2status})
                }
            }
        },


    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
.footerbar-detail
    background-color transparent

    width 100%;
    height 460px;
.common-footer
    width 100%
    min-height 25px
#footerbar
    margin 0
    padding 0
    border none
    left 0
    bottom 0
    width 100%
    line-height 20px
    /*padding-top 2px*/
    background-color transparent
    padding-left 20px;
    display flex
    box-shadow: 0 10px 15px -5px #1e242b;

.show-ip{
    /*width 25px*/
    /*height  25px*/
    min-width:200px
    margin-left 10px
    color rgba(209, 209, 209, 0.45)
    font-family:"Source Code Pro", Courier, monospace !important
    font-size 18px
}
#footer_logo_conn_status{
    width 14px
    height 14px;
    margin-top 2px;
    animation:rotatealways 3s   infinite normal
}
@keyframes rotatealways{
0%{
        transform:rotate(0deg)

    }
100%{
        transform:rotate(360deg)
    }
}
.svg_container,.svg_container div{
    cursor pointer
    height 20px
    width  20px
    margin-right 5px
}


</style>
