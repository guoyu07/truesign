/**
 * Created by ql-win on 2017/3/6.
 */
const videojs = require('video.js/dist/video.js')
class VideoPlayer{
    constructor(el) {
        this.el = el
        this.player =videojs(this.el)
        this.width = 1024
        this.height = 768


        this.whereAt = null
        this.howLong = null
        this.volume = null
        this.FullScreen = false


    }
    setSize(){
        // this.player.size(this.width,this.height)
    }
    play(){
        this.player.play()
    }
    pause(){
        this.player.pause();
    }
    whereAt(){
        this.whereAt = this.player.currentTime();
        return this.whereAt
    }
    setPlayAt(time){
        this.player.currentTime(time);
    }
    setVolume(num){
        this.player.volume(0.5);
    }
    setFullScreen(cmd){
        if(cmd){
            this.player.enterFullScreen()

        }
        else{
            this.player.exitFullscreen()

        }
    }
    reset2play(video_uri,vtt_uri){
        console.log(video_uri)
        console.log(vtt_uri)
        this.player.reset()
        var oldTracks = this.player.remoteTextTracks();

        var i = oldTracks.length;
        while (i--) {
            this.player.removeRemoteTextTrack(oldTracks[i]);
        }
        this.player.addRemoteTextTrack({
            label: 'chinese',
            language: 'zh',
            kind: 'subtitles',
            default: true,
            src: vtt_uri


        })
        // this.player.addTextTrack('subtitles',  'zh', 'chinese1')


        // this.player.addTextTrack({kind: 'subtitles', srclang: 'zh', label: 'chinese1',default:true})
        // // this.player.remoteTextTracks(vtt_uri)
        // // this.player.addTextTrack('subtitles','中文','zh')
        this.player.src({src:video_uri+'',type:"video/mp4"})
        this.player.load()
        this.player.play();
        this.player.textTracks()[0].mode = 'showing';

    }


}
module.exports = exports = VideoPlayer
