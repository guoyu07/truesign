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


}
module.exports = exports = VideoPlayer
