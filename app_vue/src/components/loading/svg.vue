<template>
    <div id="svg_path" style="width: 100%;height: 100%;overflow-y: auto">
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/play-button%20(2).svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>播放按钮</span>
        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/pause.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>暂停按钮</span>
        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/settings.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>设置按钮</span>
        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/music-player.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>音乐库</span>
        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/music-player%20(1).svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>音乐库开</span>
        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/video-camera.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>摄像头·开</span>

        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/no-photos.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>摄像头·关</span>

        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/microphone.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>麦克风·开</span>

        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/muted%20(1).svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>麦克风·关</span>

        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/sustainable.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>连接·开</span>

        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/worldwide.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>连接·关</span>

        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/speech-bubble%20(1).svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>聊天·关</span>

        </div>
        <div style="">
            <embed src="http://truesign-app.oss-cn-beijing.aliyuncs.com/svg/chat.svg" width="100%" height="100%"
                   type="image/svg+xml"
                   pluginspage="http://www.adobe.com/svg/viewer/install/" />
            <span>聊天·开</span>

        </div>

    </div>
</template>

<script>

    export default  {
        data: function () {
            return {
            }
        },
    }
</script>

<style>
    #svg_path{
        padding: 20px 50px;
    }
    #svg_path div{
        width: 100px;
        height: 100px;
        display: inline-block;
        margin: 0;
        padding: 0;
    }
    #svg_path span{
        display: block;
        width: 100px;
        text-align: center;
    }
</style>
