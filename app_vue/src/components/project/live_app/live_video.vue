<template>
  <div id="live_video" >

      <video
      id="my-player"
      class="video-js vjs-default-skin vjs-big-play-centered"
      controls
      preload="auto"
      poster="//vjs.zencdn.net/v/oceans.png"
      data-setup='{}'
      style="margin: 0 auto"

      >
      <source src="http://localhost:5000/加勒比海盗4.mp4" type="video/mp4"></source>

      </video>
      <!--<el-input placeholder="请输入内容" v-model="video_list" style="margin: 0 50px;width: 1000px;"  >-->

        <!--<el-button slot="append" icon="search"></el-button>-->
      <!--</el-input>-->
  </div>
</template>
<script>
    import VideoPlayer from '../../../api/videoPlayer.js'
    export default {

        data() {
            return {

            }
        },
        mounted(){
            const videoplayer = new VideoPlayer('my-player')

            videoplayer.setSize()
            videoplayer.setPlayAt(2000)
            videoplayer.setVolume(1)
            videoplayer.player.width(window.innerWidth).height(window.innerHeight)
            videoplayer.player.load()
            videoplayer.play();

            // How about an event listener?
            videoplayer.player.on('ended', function() {
                console.log('Awww...over so soon?!');


            });





        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
  #live_video
    margin 0 auto
    overflow hidden
    scroll no
</style>