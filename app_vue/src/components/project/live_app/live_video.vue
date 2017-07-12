<template>
  <div id="live_video" >

      <video
      id="my-player"
      class="video-js vjs-default-skin vjs-big-play-centered"
      controls
      preload="auto"
      poster="//localhost:5000/poster/arkady-lifshits-117993.png"
      data-setup='{}'
      style="margin: 0 auto"

      >
      <source src="http://192.168.1.6:5000/绝密飞行.mp4" type="video/mp4"></source>

      <track src="http://192.168.1.6:5000/subtitle/绝密飞行.vtt" kind="subtitles" default  srclang="en" label="蝙蝠侠前传1" />

          <!--<source src="" type="video/mp4"></source>-->
          <!--<track src="" kind="subtitles" default srclang="zh" label="chinese" />-->

      </video>
      <!--<el-input placeholder="请输入内容" v-model="video_list" style="margin: 0 50px;width: 1000px;"  >-->

        <!--<el-button slot="append" icon="search"></el-button>-->
      <!--</el-input>-->
  </div>
</template>
<script>
    import VideoPlayer from '../../../api/videoPlayer.js'
    import { parseURL } from '../../../api/Url'
    export default {

        data() {
            return {
                playstatus:0,
                advideouri:'',
                videoplayer:''
            }
        },
        mounted(){
            const videoplayer = new VideoPlayer('my-player')
            var vm = this

            videoplayer.setSize()
//            videoplayer.setPlayAt(2000)
            videoplayer.setVolume(1)
            videoplayer.player.width(window.innerWidth).height(window.innerHeight)
            videoplayer.player.load()
            videoplayer.play();
            this.videoplayer = videoplayer

            // How about an event listener?
            videoplayer.player.on('ended', function() {
                console.log('Awww...over so soon?!');
                vm.$root.eventHub.$emit('getNextPlay',vm.playstatus);
            });
            this.$root.eventHub.$on('sendNextPlay',function (data) {
                console.log('live_video->sendNextPlay')
                console.log(data)
                var url_data = parseURL(data.videouri)
                console.log(url_data)
                var vtt_file = url_data.path.replace('.mp4','.vtt')
                var vtt_url = url_data.all_host + 'subtitle/' + vtt_file
                videoplayer.reset2play(decodeURI(data.videouri),decodeURI(vtt_url))
            });

            this.$root.eventHub.$on('pauseOrplay',function (data) {
                console.log('pauseOrplay')
                vm.pauseOrplay(data)
            })



        },
        methods: {
            pauseOrplay(data){
                if(data){
                    this.videoplayer.play();
                }
                else{
                    this.videoplayer.pause();
                }
            }

        }
    }
</script>
<style lang="stylus" rel="stylesheet/stylus">
  #live_video
    margin 0 auto
    overflow hidden
    scroll no
</style>