!function(e){function t(n){if(o[n])return o[n].exports;var A=o[n]={exports:{},id:n,loaded:!1};return e[n].call(A.exports,A,A.exports,t),A.loaded=!0,A.exports}var n=window.webpackJsonp;window.webpackJsonp=function(o,i){for(var r,s,a=0,d=[];a<o.length;a++)s=o[a],A[s]&&d.push.apply(d,A[s]),A[s]=0;for(r in i)e[r]=i[r];for(n&&n(o,i);d.length;)d.shift().call(null,t)};var o={},A={1:0};return t.e=function(e,n){if(0===A[e])return n.call(null,t);if(void 0!==A[e])A[e].push(n);else{A[e]=[n];var o=document.getElementsByTagName("head")[0],i=document.createElement("script");i.type="text/javascript",i.charset="utf-8",i.async=!0,i.src=t.p+"js/"+e+".js?d37445f8c927210a7983",o.appendChild(i)}},t.m=e,t.c=o,t.p="/",t(0)}([function(e,t,n){e.exports=n(2)},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o="https://production.server.com",A="https://dev.server.com",i=null;"production"===production?i=o:"dev"===production&&(i=A),t["default"]=i},function(e,t,n){"use strict";function o(e){return e&&e.__esModule?e:{"default":e}}var A=n(3),i=o(A),r=n(10),s=o(r),a=n(17),d=o(a),c=n(26),u=o(c),g=n(1),l=o(g);console.log(l["default"],"---我是测试环境变量配置用"),Vue.use(u["default"]);new Vue({el:"#container",methods:{sayHi:function(){n.e(2,function(){var e=n(27).say;e("hi")})},tap:function(){console.log("hahah")}},components:{IndexInfo:s["default"],Loading:d["default"],myHead:i["default"]}})},function(e,t,n){var o,A,i={};n(4),o=n(8),o&&o.__esModule&&Object.keys(o).length>1&&console.warn("[vue-loader] src/components/home/home-header.vue: named exports in *.vue files are ignored."),A=n(9),e.exports=o||{},e.exports.__esModule&&(e.exports=e.exports["default"]);var r="function"==typeof e.exports?e.exports.options||(e.exports.options={}):e.exports;A&&(r.template=A),r.computed||(r.computed={}),Object.keys(i).forEach(function(e){var t=i[e];r.computed[e]=function(){return t}})},function(e,t,n){var o=n(5);"string"==typeof o&&(o=[[e.id,o,""]]);n(7)(o,{});o.locals&&(e.exports=o.locals)},function(e,t,n){t=e.exports=n(6)(),t.push([e.id,"\n\n\n\n\n\n\n",""])},function(e,t){e.exports=function(){var e=[];return e.toString=function(){for(var e=[],t=0;t<this.length;t++){var n=this[t];n[2]?e.push("@media "+n[2]+"{"+n[1]+"}"):e.push(n[1])}return e.join("")},e.i=function(t,n){"string"==typeof t&&(t=[[null,t,""]]);for(var o={},A=0;A<this.length;A++){var i=this[A][0];"number"==typeof i&&(o[i]=!0)}for(A=0;A<t.length;A++){var r=t[A];"number"==typeof r[0]&&o[r[0]]||(n&&!r[2]?r[2]=n:n&&(r[2]="("+r[2]+") and ("+n+")"),e.push(r))}},e}},function(e,t,n){function o(e,t){for(var n=0;n<e.length;n++){var o=e[n],A=u[o.id];if(A){A.refs++;for(var i=0;i<A.parts.length;i++)A.parts[i](o.parts[i]);for(;i<o.parts.length;i++)A.parts.push(a(o.parts[i],t))}else{for(var r=[],i=0;i<o.parts.length;i++)r.push(a(o.parts[i],t));u[o.id]={id:o.id,refs:1,parts:r}}}}function A(e){for(var t=[],n={},o=0;o<e.length;o++){var A=e[o],i=A[0],r=A[1],s=A[2],a=A[3],d={css:r,media:s,sourceMap:a};n[i]?n[i].parts.push(d):t.push(n[i]={id:i,parts:[d]})}return t}function i(e,t){var n=p(),o=I[I.length-1];if("top"===e.insertAt)o?o.nextSibling?n.insertBefore(t,o.nextSibling):n.appendChild(t):n.insertBefore(t,n.firstChild),I.push(t);else{if("bottom"!==e.insertAt)throw new Error("Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'.");n.appendChild(t)}}function r(e){e.parentNode.removeChild(e);var t=I.indexOf(e);t>=0&&I.splice(t,1)}function s(e){var t=document.createElement("style");return t.type="text/css",i(e,t),t}function a(e,t){var n,o,A;if(t.singleton){var i=M++;n=f||(f=s(t)),o=d.bind(null,n,i,!1),A=d.bind(null,n,i,!0)}else n=s(t),o=c.bind(null,n),A=function(){r(n)};return o(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;o(e=t)}else A()}}function d(e,t,n,o){var A=n?"":o.css;if(e.styleSheet)e.styleSheet.cssText=v(t,A);else{var i=document.createTextNode(A),r=e.childNodes;r[t]&&e.removeChild(r[t]),r.length?e.insertBefore(i,r[t]):e.appendChild(i)}}function c(e,t){var n=t.css,o=t.media,A=t.sourceMap;if(o&&e.setAttribute("media",o),A&&(n+="\n/*# sourceURL="+A.sources[0]+" */",n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(A))))+" */"),e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}var u={},g=function(e){var t;return function(){return"undefined"==typeof t&&(t=e.apply(this,arguments)),t}},l=g(function(){return/msie [6-9]\b/.test(window.navigator.userAgent.toLowerCase())}),p=g(function(){return document.head||document.getElementsByTagName("head")[0]}),f=null,M=0,I=[];e.exports=function(e,t){t=t||{},"undefined"==typeof t.singleton&&(t.singleton=l()),"undefined"==typeof t.insertAt&&(t.insertAt="bottom");var n=A(e);return o(n,t),function(e){for(var i=[],r=0;r<n.length;r++){var s=n[r],a=u[s.id];a.refs--,i.push(a)}if(e){var d=A(e);o(d,t)}for(var r=0;r<i.length;r++){var a=i[r];if(0===a.refs){for(var c=0;c<a.parts.length;c++)a.parts[c]();delete u[a.id]}}}};var v=function(){var e=[];return function(t,n){return e[t]=n,e.filter(Boolean).join("\n")}}()},function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t["default"]={ready:function(){console.log("home-header,测试所有home业务模块下的js都会被编译")}}},function(e,t){e.exports="\n<h1>\n\t我是home业务模块下的公共头部,修改我home业务模块下的js都会编译\n</h1>\n"},function(e,t,n){var o,A,i={};n(11),o=n(14),o&&o.__esModule&&Object.keys(o).length>1&&console.warn("[vue-loader] src/components/home/index-info.vue: named exports in *.vue files are ignored."),A=n(15),e.exports=o||{},e.exports.__esModule&&(e.exports=e.exports["default"]);var r="function"==typeof e.exports?e.exports.options||(e.exports.options={}):e.exports;A&&(r.template=A),r.computed||(r.computed={}),Object.keys(i).forEach(function(e){var t=i[e];r.computed[e]=function(){return t}})},function(e,t,n){var o=n(12);"string"==typeof o&&(o=[[e.id,o,""]]);n(7)(o,{});o.locals&&(e.exports=o.locals)},function(e,t,n){t=e.exports=n(6)(),t.push([e.id,"#kodo {\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex; }\n\n#bg h3 {\n  background: url("+n(13)+");\n  color: #fff; }\n\nimg {\n  width: 70px;\n  height: 70px;\n  border-radius: 50%; }\n",""])},function(e,t,n){e.exports=n.p+"images/holmes.jpg?39e4bbb59b"},function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t["default"]={ready:function(){console.log("修改这里测试实时编译")}}},function(e,t,n){e.exports='\n<div>\n\t<h1>我是用户信息</h1>\n\t<figure>\n\t\t<img src="'+n(16)+'" alt="头像">\n\t</figure>\n\t<div id="bg">\n\t\t<h3>这里是测试背景图片</h3>\n\t\t<p></p>\n\t</div>\n\n\t<div id="kodo">\n\t\t我是组件里引入的sass,并且是display:flex\n\t</div>\n\n</div>\n'},function(e,t,n){e.exports=n.p+"images/logo.jpg?82ceb5918f"},function(e,t,n){var o,A,i={};n(18),o=n(24),o&&o.__esModule&&Object.keys(o).length>1&&console.warn("[vue-loader] src/components/common/loading.vue: named exports in *.vue files are ignored."),A=n(25),e.exports=o||{},e.exports.__esModule&&(e.exports=e.exports["default"]);var r="function"==typeof e.exports?e.exports.options||(e.exports.options={}):e.exports;A&&(r.template=A),r.computed||(r.computed={}),Object.keys(i).forEach(function(e){var t=i[e];r.computed[e]=function(){return t}})},function(e,t,n){var o=n(19);"string"==typeof o&&(o=[[e.id,o,""]]);n(7)(o,{});o.locals&&(e.exports=o.locals)},function(e,t,n){t=e.exports=n(6)(),t.push([e.id,"i[_v-e9586cb0] {\n  font-size: 20px;\n  color: #abcedf; }\n\n@font-face {\n  font-family: 'iconfont';\n  src: url("+n(20)+");\n  /* IE9*/\n  src: url("+n(20)+'?#iefix) format("embedded-opentype"), url('+n(21)+') format("woff"), url('+n(22)+') format("truetype"), url('+n(23)+'#iconfont) format("svg");\n  /* iOS 4.1- */ }\n\n.iconfont[_v-e9586cb0] {\n  font-family: "iconfont" !important;\n  font-size: 16px;\n  font-style: normal;\n  -webkit-font-smoothing: antialiased;\n  -webkit-text-stroke-width: 0.2px;\n  -moz-osx-font-smoothing: grayscale; }\n',""])},function(e,t,n){e.exports=n.p+"fonts/iconfont.ebec254.eot"},function(e,t){e.exports="data:application/x-font-woff;base64,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"},function(e,t,n){e.exports=n.p+"fonts/iconfont.b441fce.ttf"},function(e,t){e.exports="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiID4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8bWV0YWRhdGE+CkNyZWF0ZWQgYnkgRm9udEZvcmdlIDIwMTIwNzMxIGF0IE1vbiBBdWcgMTUgMTE6MDU6MjYgMjAxNgogQnkgYWRtaW4KPC9tZXRhZGF0YT4KPGRlZnM+Cjxmb250IGlkPSJpY29uZm9udCIgaG9yaXotYWR2LXg9IjM3NCIgPgogIDxmb250LWZhY2UgCiAgICBmb250LWZhbWlseT0iaWNvbmZvbnQiCiAgICBmb250LXdlaWdodD0iNTAwIgogICAgZm9udC1zdHJldGNoPSJub3JtYWwiCiAgICB1bml0cy1wZXItZW09IjEwMjQiCiAgICBwYW5vc2UtMT0iMiAwIDYgMyAwIDAgMCAwIDAgMCIKICAgIGFzY2VudD0iODk2IgogICAgZGVzY2VudD0iLTEyOCIKICAgIHgtaGVpZ2h0PSI3OTIiCiAgICBiYm94PSIzNCAtMTQ3IDk1NiA3OTIiCiAgICB1bmRlcmxpbmUtdGhpY2tuZXNzPSI1MCIKICAgIHVuZGVybGluZS1wb3NpdGlvbj0iLTEwMCIKICAgIHVuaWNvZGUtcmFuZ2U9IlUrMDA3OC1FNjg0IgogIC8+CjxtaXNzaW5nLWdseXBoIApkPSJNMzQgMHY2ODJoMjcydi02ODJoLTI3MnpNNjggMzRoMjA0djYxNGgtMjA0di02MTR6IiAvPgogICAgPGdseXBoIGdseXBoLW5hbWU9Ii5ub3RkZWYiIApkPSJNMzQgMHY2ODJoMjcydi02ODJoLTI3MnpNNjggMzRoMjA0djYxNGgtMjA0di02MTR6IiAvPgogICAgPGdseXBoIGdseXBoLW5hbWU9Ii5udWxsIiBob3Jpei1hZHYteD0iMCIgCiAvPgogICAgPGdseXBoIGdseXBoLW5hbWU9Im5vbm1hcmtpbmdyZXR1cm4iIGhvcml6LWFkdi14PSIzNDEiIAogLz4KICAgIDxnbHlwaCBnbHlwaC1uYW1lPSJ4IiB1bmljb2RlPSJ4IiBob3Jpei1hZHYteD0iMTAwMSIgCmQ9Ik0yODEgNTQzcS0yNyAtMSAtNTMgLTFoLTgzcS0xOCAwIC0zNi41IC02dC0zMi41IC0xOC41dC0yMyAtMzJ0LTkgLTQ1LjV2LTc2aDkxMnY0MXEwIDE2IC0wLjUgMzB0LTAuNSAxOHEwIDEzIC01IDI5dC0xNyAyOS41dC0zMS41IDIyLjV0LTQ5LjUgOWgtMTMzdi05N2gtNDM4djk3ek05NTUgMzEwdi01MnEwIC0yMyAwLjUgLTUydDAuNSAtNTh0LTEwLjUgLTQ3LjV0LTI2IC0zMHQtMzMgLTE2dC0zMS41IC00LjVxLTE0IC0xIC0yOS41IC0wLjUKdC0yOS41IDAuNWgtMzJsLTQ1IDEyOGgtNDM5bC00NCAtMTI4aC0yOWgtMzRxLTIwIDAgLTQ1IDFxLTI1IDAgLTQxIDkuNXQtMjUuNSAyM3QtMTMuNSAyOS41dC00IDMwdjE2N2g5MTF6TTE2MyAyNDdxLTEyIDAgLTIxIC04LjV0LTkgLTIxLjV0OSAtMjEuNXQyMSAtOC41cTEzIDAgMjIgOC41dDkgMjEuNXQtOSAyMS41dC0yMiA4LjV6TTMxNiAxMjNxLTggLTI2IC0xNCAtNDhxLTUgLTE5IC0xMC41IC0zN3QtNy41IC0yNXQtMyAtMTV0MSAtMTQuNQp0OS41IC0xMC41dDIxLjUgLTRoMzdoNjdoODFoODBoNjRoMzZxMjMgMCAzNCAxMnQyIDM4cS01IDEzIC05LjUgMzAuNXQtOS41IDM0LjVxLTUgMTkgLTExIDM5aC0zNjh6TTMzNiA0OTh2MjI4cTAgMTEgMi41IDIzdDEwIDIxLjV0MjAuNSAxNS41dDM0IDZoMTg4cTMxIDAgNTEuNSAtMTQuNXQyMC41IC01Mi41di0yMjdoLTMyN3oiIC8+CiAgICA8Z2x5cGggZ2x5cGgtbmFtZT0idW5pRTY4NCIgdW5pY29kZT0iJiN4ZTY4NDsiIGhvcml6LWFkdi14PSIxMDI0IiAKZD0iTTg5MSA1NjJoLTE4NnExNyAzMCAxNyA2NnEwIDQ4IC0zMSA4My41dC04NiAzNS41cS01MyAwIC04OSAtMzdxLTE4IC0xOCAtMzEgLTQ2cS0xMiAyNyAtMzAgNDZxLTM2IDM3IC04OSAzN3EtNTUgMCAtODggLTM4cS0yOSAtMzMgLTI5IC04MXEwIC0zNiAxOCAtNjZoLTE3NXEtMTEgMCAtMTkuNSAtOC41dC04LjUgLTIwLjV2LTI1OXEwIC0xMSA4LjUgLTE5LjV0MTkuNSAtOC41aDM4di0zNjVxMCAtMTIgOC41IC0yMHQxOS41IC04aDY2OApxMTEgMCAxOS41IDh0OC41IDIwdjM2NWgzN3ExMiAwIDIwIDguNXQ4IDE5LjV2MjU5cTAgMTIgLTggMjAuNXQtMjAgOC41ek01NTUgNjcwcTIwIDIwIDUwIDIwcTMxIDAgNDYgLTE4LjV0MTUgLTQzLjVxMCAtMjggLTE1IC00NnEtMTcgLTIwIC00NiAtMjBsLTg5IDFxOCA3NCAzOSAxMDd6TTMwNSA2MjhxMCAyNiAxNSA0NHExNiAxOCA0NiAxOHQ0OSAtMjBxMzEgLTMyIDM4IC0xMDhoLTg3cS0zMiAwIC00Ni41IDIxdC0xNC41IDQ1ek0xMjAgNTA1CmgyNjd2LTIwMmgtMjY3djIwMnpNMTg2IDI0NmgyMDF2LTMzNmgtMjAxdjMzNnpNNDQzIC05MHY1OTVoOTh2LTU5NWgtOTh6TTc5OCAtOTBoLTIwMXYzMzZoMjAxdi0zMzZ6TTg2NCAzMDNoLTI2N3YyMDJoMjY3di0yMDJ6IiAvPgogIDwvZm9udD4KPC9kZWZzPjwvc3ZnPgo="},function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t["default"]={ready:function(){console.log("loading,测试所有入口js都会被编译")}}},function(e,t){e.exports='\n    <div id="loading" _v-e9586cb0="">\n\t\t<!--修改这里试试,所有js都会编译,因为他是common全局公用的-->\n\t\t<h4 _v-e9586cb0="">loading组件 正在加载中,请稍等...</h4>\n\t\t<!-- 测试字体文件 -->\n\t\t<i class="iconfont" _v-e9586cb0=""></i>\n\t</div>\n'},function(e,t,n){!function(){function t(){for(var e=navigator.userAgent,t=["Android","iPhone","Windows Phone","iPad","iPod"],n=!0,o=0;o<t.length;o++)if(e.indexOf(t[o])>0){n=!1;break}return n}function n(e){if(r?e.disabled:e.el.disabled)return!1;var t=e.tapObj;return e.time<150&&Math.abs(t.distanceX)<2&&Math.abs(t.distanceY)<2}function o(e,t){var n=e.touches[0],o=t.tapObj;o.pageX=n.pageX,o.pageY=n.pageY,o.clientX=n.clientX,o.clientY=n.clientY,t.time=+new Date}function A(e,t){var o=e.changedTouches[0],A=t.tapObj;t.time=+new Date-t.time,A.distanceX=A.pageX-o.pageX,A.distanceY=A.pageY-o.pageY,n(t)&&setTimeout(function(){t.handler(e)},150)}var i={},r=!1,s={isFn:!0,acceptStatement:!0,update:function(e){var n=this;return n.tapObj={},"function"!=typeof e&&"a"!==n.el.tagName.toLocaleLowerCase()?console.error('The param of directive "v-tap" must be a function!'):(n.handler=function(t){return t.tapObj=n.tapObj,n.el.href&&!n.modifiers.prevent?window.location=n.el.href:void e.call(n,t)},void(t()?n.el.addEventListener("click",function(e){return n.el.href&&!n.modifiers.prevent?window.location=n.el.href:void n.handler.call(n,e)},!1):(n.el.addEventListener("touchstart",function(e){n.modifiers.stop&&e.stopPropagation(),n.modifiers.prevent&&e.preventDefault(),o(e,n)},!1),n.el.addEventListener("touchend",function(e){return Object.defineProperties(e,{currentTarget:{value:n.el,writable:!0,enumerable:!0,configurable:!0}}),e.preventDefault(),A(e,n)},!1))))}},a={bind:function(e,n){var i=n.value;e.tapObj={},e.handler=function(t){return i||!e.href||n.modifiers.prevent?(i.event=t,i.tapObj=e.tapObj,void i.methods.call(this,i)):window.location=e.href},t()?e.addEventListener("click",function(t){return i||!e.href||n.modifiers.prevent?(i.event=t,void i.methods.call(this,i)):window.location=e.href},!1):(e.addEventListener("touchstart",function(t){n.modifiers.stop&&t.stopPropagation(),n.modifiers.prevent&&t.preventDefault(),o(t,e)},!1),e.addEventListener("touchend",function(t){return Object.defineProperties(t,{currentTarget:{value:e,writable:!0,enumerable:!0,configurable:!0}}),t.preventDefault(),A(t,e)},!1))}};i.install=function(e){e.version.substr(0,1)>1&&(r=!0),e.directive("tap",r?a:s)},e.exports=i}()}]);