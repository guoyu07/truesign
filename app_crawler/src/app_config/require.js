import $ from 'jquery'
var wWidth = $(window).width();
var scale = 100*wWidth/750;
// console.log('scale',scale)

$("html").css("font-size",scale);
require('../../static/css/reset.css')
require('../../static/css/style.css')
// require('../../static/css/style.sass')
require('../../static/css/fade.styl')
require('../../static/css/line.styl')
require('../../static/css/keyframes.styl')
require('../../static/css/triangle.css')
// require('mint-ui/lib/style.css')
// require('semantic-ui/dist/semantic.css')
// require('semantic-ui/dist/semantic.js')
// 动效依赖
// require('animate.css/animate.css');// get animate.css
// require('animate.css/animate_zxx.css');// get animate.css
// require('velocity-animate/velocity.js')
// require('velocity-animate/velocity.ui.js')
require('node-waves/src/stylus/waves.styl')
//视频依赖
// require('video.js/dist/video-js.css')
// const  TWEEN = require('tween.js/src/tween.js')


