var wWidth = $(window).width();
var scale = 100*wWidth/750;
// console.log('scale',scale)

$("html").css("font-size",scale);
require('normalize.css')
require('../../static/css/reset.css')
require('../../static/css/style.css')
require('../../static/css/fade.styl')
require('../../static/css/line.styl')
require('../../static/css/keyframes.styl')
require('../../static/css/triangle.css')
require('element-ui/lib/theme-default/index.css')
require('font-awesome/css/font-awesome.css');// get font-awesome
require('bootstrap/dist/css/bootstrap.css')
require('bootstrap/dist/js/bootstrap.min')
require('node-waves/src/stylus/waves.styl')
//视频依赖
// require('video.js/dist/video-js.css')
// const  TWEEN = require('tween.js/src/tween.js')


