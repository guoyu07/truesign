/**
 * Created by ql-win on 2017/3/7.
 */
var scrollFunc = function (e) {
    e = e || window.event;
    if (e.wheelDelta) {  //判断浏览器IE，谷歌滑轮事件
        if (e.wheelDelta > 0) { //当滑轮向上滚动时
            $('#nav').removeClass('pullup')
            $('#nav').addClass('pullin')
        }
        if (e.wheelDelta < 0) { //当滑轮向下滚动时
            $('#nav').removeClass('pullin')
            $('#nav').addClass('pullup')
        }
    } else if (e.detail) {  //Firefox滑轮事件
        if (e.detail> 0) { //当滑轮向上滚动时
            $('#nav').removeClass('pullup')
            $('#nav').addClass('pullin')
        }
        if (e.detail< 0) { //当滑轮向下滚动时
            $('#nav').removeClass('pullin')
            $('#nav').addClass('pullup')
        }
    }
}
//给页面绑定滑轮滚动事件
if (document.addEventListener) { //firefox
    document.addEventListener('DOMMouseScroll', scrollFunc, false);
}
//滚动滑轮触发scrollFunc方法  //ie 谷歌
window.onmousewheel = document.onmousewheel = scrollFunc;
